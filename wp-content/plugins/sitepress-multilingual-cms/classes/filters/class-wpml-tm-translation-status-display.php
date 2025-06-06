<?php

use WPML\FP\Fns;
use WPML\FP\Maybe;
use WPML\Settings\PostType\Automatic;
use WPML\Setup\Option;
use WPML\TM\ATE\TranslateEverything;
use WPML\FP\Lst;
use WPML\FP\Obj;
use WPML\FP\Logic;
use WPML\TM\API\ATE\CachedLanguageMappings;
use WPML\Element\API\Languages;
use WPML\LIB\WP\Post;
use WPML\API\PostTypes;
use WPML\TM\API\Jobs;
use function WPML\FP\partial;
use WPML\LIB\WP\User;
use WPML\UIPage;
use WPML\FP\Relation;
use function WPML\FP\pipe;

class WPML_TM_Translation_Status_Display {

	private $statuses        = array();
	private $stats_preloaded = false;

	/**
	 * @var WPML_Post_Status
	 */
	private $status_helper;

	/**
	 * @var WPML_Translation_Job_Factory
	 */
	private $job_factory;

	/**
	 * @var WPML_TM_API
	 */
	protected $tm_api;

	/**
	 * @var WPML_Post_Translation
	 */
	private $post_translations;

	/**
	 * @var SitePress
	 */
	protected $sitepress;

	private $original_links  = array();
	private $tm_editor_links = array();
	/**
	 * @var \wpdb
	 */
	private $wpdb;

	/** @var TranslateEverything\UntranslatedPosts  */
	private $untranslatedPosts;

	/**
	 * WPML_TM_Translation_Status_Display constructor.
	 *
	 * @param wpdb                         $wpdb
	 * @param SitePress                    $sitepress
	 * @param WPML_Post_Status             $status_helper
	 * @param WPML_Translation_Job_Factory $job_factory
	 * @param WPML_TM_API                  $tm_api
	 * @param TranslateEverything\UntranslatedPosts $untranslatedPosts
	 */
	public function __construct(
		wpdb $wpdb,
		SitePress $sitepress,
		WPML_Post_Status $status_helper,
		WPML_Translation_Job_Factory $job_factory,
		WPML_TM_API $tm_api,
		TranslateEverything\UntranslatedPosts $untranslatedPosts
	) {
		$this->post_translations = $sitepress->post_translations();
		$this->wpdb              = $wpdb;
		$this->status_helper     = $status_helper;
		$this->job_factory       = $job_factory;
		$this->tm_api            = $tm_api;
		$this->sitepress         = $sitepress;
		$this->untranslatedPosts = $untranslatedPosts;
	}

	public function init() {
		add_action( 'wpml_cache_clear', array( $this, 'init' ), 11, 0 );
		add_filter(
			'wpml_css_class_to_translation',
			array(
				$this,
				'filter_status_css_class',
			),
			10,
			4
		);
		add_filter(
			'wpml_link_to_translation',
			array(
				$this,
				'filter_status_link',
			),
			10,
			4
		);
		add_filter(
			'wpml_text_to_translation',
			array(
				$this,
				'filter_status_text',
			),
			10,
			4
		);

		add_filter( 'wpml_post_status_display_html', array( $this, 'add_links_data_attributes' ), 10, 4 );

		$this->statuses = array();
	}

	private function preload_stats() {
		$this->load_stats( $this->post_translations->get_trids() );
		$this->stats_preloaded = true;
	}

	private function load_stats( $trids ) {
		if ( ! $trids ) {
			return;
		}

		$trids       = wpml_prepare_in( $trids );
		$trids_query = "translations.trid IN ( {$trids} )";
		$stats       = $this->wpdb->get_results(
			"SELECT translation_status.status,
       				languages.code,
       				translation_status.translator_id,
       				translation_status.translation_service,
       				translation_status.needs_update,
       				translations.trid,
       			    translate_job.job_id,
       				translate_job.editor,
       				translate_job.automatic
				FROM {$this->wpdb->prefix}icl_languages languages
				LEFT JOIN {$this->wpdb->prefix}icl_translations translations
					ON languages.code = translations.language_code
				JOIN {$this->wpdb->prefix}icl_translation_status translation_status
					ON translations.translation_id = translation_status.translation_id
				JOIN {$this->wpdb->prefix}icl_translate_job translate_job
					ON translate_job.rid = translation_status.rid AND translate_job.revision IS NULL
				WHERE languages.active = 1
					AND {$trids_query}
					OR translations.trid IS NULL",
			ARRAY_A
		);
		foreach ( $stats as $element ) {
			$this->statuses[ $element['trid'] ][ $element['code'] ] = $element;
		}

	}

	public function filter_status_css_class( $css_class, $post_id, $lang, $trid ) {
		$this->maybe_load_stats( $trid );
		$element_id  = $this->post_translations->get_element_id( $lang, $trid );
		$source_lang = $this->post_translations->get_source_lang_code( $element_id );
		$post_status = $this->get_post_status( $post_id );

		if ( $this->is_in_progress( $trid, $lang ) ) {
			$css_class = 'otgs-ico-in-progress';
		} elseif ( $this->is_in_basket( $trid, $lang )
		           || ( ! $this->is_lang_pair_allowed( $lang, $source_lang, $post_id ) && $element_id )
		) {
			$css_class .= ' otgs-ico-edit-disabled';
		} elseif ( ! $this->is_lang_pair_allowed( $lang, $source_lang, $post_id ) && ! $element_id ) {
			$css_class .= ' otgs-ico-add-disabled';
		} elseif ( ! $this->has_user_rights_to_translate( $trid, $lang ) ) {
			$css_class .= ' otgs-ico-edit-disabled';
		}

		if ( ( $this->isTranslateEverythingInProgress( $trid, $post_id, $lang ) && ( 'draft' !== $post_status || $this->is_in_progress( $trid, $lang ) ) ) ) {
			$css_class .= ' otgs-ico-waiting';
		}

		return $css_class;
	}

	private function get_post_status( $post_id ) {
		return Maybe::of( $post_id )
			->map( 'get_post' )
			->map( Obj::prop( 'post_status' ) )
			->getOrElse( partial( 'get_post_status', $post_id ) );
	}

	public function filter_status_text( $text, $original_post_id, $lang, $trid ) {
		$source_lang = $this->post_translations->get_element_lang_code( $original_post_id );

		$this->maybe_load_stats( $trid );
		if ( ( $this->is_remote( $trid, $lang ) && $this->is_in_progress( $trid, $lang ) ) || $this->it_needs_retry( $trid, $lang ) ) {
			$ts_name = TranslationProxy::get_service_name( intval( $this->statuses[ $trid ][ $lang ]['translation_service'] ) );
			$text    = sprintf( __( 'Waiting for translation from %s', 'sitepress' ), $ts_name );

		} elseif ( $this->is_in_basket( $trid, $lang ) ) {
			$text = __(
				'Cannot edit this item, because it is currently in the translation basket.',
				'sitepress'
			);
		} elseif ( $this->is_lang_pair_allowed( $lang, null, $original_post_id ) && $this->is_in_progress( $trid, $lang ) ) {
			$language = $this->sitepress->get_language_details( $lang );

			if ( $this->shouldAutoTranslate( $trid, $original_post_id, $lang ) && $this->has_no_manual_translation( $trid, $lang ) ) {
				$text = sprintf( __( '%s: Waiting for automatic translation', 'sitepress' ), $language['display_name'] );
			} else {
				$text = $this->get_in_progress_status_txt( $trid, $lang, $language );
			}
		} elseif ( ! $this->is_lang_pair_allowed( $lang, $source_lang, $original_post_id ) ) {
			$language        = $this->sitepress->get_language_details( $lang );
			$source_language = $this->sitepress->get_language_details( $source_lang );
			$text            = sprintf(
				__( 'You don\'t have the rights to translate from %1$s to %2$s', 'sitepress' ),
				$source_language['display_name'],
				$language['display_name']
			);
		} elseif ( ! $this->has_user_rights_to_translate( $trid, $lang ) ) {
			$text = __( 'You can only edit translations assigned to you.', 'sitepress' );
		}

		if ( $this->isTranslateEverythingInProgress( $trid, $original_post_id, $lang ) ) {
			$text = __( 'WPML is translating your content automatically. You can monitor the progress in the admin bar.', 'sitepress' );
		}

		return $text;
	}

	/**
	 * Determine if there is no manual translation for the given trid
	 * and language.
	 *
	 * @param int    $trid
	 * @param string $lang
	 *
	 * @return bool
	 */
	private function has_no_manual_translation( $trid, $lang ) {
		if (
			! array_key_exists( $trid, $this->statuses ) ||
			! array_key_exists( $lang, $this->statuses[ $trid ] )
		) {
			// There is no job yet for this language.
			return true;
		}

		// The ICL_TM_NOT_TRANSLATED flag is used for jobs which were canceled.
		// I.e. Job created on post update while a Translation Service is
		// active, but before that service has been able to translate the post,
		// the user switched to Translate Everything.
		$job_is_canceled  = pipe(
			Obj::path( [ $trid, $lang, 'status' ] ),
			Relation::equals( ICL_TM_NOT_TRANSLATED )
		);
		$job_is_automatic = Obj::path( [ $trid, $lang, 'automatic' ] );

		return Logic::anyPass(
			[
				$job_is_canceled,
				$job_is_automatic,
			],
			$this->statuses
		);
	}

	/**
	 * @param string $link
	 * @param int    $post_id
	 * @param string $lang
	 * @param int    $trid
	 *
	 * @return string
	 */
	public function filter_status_link( $link, $post_id, $lang, $trid ) {

		$this->original_links[ $post_id ][ $lang ][ $trid ] = $link;

		$translated_element_id = $this->post_translations->get_element_id( $lang, $trid );
		$source_lang           = $this->post_translations->get_source_lang_code( $translated_element_id );

		if ( (bool) $translated_element_id && (bool) $source_lang === false ) {
			$this->tm_editor_links[ $post_id ][ $lang ][ $trid ] = $link;

			return $link;
		}

		$this->maybe_load_stats( $trid );
		$is_remote        = $this->is_remote( $trid, $lang );
		$is_in_progress   = $this->is_in_progress( $trid, $lang );
		$does_need_update = (bool) Obj::pathOr( false, [ $trid, $lang, 'needs_update' ], $this->statuses );
		$use_tm_editor    = $this->shouldUseTMEditor( $post_id );

		$source_lang_code = $this->post_translations->get_element_lang_code( $post_id );

		$is_local_job_in_progress  = $is_in_progress && ! $is_remote;
		$is_remote_job_in_progress = $is_remote && $is_in_progress;
		$translation_exists        = (bool) $translated_element_id;

		$tm_editor_link = '';

		if (
			$is_remote_job_in_progress ||
			$this->is_in_basket( $trid, $lang ) ||
			! $this->is_lang_pair_allowed( $lang, $source_lang, $post_id ) ||
			$this->it_needs_retry( $trid, $lang )
		) {
			$link = '';
			$this->original_links[ $post_id ][ $lang ][ $trid ] = ''; // Also block the native editor
		} elseif ( $source_lang_code !== $lang ) {
			$job_id = null;

			if ( $is_local_job_in_progress || $translation_exists ) {
				$job_id = $this->job_factory->job_id_by_trid_and_lang( $trid, $lang );
				if ( $job_id && ! is_admin() ) {
					$job_object = $this->job_factory->get_translation_job( $job_id, false, 0, true );
					if ( $job_object && ! $job_object->user_can_translate( wp_get_current_user() ) ) {
						return $link;
					}
				}
			}

			if ( $job_id ) {
				if ( $does_need_update && $this->shouldAutoTranslate( $trid, $post_id, $lang ) ) {
					$tm_editor_link = '#';
				} else {
					$tm_editor_link = $this->get_link_for_existing_job( $job_id );
				}
			} else {
				$tm_editor_link = $this->get_link_for_new_job( $post_id, $trid, $lang, $source_lang_code );
			}

			if ( $is_local_job_in_progress || $use_tm_editor ) {
				$link = $tm_editor_link;
			}
		}

		$this->tm_editor_links[ $post_id ][ $lang ][ $trid ] = $tm_editor_link;

		return $link;
	}

	private function shouldUseTMEditor( $postId ) {
		static $cachedKeys;
		if ( ! isset( $cachedKeys[ $postId ] ) ) {
			list( $useTmEditor, $isWpmlEditorBlocked, $reason ) = \WPML_TM_Post_Edit_TM_Editor_Mode::get_editor_settings( $this->sitepress, $postId );
			$cachedKeys[ $postId ]                              = $useTmEditor && ! $isWpmlEditorBlocked;
		}

		return $cachedKeys[ $postId ];
	}

	/**
	 * @param string $html
	 * @param int    $post_id
	 * @param string $lang
	 * @param int    $trid
	 *
	 * @return string
	 */
	public function add_links_data_attributes( $html, $post_id, $lang, $trid ) {
		if ( ! isset(
			$this->original_links[ $post_id ][ $lang ][ $trid ],
			$this->tm_editor_links[ $post_id ][ $lang ][ $trid ]
		)
		) {
			return $html;
		}

		$this->maybe_load_stats( $trid );

		$Attributes = [
			'original-link'      => $this->original_links[ $post_id ][ $lang ][ $trid ],
			'tm-editor-link'     => $this->tm_editor_links[ $post_id ][ $lang ][ $trid ],
			'auto-translate'     => $this->shouldAutoTranslate( $trid, $post_id, $lang ),
			'trid'               => $trid,
			'language'           => $lang,
			'user-can-translate' => $this->is_lang_pair_allowed( $lang, null, $post_id ) ? 'yes' : 'no',
			'should-ate-sync'    => $this->shouldATESync( $trid, $lang ) ? '1' : '0',
		];
		if ( isset( $this->statuses[ $trid ][ $lang ]['job_id'] ) ) {
			$Attributes['tm-job-id'] = esc_attr( $this->statuses[ $trid ][ $lang ]['job_id'] );
		}

		$createAttribute = function ( $item, $key ) {
			return 'data-' . $key . '="' . $item . '"';
		};

		$data = wpml_collect( $Attributes )
			->map( $createAttribute )
			->implode( ' ' );

		return str_replace( '<a ', '<a ' . $data . ' ', $html );
	}

	private function get_link_for_new_job( $post_id, $trid, $lang, $source_lang_code ) {
		if ( $this->shouldAutoTranslate( $trid, $post_id, $lang ) ) {
			return '#';
		} else {
			$args = array(
				'trid'                 => $trid,
				'language_code'        => $lang,
				'source_language_code' => $source_lang_code,
			);

			return add_query_arg( $args, self::get_tm_editor_base_url() );
		}
	}

	public static function get_link_for_existing_job( $job_id ) {
		$args = array( 'job_id' => $job_id );

		return add_query_arg( $args, self::get_tm_editor_base_url() );
	}

	private static function get_tm_editor_base_url() {
		$returnUrl = self::get_return_url();
		$returnUrl = $returnUrl ? rawurlencode( esc_url_raw( stripslashes( $returnUrl ) ) ) : '';

		$args = array(
			'page'       => WPML_TM_FOLDER . '/menu/translations-queue.php',
			'return_url' => $returnUrl,
			'lang'       => Languages::getCurrentCode(), // We pass this param later to ATE in order to return to the page list in the same language.
		);

		return add_query_arg( $args, 'admin.php' );
	}

	private static function get_return_url() {
		$getBaseUrl = function (): string {
			$removeUnwantedArgs = function ( $url = false ) {
				$args = [ 'wpml_tm_saved', 'wpml_tm_cancel' ];

				return remove_query_arg( $args, $url );
			};

			$returnToTMDashboard = function () use ( $removeUnwantedArgs ) {
				return $removeUnwantedArgs( admin_url( UIPage::getTMDashboard() ) );
			};

			$returnToThePageWhichTriggeredAjaxIfThatPageCanBeDetermined = function () use ( $removeUnwantedArgs ) {
				if ( wpml_is_ajax() && isset( $_SERVER['HTTP_REFERER'] ) ) {
					return $removeUnwantedArgs( $_SERVER['HTTP_REFERER'] );
				}
			};

			$returnToTMDashboardIfItIsAjaxAndTriggeringPageCannotBeDetermined = function () use ( $returnToTMDashboard ) {
				if ( wpml_is_ajax() && ! isset( $_SERVER['HTTP_REFERER'] ) ) {
					return $returnToTMDashboard();
				}
			};

			$otherwiseReturnToCurrentPage = function () use ( $removeUnwantedArgs ) {
				$currentUrl = \WPML\TM\API\Jobs::getCurrentUrl();

				return $removeUnwantedArgs( $currentUrl );
			};

			$strategies = [
				$returnToThePageWhichTriggeredAjaxIfThatPageCanBeDetermined,
				$returnToTMDashboardIfItIsAjaxAndTriggeringPageCannotBeDetermined,
				$otherwiseReturnToCurrentPage,
			];

			return Logic::firstSatisfying( Logic::isTruthy(), $strategies, null );
		};

		// We add the lang parameter to the return url to return from CTE to the post list in the same language.
		return add_query_arg(
			[
				'lang'    => Languages::getCurrentCode(),
				'referer' => 'ate',
			],
			$getBaseUrl()
		);
	}

	/**
	 * @param string $lang_to
	 * @param string $lang_from
	 * @param int    $post_id
	 *
	 * @return bool
	 */
	protected function is_lang_pair_allowed( $lang_to, $lang_from = null, $post_id = 0 ) {

		return $this->tm_api->is_translator_filter(
			false,
			$this->sitepress->get_wp_api()->get_current_user_id(),
			[
				'lang_from'      => $lang_from ?: Languages::getCurrentCode(),
				'lang_to'        => $lang_to,
				'admin_override' => User::isAdministrator(),
				'post_id'        => $post_id,
			]
		);
	}

	/**
	 * It checks whether a current user has rights to edit a translation created by another user.
	 * All admins and editors can edit any translation.
	 * Other translators can edit only translations which either are assigned to them or unassigned.
	 *
	 * @param int    $trid
	 * @param string $lang
	 *
	 * @return bool
	 */
	private function has_user_rights_to_translate( $trid, $lang ) {
		$user = User::getCurrent();
		if ( User::isAdministrator( $user ) || User::isEditor( $user ) ) {
			return true;
		}

		$job = Jobs::getTridJob( $trid, $lang );
		if ( ! $job ) {
			return true;
		}

		if ( ! Obj::prop( 'translator_id', $job ) ) { // nobody is currently assigned
			return true;
		}

		if ( (int) Obj::propOr( 0, 'translator_id', $job ) === (int) $user->ID ) {
			return true;
		}

		// Neither admin, nor editor, nor the translator of $trid.
		return false;
	}

	/**
	 * @param int $trid
	 *
	 * @todo make this into a proper active record user
	 */
	private function maybe_load_stats( $trid ) {
		if ( ! $this->stats_preloaded ) {
			$this->preload_stats();
		}

		if ( ! isset( $this->statuses[ $trid ] ) ) {
			$this->statuses[ $trid ] = array();
			$this->load_stats( array( $trid ) );
		}
	}

	private function is_remote( $trid, $lang ) {

		return isset( $this->statuses[ $trid ][ $lang ]['translation_service'] )
		       && (bool) $this->statuses[ $trid ][ $lang ]['translation_service'] !== false
		       && $this->statuses[ $trid ][ $lang ]['translation_service'] !== 'local';
	}

	private function is_in_progress( $trid, $lang ) {
		return Lst::includes(
            (int) Obj::path( [ $trid, $lang, 'status' ], $this->statuses ),
            [
				ICL_TM_IN_PROGRESS,
				ICL_TM_WAITING_FOR_TRANSLATOR,
				ICL_TM_ATE_NEEDS_RETRY,
			]
        );
	}

	private function it_needs_retry( $trid, $lang ) {
		return (int) Obj::path( [ $trid, $lang, 'status' ], $this->statuses ) === ICL_TM_ATE_NEEDS_RETRY;
	}

	private function is_in_basket( $trid, $lang ) {

		return $this->status_helper
			       ->get_status( false, $trid, $lang ) === ICL_TM_IN_BASKET;
	}

	/**
	 * @param int    $trid
	 * @param int    $postId
	 * @param string $language
	 *
	 * @return bool
	 */
	private function isTranslateEverythingInProgress( $trid, $postId, $language ) {
		/** @var string $postType */
		$postType = Post::getType( $postId );
		return $postType
			   && Option::shouldTranslateEverything()
		       && $this->shouldAutoTranslate( $trid, $postId, $language )
		       && ! $this->untranslatedPosts->isPostTypeProcessedForTypeAndLanguage( $postType, $language )
		       && Lst::includes( Post::getType( $postId ), PostTypes::getAutomaticTranslatable() );
	}

	private function shouldAutoTranslate( $trid, $postId, $targetLang ) {
		$isOriginalPost = ! (bool) $this->post_translations->get_source_lang_code( $postId );
		$postLanguage   = $this->post_translations->get_element_lang_code( $postId );

		return $isOriginalPost &&
		       $postLanguage === Languages::getDefaultCode() &&
		       $this->shouldUseTMEditor( $postId ) &&
		       Automatic::shouldTranslate( get_post_type( $postId ) ) &&
		       CachedLanguageMappings::isCodeEligibleForAutomaticTranslations( $targetLang ) &&
		       $this->doesExistingJobSupportsAte( $trid, $postId, $targetLang );
	}

	/**
	 * A given post may already have an existing translation created in CTE.
	 * Depending on the WPML Settings, we may want to exclude such translation from automatic re-translation.
	 * @see wpmldev-3871
	 *
	 * @param int    $trid
	 * @param int    $postIdD
	 * @param string $targetLang
	 *
	 * @return bool
	 */
	private function doesExistingJobSupportsAte( $trid, $postId, $targetLang ): bool {
		$jobId = isset( $this->statuses[ $trid ][ $targetLang ]['job_id'] )
			? $this->statuses[ $trid ][ $targetLang ]['job_id']
			: null;

		if ( ! $jobId ) {
			return true;
		}

		return ! wpml_tm_load_old_jobs_editor()->shouldStickToWPMLEditor( $jobId, Jobs::get( $jobId ) );
	}

	/**
	 * @param int    $trid
	 * @param string $lang
	 *
	 * @return bool
	 */
	private function shouldATESync( $trid, $lang ) {
		$job = Obj::path( [ $trid, $lang ], $this->statuses );

		return Jobs::shouldBeATESynced( $job );
	}

	/**
	 * Get tooltip text for In-progress jobs based on translator ID.
	 *
	 * @param int    $trid
	 * @param string $lang
	 * @param array  $language_details
	 *
	 * @return string Tooltip text.
	 */
	private function get_in_progress_status_txt( $trid, $lang, $language_details ) {
		// If it is an automatic job.
		if ( Obj::path( [ $trid, $lang, 'automatic' ], $this->statuses ) ) {
			// Translators: %s: Language display name.
			return sprintf(
				__( 'Complete the %s translation', 'sitepress' ),
				$language_details['display_name']
			);
		}

		// If it is not an automatic job.
		$translator_id = Obj::path( [ $trid, $lang, 'translator_id' ], $this->statuses );
		$status_txt    = $translator_id
			// Translators: %s: Language display name.
			? __( '%s translation assigned to local translator', 'sitepress' )
			// Translators: %s: Language display name.
			: __( '%s translation awaiting first available translator', 'sitepress' );

		return sprintf( $status_txt, $language_details['display_name'] );
	}
}

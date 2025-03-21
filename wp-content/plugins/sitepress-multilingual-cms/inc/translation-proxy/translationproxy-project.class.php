<?php
/**
 * @package wpml-core
 * @subpackage wpml-core
 */

if ( class_exists( 'TranslationProxy_Project' ) ) {
	// Workaround for UnitTests.
	return;
}

require_once dirname( __FILE__ ) . '/translationproxy-api.class.php';
require_once dirname( __FILE__ ) . '/translationproxy-service.class.php';
require_once dirname( __FILE__ ) . '/translationproxy-batch.class.php';

/**
 * Class TranslationProxy_Project
 */
class TranslationProxy_Project {

	public $id;
	/**
	 * @var string
	 *
	 * `access_key` used when sending **any request** to TP
	 */
	public $access_key;
	/**
	 * @var int
	 *
	 * `ts_id` (aka `website_id`) is used **exclusively** when sending request directly to ICL
	 */
	public $ts_id;
	/**
	 * @var string
	 *
	 * `ts_access_key` is used **exclusively** when sending request directly to ICL
	 */
	public $ts_access_key;

	/**
	 * @var object
	 */
	public $service;

	/** @var WPML_TP_Client $tp_client */
	public $tp_client;

	public $errors = array();

	/**
	 * @param TranslationProxy_Service|stdClass $service
	 * @param string                            $delivery
	 * @param WPML_TP_Client                    $tp_client
	 */
	public function __construct( $service, $delivery, WPML_TP_Client $tp_client ) {
		$this->service   = $service;
		$this->tp_client = $tp_client;

		$icl_translation_projects = TranslationProxy::get_translation_projects();
		$project_index            = self::generate_service_index( $service );

		if ( $project_index && $icl_translation_projects && isset( $icl_translation_projects [ $project_index ] ) ) {
			$project             = $icl_translation_projects[ $project_index ];
			$this->id            = $project['id'];
			$this->access_key    = $project['access_key'];
			$this->ts_id         = $project['ts_id'];
			$this->ts_access_key = $project['ts_access_key'];

			$this->service->delivery_method = $delivery;
		}
	}

	/**
	 * @return TranslationProxy_Service
	 */
	public function service() {

		return $this->service;
	}

	/**
	 * Returns the index by which a translation service can be found in the array returned by
	 * \TranslationProxy::get_translation_projects
	 *
	 * @param $service object
	 *
	 * @return bool|string
	 */
	public static function generate_service_index( $service ) {
		$index = false;
		if ( $service ) {
			$service->custom_fields_data = isset( $service->custom_fields_data ) ? $service->custom_fields_data : array();
			if ( isset( $service->id ) ) {
				$index = md5( $service->id . serialize( $service->custom_fields_data ) );
			}
		}

		return $index;
	}

	/**
	 * Convert WPML language code to service language
	 *
	 * @param $language string
	 *
	 * @return bool|string
	 */
	private function service_language( $language ) {
		return TranslationProxy_Service::get_language( $this->service, $language );
	}

	/*
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Get information about the project (Translation Service)
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	public function custom_text( $location, $locale = 'en' ) {
		$response = '';
		if ( ! $this->ts_id || ! $this->ts_access_key ) {
			return '';
		}

		// Sending Translation Service (ts_) id and access_key, as we are talking directly to the Translation Service
		// Todo: use project->id and project->access_key once this call is moved to TP
		$params = array(
			'project_id' => $this->ts_id,
			'accesskey'  => $this->ts_access_key,
			'location'   => $location,
			'lc'         => $locale,
		);

		if ( $this->service->custom_text_url ) {
			try {
				$response = TranslationProxy_Api::service_request(
					$this->service->custom_text_url,
					$params,
					'GET',
					true,
					true,
					true
				);
			} catch ( Exception $e ) {
				throw new RuntimeException(
					'error getting custom text from Translation Service: ' . serialize( $params ) . ' url: ' . $this->service->custom_text_url,
					0,
					$e
				);
			}
		}

		return $response;
	}

	function current_service_name() {

		return TranslationProxy::get_current_service_name();
	}

	function current_service() {
		return TranslationProxy::get_current_service();
	}

	/*
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * IFrames to display project info (Translation Service)
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	public function select_translator_iframe_url( $source_language, $target_language ) {
		// Sending Translation Service (ts_) id and access_key, as we are talking directly to the Translation Service
		$params['project_id']      = $this->ts_id;
		$params['accesskey']       = $this->ts_access_key;
		$params['source_language'] = $this->service_language( $source_language );
		$params['target_language'] = $this->service_language( $target_language );
		$params['compact']         = 1;

		return $this->_create_iframe_url( $this->service->select_translator_iframe_url, $params );
	}

	public function translator_contact_iframe_url( $translator_id ) {
		// Sending Translation Service (ts_) id and access_key, as we are talking directly to the Translation Service
		$params['project_id']    = $this->ts_id;
		$params['accesskey']     = $this->ts_access_key;
		$params['translator_id'] = $translator_id;
		$params['compact']       = 1;
		if ( $this->service->translator_contact_iframe_url ) {
			return $this->_create_iframe_url( $this->service->translator_contact_iframe_url, $params );
		}

		return false;
	}

	private function _create_iframe_url( $url, $params ) {
		if ( $params ) {
			$url  = TranslationProxy_Api::add_parameters_to_url( $url, $params );
			$url .= '?' . http_build_query( $params );
		}

			return $url;
	}

	/*
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	 * Jobs handling (Translation Proxy)
	 * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */

	/**
	 * @throws WPML_TP_Batch_Exception
	 *
	 * @param string|null $source_language
	 * @param string[]|null $target_languages
	 * @param array<string,string> | null $tp_batch_info
	 *
	 * @internal param bool $name
	 * @return false|WPML_TP_Batch
	 */
	private function get_batch_job( $source_language = null, $target_languages = null, $tp_batch_info = null ) {

		$batch_data = TranslationProxy_Basket::get_batch_data();

		if ( ! $batch_data ) {
			if ( isset( $tp_batch_info ) ) {

				$prepareTpBatchExtraFields = function ( $extraFields ) {
					$preparedExtraFields = [];

					foreach ( $extraFields as $extraField ) {
						$preparedExtraFields[ $extraField[ 'fieldName' ] ] = $extraField[ 'fieldValue' ];
					}

					return $preparedExtraFields;
				};

				$deadline = false;

				if ( is_string( $tp_batch_info[ 'deadline' ] ) ) {
					$deadline = strtotime( $tp_batch_info[ 'deadline' ] );
				} elseif ( $tp_batch_info[ 'deadline' ] instanceof DateTime ) {
					$deadline = ( $tp_batch_info[ 'deadline' ] )->getTimestamp();
				}

				$basicBatchData = [
					'source_language'  => $source_language,
					'target_languages' => $target_languages,
					'name'             => $tp_batch_info[ 'batchName' ],
					'deadline'         => $deadline
				];

				$batchExtraFields = isset( $tp_batch_info[ 'extraFields' ] )
					? $prepareTpBatchExtraFields( $tp_batch_info[ 'extraFields' ] )
					: false;
			} else {
				return false;
			}

			$batch_data = $this->create_batch_job( $basicBatchData, $batchExtraFields );

			if ( $batch_data ) {
				TranslationProxy_Basket::set_batch_data( $batch_data );
			}
		}

		return $batch_data;
	}

	/**
	 * @param string|null $source_language
	 * @param string[]|null $target_languages
	 * @throws WPML_TP_Batch_Exception
	 * @param array<string,string> | null $tp_batch_info
	 *
	 * @return false|int
	 */
	function get_batch_job_id( $source_language = null, $target_languages = null, $tp_batch_info = null ) {
		$ret        = false;
		$batch_data = $this->get_batch_job( $source_language, $target_languages, $tp_batch_info );

		if ( $batch_data ) {
			$ret = $batch_data->get_id();
		}

		return $ret;
	}

	public function create_batch_job( $batchData, $extraFields ) {

		if ( ! $batchData[ 'source_language' ] ) {
			$batchData[ 'source_language' ] = TranslationProxy_Basket::get_source_language();
		}

		if ( ! $batchData[ 'target_languages' ] ) {
			$batchData[ 'target_languages' ] = TranslationProxy_Basket::get_remote_target_languages();
		}

		if ( ! $batchData[ 'source_language' ] || ! $batchData[ 'target_languages' ] ) {
			return false;
		}

		if ( ! $batchData[ 'name' ] ) {
			$batchData[ 'name' ] = sprintf(
				__(
					'%s: WPML Translation Jobs',
					'wpml-translation-management'
				),
				get_option( 'blogname' )
			);
		}

		TranslationProxy_Basket::set_basket_name( $batchData[ 'name' ] );

		return $this->tp_client->batches()->create( $batchData, $extraFields );
	}


	/**
	 *
	 * Add Files Batch Job
	 *
	 * @throws WPML_TP_Batch_Exception
	 *
	 * @param string $file
	 * @param string $title
	 * @param string $cms_id
	 * @param string $url
	 * @param string $source_language
	 * @param string $target_language
	 * @param int    $word_count
	 * @param int    $translator_id
	 * @param string $note
	 * @param array<string,string> | null $tp_batch_info
	 *
	 * @return bool|int
	 */
	public function send_to_translation_batch_mode(
		$file,
		$title,
		$cms_id,
		$url,
		$source_language,
		$target_language,
		$word_count,
		$translator_id = 0,
		$note = '',
		$uuid = null,
		$tp_batch_info = null
	) {

		/**
		 * For now, we have to keep `TranslationProxy_Basket::get_remote_target_languages()`.
		 * In wpml/wpml code, `WPML\Legacy\Component\Translation\Sender\TranslationSender::sendToTranslation`, we set
		 * those languages by calling \TranslationProxy_Basket::set_remote_target_languages( $targetLanguages );
		 *
		 * We need all the target languages to create the batch job, because they are needed for batch validation.
		 * It is impossible to pass the target languages in a better way without vast refactoring.
		 */
		$batch_id = $this->get_batch_job_id(
			$source_language,
			TranslationProxy_Basket::get_remote_target_languages() ?: null,
			$tp_batch_info
		);

		if ( ! $batch_id ) {
			return false;
		}

		$job_data = array(
			'file'            => $file,
			'word_count'      => $word_count,
			'title'           => $title,
			'cms_id'          => $cms_id,
			'udid'            => $uuid,
			'url'             => $url,
			'translator_id'   => $translator_id,
			'note'            => $note,
			'source_language' => $source_language,
			'target_language' => $target_language,
		);

		$tp_job = $this->tp_client->batches()->add_job( $batch_id, $job_data );

		return $tp_job ? $tp_job->get_id() : false;
	}

	/**
	 * @param bool|int $tp_batch_id
	 *
	 * @link http://git.icanlocalize.com/onthego/translation_proxy/wikis/commit_batch_job
	 *
	 * @return array|bool|mixed|null|stdClass|string
	 */
	function commit_batch_job( $tp_batch_id = false, $cleanBasketNameAndBatch = false ) {
		$tp_batch_id = $tp_batch_id ? $tp_batch_id : $this->get_batch_job_id();

		if ( ! $tp_batch_id ) {
			return true;
		}

		$params = array(
			'api_version' => TranslationProxy_Api::API_VERSION,
			'project_id'  => $this->id,
			'accesskey'   => $this->access_key,
			'batch_id'    => $tp_batch_id,
		);

		$response    = TranslationProxy_Api::proxy_request( '/batches/{batch_id}/commit.json', $params, 'PUT', false );
		$basket_name = TranslationProxy_Basket::get_basket_name();
		if ( $basket_name ) {
			global $wpdb;

			$batch_id_sql      = "SELECT id FROM {$wpdb->prefix}icl_translation_batches WHERE batch_name=%s";
			$batch_id_prepared = $wpdb->prepare(
				$batch_id_sql,
				array( $basket_name )
			);
			$batch_id          = $wpdb->get_var( $batch_id_prepared );

			$batch_data = array(
				'batch_name'  => $basket_name,
				'tp_id'       => $tp_batch_id,
				'last_update' => date( 'Y-m-d H:i:s' ),
			);
			if ( isset( $response ) && $response ) {
				$batch_data['ts_url'] = serialize( $response );
			}

			if ( ! $batch_id ) {
				$wpdb->insert(
					$wpdb->prefix . 'icl_translation_batches',
					$batch_data
				);
			} else {
				$wpdb->update(
					$wpdb->prefix . 'icl_translation_batches',
					$batch_data,
					array( 'id' => $batch_id )
				);
			}
		}

		if ( $cleanBasketNameAndBatch ) {
			TranslationProxy_Basket::cleanBasket();
		}

		return isset( $response ) ? $response : false;
	}

	/**
	 *
	 * @return object[]
	 */
	public function jobs() {

		return $this->get_jobs( 'any' );
	}

	/**
	 * @return object[]
	 */
	public function finished_jobs() {

		return $this->get_jobs( 'translation_ready' );
	}

	public function set_delivery_method( $method ) {
		$params = array(
			'project_id' => $this->id,
			'accesskey'  => $this->access_key,
			'project'    => array( 'delivery_method' => $method ),
		);
		TranslationProxy_Api::proxy_request( '/projects.json', $params, 'put' );

		return true;
	}

	public function fetch_translation( $job_id ) {
		$params = array(
			'project_id' => $this->id,
			'accesskey'  => $this->access_key,
			'job_id'     => $job_id,
		);

		return TranslationProxy_Api::proxy_download(
			'/jobs/{job_id}/xliff.json',
			$params
		);
	}

	public function update_job( $job_id, $url = null, $state = 'delivered' ) {
		$params = array(
			'job_id'     => $job_id,
			'project_id' => $this->id,
			'accesskey'  => $this->access_key,
			'job'        => array(
				'state' => $state,
			),
		);
		if ( $url ) {
			$params['job']['url'] = $url;
		}

		TranslationProxy_Api::proxy_request(
			'/jobs/{job_id}.json',
			$params,
			'PUT'
		);
	}

	/**
	 * @param string $state
	 *
	 * @return mixed
	 */
	private function get_jobs( $state = 'any' ) {
		$batch = TranslationProxy_Basket::get_batch_data();

		$params = array(
			'project_id' => $this->id,
			'accesskey'  => $this->access_key,
			'state'      => $state,
		);

		if ( $batch ) {
			$params['batch_id'] = $batch ? $batch->get_id() : false;

			return TranslationProxy_Api::proxy_request(
				'/batches/{batch_id}/jobs.json',
				$params
			);
		} else {
			// FIXME: remove this once TP will accept the TP Project ID: https://icanlocalize.basecamphq.com/projects/11113143-translation-proxy/todo_items/182251206/comments
			$params['project_id'] = $this->id;
		}

		return TranslationProxy_Api::proxy_request( '/jobs.json', $params );
	}
}

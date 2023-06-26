<?php

class DMG_MasonryGallery extends ET_Builder_Module {

	public $slug       = 'dmg_masonry_gallery';
	public $vb_support = 'on';
	public $artists = array(
		"Hooverphonic" => "https://open.spotify.com/artist/5EP020iZcwBqHRnJftibXX",
		"Nneka" => "https://open.spotify.com/artist/0VX4MyYhvKRtU1AZUVGLUZ",
		"Kadebostany" => "https://open.spotify.com/artist/3IVrpJxHeUFoYP4H6bxg57",
		"DeliricxSilentStrikecuCTCandMuseQuartet" => "https://open.spotify.com/artist/357du2352LkLWerYcY49WY",
		"Macanache" => "https://open.spotify.com/artist/4tr1nXsLLAQj86Hs5VyU2w",
		"SurorileOsoianuandLupiiluiCalancea" => "https://open.spotify.com/artist/24uAdKaf9xT9Y2Bp8UEN6L",
		"ImplantPentruRefuz" => "https://open.spotify.com/artist/0DAiTmP622iZnsqnzQsEk7",
		"Alexandrina" => "https://open.spotify.com/artist/48H9n5rw3haswVlzIT16Eq",
		"ElNegro" => "https://open.spotify.com/artist/5sau6yWxkMTcdE47bvW1h5",
		"AlexandraUșurelu" => "https://open.spotify.com/artist/4VR3OHgWogZJtGHIdWTJUc",
		"RobinandtheBackstabbers" => "https://open.spotify.com/artist/7y2DXdRackYULQq4qsCxpL",
		"Omlalună" => "https://open.spotify.com/artist/5J94Bk05Jkmul9Ptgpz7iz",
		"ViaDacă" => "https://open.spotify.com/artist/31uY8zYHNF2MaHc9coodbi",
		"MeltingDice" => "https://open.spotify.com/artist/2E8ii8YO178DnjVuJVQyEC",
		"GojiraandPlanetHfeatPsihotrop" => "https://open.spotify.com/artist/6BJaN208lSPd4Rpp3mHdjK",
		"Jurjak" => "https://open.spotify.com/artist/1I4LbpsQvyH3eDSnBiGRzB",
		"DJSHIVER" => "https://open.spotify.com/artist/1mrcAnxaMjzTeusSQMswFU",
		"MirceaBaniciu" => "https://open.spotify.com/artist/4zwL4BofWKSFXMsat7gZjj",
		"DucuBertzi" => "https://open.spotify.com/artist/6M0A6t14k0DICSbcYBvyqf",
		"PragudeSus" => "https://open.spotify.com/artist/6aaD2VIn1eiM600OYWYNjR",
		"FlorianChilian" => "https://open.spotify.com/artist/0OSqH37WmkURy2owuItGA3",
		"COMA" => "https://open.spotify.com/artist/1BuMfAdEX6F0duCixZBW5S",
		"MădălinaPavăl" => "https://open.spotify.com/artist/3dsSZsp0GCinE6IEyuh85W",
		"FrateGheorghe" => "https://open.spotify.com/artist/67eOBrAZ5MS1h5hcFKs7zY",
		"AnaComan" => "https://open.spotify.com/artist/4j9MwaZCANMjIyYLAC4pAW",
		"ValiBărbulescu" => "https://open.spotify.com/artist/2dopLYrj3mff8IbKUgE7h5",
		"Klu" => "https://open.spotify.com/artist/7u4qjBqB5Wwn8j8P7TYATI",
		"ALLOVER" => "https://open.spotify.com/artist/6UIhgOlZPCC7l9DQH0s2KG",
		"TheCase" => "https://open.spotify.com/artist/1khrILKEdnCqXssbT5vOWO",
		"Phaser" => "https://open.spotify.com/artist/31XVKPMoexodcassow7K6g",
		"KingsAreOverrated" => "https://open.spotify.com/artist/0sQIsmse8G8YiEoAArJ67V",
		"HipHopTM" => "https://open.spotify.com/artist/00frNE9uAiVbOezLIPt7mS",
		"EMIL" => "https://open.spotify.com/artist/531RmHBTG1gvEVglG5tKUO",
		"Domino" => "https://open.spotify.com/artist/6mQorqBOjoSMBy3GqrmGpS",
		"ToulouseLautrec" => "https://open.spotify.com/artist/0kTJt0nqhTNd5tIuVGvObt",
		"GașcaZurli" => "https://open.spotify.com/artist/6zP9kShh5KHBIYfjvuCWBS",
		"DubFXfeatMrWoodnote" => "https://open.spotify.com/artist/4ucW1LE5T7y7X4jlaKCeVo"

	);

	protected $module_credits = array(
		'module_uri' => '',
		'author'     => 'Destaca Imagen',
		'author_uri' => 'https://www.destacaimagen.com',
	);

	public function init() {
		$this->name = esc_html__( 'Masonry Gallery', 'dmg-masonry-gallery' );
		$this->icon_path = plugin_dir_path( __FILE__ ). 'icon.svg';
		$this->settings_modal_toggles = array(
			'general'  => array(
				'toggles' => array(
					'gallery_settings' => esc_html__('Gallery Settings', 'dmg-masonry-gallery'),
				)
			),
			'advanced' => array(
				'toggles' => array(
					'overlay' => esc_html__( 'Overlay', 'dmg-masonry-gallery' ),
					'image' => array(
						'title' => esc_html__( 'Image', 'dmg-masonry-gallery' ),
					),
					'filter' => array(
						'title' => esc_html__( 'Gallery Filter', 'dmg-masonry-gallery' ),
					),
					'pagination' => array(
						'title' => esc_html__( 'Gallery Pagination', 'dmg-masonry-gallery' ),
					)
				),
			)
		);
		$this->main_css_element = '%%order_class%%.dmg_masonry_gallery';
		$this->advanced_fields = array(
			'borders'               => array(
				'default' => array(
						'css' => array(
							'main' => array(
								'border_radii'  => "%%order_class%%.dmg_masonry_gallery",
								'border_styles' => "%%order_class%%.dmg_masonry_gallery",
							),
					),
				),
				'image' => array(
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dmg_masonry_gallery_item",
							'border_styles' => "{$this->main_css_element} .dmg_masonry_gallery_item",
						)
					),
					'label_prefix'    => esc_html__( 'Image', 'dmg-masonry-gallery' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'image'
				),
				'filter' => array(
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dmg-gallery-filter",
							'border_styles' => "{$this->main_css_element} .dmg-gallery-filter",
						)
					),
					'label'           => esc_html__( 'Filter Buttons', 'dmg-masonry-gallery' ),
					'label_prefix'    => esc_html__( 'Filter Buttons', 'dmg-masonry-gallery' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'filter'
				),
				'filter_active' => array(
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dmg-gallery-filter.dmg-filter-active",
							'border_styles' => "{$this->main_css_element} .dmg-gallery-filter.dmg-filter-active",
						)
					),
					'label'           => esc_html__( 'Active Filter Button', 'dmg-masonry-gallery' ),
					'label_prefix'    => esc_html__( 'Active Filter Button', 'dmg-masonry-gallery' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'filter'
				),
				'pagination' => array(
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dmg-gallery-page-button",
							'border_styles' => "{$this->main_css_element} .dmg-gallery-page-button",
						)
					),
					'label'           => esc_html__( 'Pagination Buttons', 'dmg-masonry-gallery' ),
					'label_prefix'    => esc_html__( 'Pagination Buttons', 'dmg-masonry-gallery' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'pagination'
				),
				'pagination_active' => array(
					'css' => array(
						'main' => array(
							'border_radii'  => "{$this->main_css_element} .dmg-gallery-page-button.dmg-gallery-page-button-active",
							'border_styles' => "{$this->main_css_element} .dmg-gallery-page-button.dmg-gallery-page-button-active",
						)
					),
					'label'           => esc_html__( 'Active Pagination Button', 'dmg-masonry-gallery' ),
					'label_prefix'    => esc_html__( 'Active Pagination Button', 'dmg-masonry-gallery' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'pagination'
				),
			),
			'box_shadow' => array(
				'default' => array(
				),
				'filter'   => array(
					'label'           => esc_html__( 'Filter buttons box shadow', 'dmg-masonry-gallery' ),
					'label_prefix'    => esc_html__( 'Image', 'dmg-masonry-gallery' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'filter',
					'css' => array(
						'main'         => '%%order_class%% .dmg-gallery-filter',
						'overlay' => 'inset',
					),
					'default_on_fronts'  => array(
						'color'    => '',
						'position' => '',
					),
				),
				'pagination'   => array(
					'label'           => esc_html__( 'Pagination buttons box shadow', 'dmg-masonry-gallery' ),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'pagination',
					'css' => array(
						'main'         => '%%order_class%% .dmg-gallery-page-button',
						'overlay' => 'inset',
					),
					'default_on_fronts'  => array(
						'color'    => '',
						'position' => '',
					),
				)
			),
			'margin_padding' => array(
				'css' => array(
					'main' => "{$this->main_css_element} .dmg-gallery",
				),
			),
			'text' => false,
			'link_options' => false,
			'fonts' => array(
				'title' => array(
					'css' => array(
						'main' => "{$this->main_css_element} .dmg-img-overlay"
					),
					'toggle_slug' => 'overlay',
					'depends_show_if' => array(
						'hover_overlay_effect' => 'overlay_image_data',
					),
					'hide_font_size' => true,
					'hide_line_height' => true,
					'hide_text_color' => true
				),
				'filter' => array(
					'css' => array(
						'main' => "{$this->main_css_element} li.dmg-gallery-filter"
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'filter',
					'hide_font_size' => false,
					'hide_line_height' => false,
					'hide_text_color' => false,
					'label'           => esc_html__( 'Filter Buttons', 'dmg-masonry-gallery' )
				),
				'filter_active' => array(
					'css' => array(
						'main' => "{$this->main_css_element} li.dmg-gallery-filter.dmg-filter-active"
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'filter',
					'hide_font_size' => false,
					'hide_line_height' => false,
					'hide_text_color' => false,
					'label'           => esc_html__( 'Active Filter Button', 'dmg-masonry-gallery' )
				),
				'pagination' => array(
					'css' => array(
						'main' => "{$this->main_css_element} li.dmg-gallery-page-button"
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'pagination',
					'hide_font_size' => false,
					'hide_line_height' => false,
					'hide_text_color' => false,
					'label'           => esc_html__( 'Pagination Buttons', 'dmg-masonry-gallery' )
				),
				'pagination_active' => array(
					'css' => array(
						'main' => "{$this->main_css_element} li.dmg-gallery-page-button.dmg-gallery-page-button-active"
					),
					'tab_slug'        => 'advanced',
					'toggle_slug'     => 'pagination',
					'hide_font_size' => false,
					'hide_line_height' => false,
					'hide_text_color' => false,
					'label'           => esc_html__( 'Active Pagination Button', 'dmg-masonry-gallery' )
				)
			),
			'max_width' => array(
				'use_module_alignment' => false
			)
		);
	}
	public function get_fields() {
		$fields = array(
			'gallery' => array(
				'label' => esc_html__( 'Images', 'dmg-masonry-gallery' ),
				'type' => 'upload_gallery',
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option'
			),
			'columns' => array(
				'label' => esc_html__( 'Number of columns', 'dmg-masonry-gallery' ),
				'type' => 'range',
				'default' => 3,
				'validate_unit' => false,
				'range_settings' => array(
					'min' => 1,
					'max' => 10,
					'step' => 1
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'mobile_options' => true
			),
			'gutter' => array(
				'label' => esc_html__( 'Space between images', 'dmg-masonry-gallery' ),
				'type' => 'range',
				'default' => 10,
				'validate_unit' => false,
				'range_settings' => array(
					'min' => 0,
					'max' => 100,
					'step' => 1
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'mobile_options' => true
			),
			'images_size' => array(
				'label' => esc_html__( 'Images size', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'default' => 'on',
				'options' => array(
					'dmg_image_small' => esc_html__( 'Small', 'dmg-masonry-gallery' ),
					'dmg_image_medium' => esc_html__( 'Medium', 'dmg-masonry-gallery' ),
					'dmg_image_large' => esc_html__( 'Large', 'dmg-masonry-gallery' ),
					'dmg_image_extra_large' => esc_html__( 'Extra Large', 'dmg-masonry-gallery' )
				),
				'default' => 'dmg_image_medium',
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option'
			),
			'modal_gallery' => array(
				'label' => esc_html__( 'Image onclick action.', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'default' => 'off',
				'options' => array(
					'on'  => esc_html__( 'Open lightbox gallery', 'dmg-masonry-gallery' ),
					'links' => esc_html__( 'Link to other pages', 'dmg-masonry-gallery' ),
					'off' => esc_html__( 'None', 'dmg-masonry-gallery' )
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option'
			),
			'modal_background_color' => array(
				'label' => esc_html__( 'Popup image background color', 'dmg-masonry-gallery' ),
				'type' => 'color-alpha',
				'default' => 'rgba(11,11,11,0.8)',
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'show_if' => array(
					'modal_gallery' => 'on',
				),
			),
			'gallery_links' => array(
				'label' => esc_html__( 'Image links', 'dmg-masonry-gallery' ),
				'description' => esc_html__( 'Enter the links of the images in the same order separated by the separator {{link}}. Do not use line-breaks', 'dmg-masonry-gallery' ),
				"type" => "textarea",
				'default' => '',
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'show_if' => array(
					'modal_gallery' => 'links',
				)
			),
			'gallery_links_target' => array(
				'type' => 'select',
				'label'       => esc_html__( 'Images link target', 'dmg-masonry-gallery' ),
				'description' => esc_html__( 'Here you can choose whether or not your link opens in a new window', 'et_builder' ),
				'options'     => array(
					'off' => esc_html__( 'In The Same Window', 'et_builder' ),
					'on'  => esc_html__( 'In The New Tab', 'et_builder' ),
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'show_if' => array(
					'modal_gallery' => 'links',
				)
			),
			'modal_title' => array(
				'label' => esc_html__( 'Show image title/caption in popup?', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'default' => 'off',
				'options' => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Show Image title outside image', 'dmg-masonry-gallery' ),
					'title_caption'  => esc_html__( 'Show Image title + caption outside image', 'dmg-masonry-gallery' ),
					'in_title'  => esc_html__( 'Show Image title inside image', 'dmg-masonry-gallery' ),
					'in_title_caption'  => esc_html__( 'Show Image title + caption inside image', 'dmg-masonry-gallery' )
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'show_if' => array(
					'modal_gallery' => 'on',
				),
			),
			'modal_title_color' => array(
				'label' => esc_html__( 'Image popup legend color', 'dmg-masonry-gallery' ),
				'type' => 'color-alpha',
				'default' => '#f3f3f3',
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'show_if' => array(
					'modal_gallery' => 'on',
					'modal_title' => 'on',
				),
			),
			'hover_overlay_effect' => array(
				'label' => esc_html__( 'Overlay content', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'options' => array(
					'overlay_icon' => esc_html__( 'Overlay icon', 'dmg-masonry-gallery' ),
					'overlay_image_data' => esc_html__( 'Overlay image title/caption', 'dmg-masonry-gallery' ),
					'nothing' => esc_html__( 'Nothing', 'dmg-masonry-gallery' )
				),
				'tab_slug'          => 'advanced',
				'default' => 'overlay_icon',
				'toggle_slug' => 'overlay'
			),
			'zoom_icon_color' => array(
				'label'             => esc_html__( 'Overlay Icon Color', 'et_builder' ),
				'description'       => esc_html__( 'Here you can define a custom color for the zoom icon.', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'depends_show_if'   => 'off',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'overlay',
				'mobile_options'    => true,
				'default'			=> 'transparent',
				'show_if' => array(
					'hover_overlay_effect' => 'overlay_icon',
				),
			),
			'hover_overlay_color' => array(
				'label'             => esc_html__( 'Overlay Background Color', 'et_builder' ),
				'description'       => esc_html__( 'Here you can define a custom color for the overlay', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'depends_show_if'   => 'off',
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'overlay',
				'mobile_options'    => true,
				'default'			=> 'transparent',
				'show_if' => array(
					'hover_overlay_effect' => array('overlay_icon', 'overlay_image_data'),
				),
			),
			'hover_icon' => array(
				'label'               => esc_html__( 'Overlay Icon', 'et_builder' ),
				'description'         => esc_html__( 'Here you can define a custom icon for the overlay', 'et_builder' ),
				'type'                => 'select_icon',
				'option_category'     => 'configuration',
				'class'               => array( 'et-pb-font-icon' ),
				'tab_slug'            => 'advanced',
				'toggle_slug'         => 'overlay',
				'mobile_options'      => true,
				'show_if' => array(
					'hover_overlay_effect' => 'overlay_icon',
				),
			),
			'show_overlay_title' => array(
				'label' => esc_html__( 'Show image overlay title?', 'dmg-masonry-gallery' ),
				'type' => 'yes_no_button',
				'default' => 'on',
				'options' => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' )
				),
				'tab_slug'	=> 'advanced',
				'toggle_slug' => 'overlay',
				'option_category' => 'configuration',
				'show_if' => array(
					'hover_overlay_effect' => 'overlay_image_data',
				),
			),
			'overlay_title_size' => array(
				'label' => esc_html__( 'Image title size', 'dmg-masonry-gallery' ),
				'type' => 'range',
				'default' => '12px',
				'validate_unit' => true,
				'range_settings' => array(
					'min' => 0,
					'max' => 100,
					'step' => 1
				),
				'tab_slug'	=> 'advanced',
				'toggle_slug' => 'overlay',
				'option_category' => 'configuration',
				'show_if' => array(
					'hover_overlay_effect' => 'overlay_image_data',
					'show_overlay_title' => 'on',
				),
				'mobile_options' => true
			),
			'overlay_title_color' => array(
				'label'             => esc_html__( 'Image title color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'overlay',
				'mobile_options'    => true,
				'default'			=> '#ffffff',
				'show_if' => array(
					'hover_overlay_effect' => 'overlay_image_data',
					'show_overlay_title' => 'on',
				),
			),
			'show_overlay_caption' => array(
				'label' => esc_html__( 'Show image overlay caption?', 'dmg-masonry-gallery' ),
				'type' => 'yes_no_button',
				'default' => 'on',
				'options' => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'et_builder' )
				),
				'tab_slug'	=> 'advanced',
				'toggle_slug' => 'overlay',
				'option_category' => 'configuration',
				'show_if' => array(
					'hover_overlay_effect' => 'overlay_image_data',
				),
			),
			'overlay_caption_size' => array(
				'label' => esc_html__( 'Image caption size', 'dmg-masonry-gallery' ),
				'type' => 'range',
				'default' => '10px',
				'validate_unit' => true,
				'range_settings' => array(
					'min' => 0,
					'max' => 100,
					'step' => 1
				),
				'tab_slug'	=> 'advanced',
				'toggle_slug' => 'overlay',
				'option_category' => 'configuration',
				'show_if' => array(
					'hover_overlay_effect' => 'overlay_image_data',
					'show_overlay_title' => 'on',
				),
				'mobile_options' => true
			),
			'overlay_caption_color' => array(
				'label'             => esc_html__( 'Image caption color', 'et_builder' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'          => 'advanced',
				'toggle_slug'       => 'overlay',
				'mobile_options'    => true,
				'default'			=> '#ffffff',
				'show_if' => array(
					'hover_overlay_effect' => array('overlay_icon', 'overlay_image_data'),
					'show_overlay_caption' => 'on',
				),
			),
			'overlay_content_animation' => array(
				'label' => esc_html__( 'Overlay content animation', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'options' => array(
					'dmg-fade-in' => esc_html__( 'Fade In', 'dmg-masonry-gallery' ),
					'dmg-right-enter' => esc_html__( 'Right to Left', 'dmg-masonry-gallery' ),
					'dmg-left-enter' => esc_html__( 'Left to Right', 'dmg-masonry-gallery' ),
					'dmg-top-enter' => esc_html__( 'Top to bottom', 'dmg-masonry-gallery' ),
					'dmg-bottom-enter' => esc_html__( 'Bottom to top', 'dmg-masonry-gallery' ),
					'dmg-visible-top' => esc_html__( 'Visible always at top', 'dmg-masonry-gallery' ),
					'dmg-visible-bottom' => esc_html__( 'Visble always at bottom', 'dmg-masonry-gallery' ),
					'none' => esc_html__( 'None', 'dmg-masonry-gallery' )
				),
				'tab_slug' => 'advanced',
				'default' => 'dmg-fade-in',
				'toggle_slug' => 'overlay',
				'show_if' => array(
					'hover_overlay_effect' => 'overlay_image_data',
				),
			),
			'__gallery_data' => array(
				'type' => 'computed',
				'computed_callback' => array( 'DMG_MasonryGallery', 'get_gallery' ),
				'computed_depends_on' => array(
					'gallery',
					'show_overlay_caption',
					'show_overlay_title',
					'gallery_categories'
				),
			),
			'display_gallery_categories' => array(
				'label' => esc_html__( 'Show categories filter?', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'default' => 'off',
				'options' => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'dmg-masonry-gallery' ),
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
			),
			'gallery_categories' => array(
				'label' => esc_html__( 'Included categories', 'dmg-masonry-gallery' ),
				'type' => 'categories',
				'renderer_options' => array(
					'use_terms' => true,
					'term_name' => 'media-categories'
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'show_if' => array(
					'display_gallery_categories' => 'on',
				),
			),
			'gallery_categories_all_text' => array(
				'label' => esc_html__( 'All categories button text', 'dmg-masonry-gallery' ),
				'type' => 'text',
				'default' => 'All categories',
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'show_if' => array(
					'display_gallery_categories' => 'on',
				),
			),
			'gallery_buttons_alignment' => array(
				'priority' => 20,
				'label'             => esc_html__( 'Filter alignment', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'options' => array(
					'left' => esc_html__( 'Left', 'dmg-masonry-gallery' ),
					'center' => esc_html__( 'Center', 'dmg-masonry-gallery' ),
					'right' => esc_html__( 'Right', 'dmg-masonry-gallery' )
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'filter',
				'mobile_options'    => false,
				'default'			=> 'center'
			),
			'gallery_buttons_background' => array(
				'label'             => esc_html__( 'Filter buttons background', 'dmg-masonry-gallery' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'filter',
				'mobile_options'    => false,
				'default'			=> '#000',
				'priority'			=> 10,
				'hover'				=> 'tabs',
			),
			'gallery_buttons_background_active' => array(
				'label'             => esc_html__( 'Active filter button background', 'dmg-masonry-gallery' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'filter',
				'mobile_options'    => false,
				'default'			=> '#000',
				'priority'			=> 30,
				'hover'				=> 'tabs',
			),
			'paginate_gallery' => array(
				'label' => esc_html__( 'Paginate gallery?', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'default' => 'off',
				'options' => array(
					'off' => esc_html__( 'No', 'et_builder' ),
					'on'  => esc_html__( 'Yes', 'dmg-masonry-gallery' ),
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
			),
			'page_size' => array(
				'label' => esc_html__( 'Page size', 'dmg-masonry-gallery' ),
				'type' => 'range',
				'default' => 10,
				'validate_unit' => false,
				'range_settings' => array(
					'min' => 1,
					'max' => 20,
					'step' => 1
				),
				'toggle_slug' => 'gallery_settings',
				'option_category' => 'basic_option',
				'mobile_options' => false,
				'show_if' => array(
					'paginate_gallery' => 'on',
				),
			),
			'pagination_buttons_alignment' => array(
				'label'             => esc_html__( 'Pagination alignment', 'dmg-masonry-gallery' ),
				'type' => 'select',
				'options' => array(
					'left' => esc_html__( 'Left', 'dmg-masonry-gallery' ),
					'center' => esc_html__( 'Center', 'dmg-masonry-gallery' ),
					'right' => esc_html__( 'Right', 'dmg-masonry-gallery' )
				),
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'pagination',
				'mobile_options'    => false,
				'default'			=> 'center'
			),
			'pagination_buttons_background' => array(
				'label'             => esc_html__( 'Pagination buttons background', 'dmg-masonry-gallery' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'pagination',
				'mobile_options'    => false,
				'default'			=> '#000',
				'hover'				=> 'tabs',
			),
			'pagination_buttons_background_active' => array(
				'label'             => esc_html__( 'Active page button background', 'dmg-masonry-gallery' ),
				'type'              => 'color-alpha',
				'custom_color'      => true,
				'tab_slug'        => 'advanced',
				'toggle_slug'     => 'pagination',
				'mobile_options'    => false,
				'default'			=> '#fff',
				'hover'				=> 'tabs',
			),
		);

		$filter_margin = DMG_MasonryGallery::add_margin_padding_field(
			'filter_margin',
			'Filter buttons margin',
			'filter'
		);

		$filter_padding = DMG_MasonryGallery::add_margin_padding_field(
			'filter_padding',
			'Filter buttons padding',
			'filter'
		);

		$pagination_margin = DMG_MasonryGallery::add_margin_padding_field(
			'pagination_margin',
			'Pagination buttons margin',
			'pagination'
		);

		$pagination_padding = DMG_MasonryGallery::add_margin_padding_field(
			'pagination_padding',
			'Pagination buttons padding',
			'pagination'
		);

		return array_merge(
			$fields,
			$filter_margin,
			$filter_padding,
			$pagination_margin,
			$pagination_padding
		);
	}

	/**
	 * Get attachment data for gallery module
	 *
	 * @param array $args {
	 *     Gallery Options
	 *
	 *     @type array  $gallery     Attachment Ids of images to be included in gallery.
	 *     @type string $gallery_orderby `orderby` arg for query. Optional.
	 *     @type string $fullwidth       on|off to determine grid / slider layout
	 *     @type string $orientation     Orientation of thumbnails (landscape|portrait).
	 * }
	 * @param array $conditional_tags
	 * @param array $current_page
	 *
	 * @return array Attachments data
	 */
	static function get_gallery( $args = array(), $conditional_tags = array(), $current_page = array() ) {

		$attachments = array();

		$defaults = array(
			'gallery'      => array(),
			'gallery_orderby'  => '',
			'gallery_captions' => array(),
			'fullwidth'        => 'off',
			'orientation'      => 'landscape',
		);

		$args = wp_parse_args( $args, $defaults );

		$attachments_args = array(
			'include'        => $args['gallery'],
			'post_status'    => 'inherit',
			'post_type'      => 'attachment',
			'post_mime_type' => 'image',
			'order'          => 'ASC',
			'orderby'        => 'post__in',
		);

		// Woo Gallery module shouldn't display placeholder image when no Gallery image is
		// available.
		// @see https://github.com/elegantthemes/submodule-builder/pull/6706#issuecomment-542275647
		if ( isset( $args['attachment_id'] ) ) {
			$attachments_args['attachment_id'] = $args['attachment_id'];
		}

		if ( 'rand' === $args['gallery_orderby'] ) {
			$attachments_args['orderby'] = 'rand';
		}

		if ( 'on' === $args['fullwidth'] ) {
			$width  = 1080;
			$height = 9999;
		} else {
			$width  =  400;
			$height = ( 'landscape' === $args['orientation'] ) ? 284 : 516;
		}

		$width  = (int) apply_filters( 'et_pb_gallery_image_width', $width );
		$height = (int) apply_filters( 'et_pb_gallery_image_height', $height );

		$_attachments = get_posts( $attachments_args );

		foreach ( $_attachments as $key => $val ) {
			$attachments[$key] = $_attachments[$key];
			$attachments[$key]->image_alt_text  = get_post_meta( $val->ID, '_wp_attachment_image_alt', true);
			$attachments[$key]->image_src_full  = wp_get_attachment_image_src( $val->ID, 'full' );
			$attachments[$key]->image_src_thumb = wp_get_attachment_image_src( $val->ID, array( $width, $height ) );
			$categories_list = get_the_terms( $val->ID, 'media-categories' );
			if ($categories_list) {
				$attachments[$key]->media_categories= join(',', wp_list_pluck($categories_list, 'term_id'));
			} else {
				$attachments[$key]->media_categories= '';
			}
		}

		return $attachments;
	}

	public function get_advanced_fields_config() {
		$advance_fields = $this->advanced_fields;
		$advance_fields['link_options'] = false;
		$advance_fields['text'] = false;
		$advance_fields['max_width'] = [
			'use_module_alignment' => false
		];

		return $advance_fields;
	}

	private function get_image_link($index, $links) {
		$links = preg_replace('/<br\s*\/?>/i', '', $links);
		$linksArr = explode('{{link}}', $links);
		for ($i = 0; $i < count($linksArr); $i++) {
			if ($i == $index && trim($linksArr[$i]) != '') {
				return trim($linksArr[$i]);
			}
		}

		return '#';
	}

	public static function add_margin_padding_field($key, $description, $toggle_slug, $sub_toggle = '', $priority = 30 ) {
		$margin_padding = array();
		$margin_padding[$key] = array(
			'label'				=> sprintf(esc_html__('%1$s', 'dmg-masonry-gallery'), $description),
			'type'				=> 'custom_margin',
			'toggle_slug'       => $toggle_slug,
			'tab_slug'			=> 'advanced',
			'mobile_options'    => true,
			'hover'				=> false,
			'priority' 			=> $priority,
		);
		$margin_padding[$key . '_tablet'] = array(
			'type'            	=> 'skip',
			'tab_slug'        	=> 'advanced',
			'toggle_slug'		=> $toggle_slug,
		);
		$margin_padding[$key.'_phone'] = array(
			'type'            	=> 'skip',
			'tab_slug'        	=> 'advanced',
			'toggle_slug'		=> $toggle_slug,
		);
		$margin_padding[$key.'_last_edited'] = array(
			'type'            	=> 'skip',
			'tab_slug'        	=> 'advanced',
			'toggle_slug'		=> $toggle_slug,
		);

		return $margin_padding;
	}

	/**
	 * Apply Custom Margin Padding
	 */
	public static function apply_margin_padding($props, $render_slug, $slug, $type, $class, $important = true) {
		$desktop 				= $props[$slug];
		$tablet 				= $props[$slug.'_tablet'];
		$phone 					= $props[$slug.'_phone'];

		if(isset($desktop) && !empty($desktop)) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => $class,
				'declaration' => et_builder_get_element_style_css($desktop, $type, $important),
			));
		}
		if (isset($tablet) && !empty($tablet)) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => $class,
				'declaration' => et_builder_get_element_style_css($tablet, $type, $important),
				'media_query' => ET_Builder_Element::get_media_query('max_width_980'),
			));
		}
		if (isset($phone) && !empty($phone)) {
			ET_Builder_Element::set_style($render_slug, array(
				'selector' => $class,
				'declaration' => et_builder_get_element_style_css($phone, $type, $important),
				'media_query' => ET_Builder_Element::get_media_query('max_width_767'),
			));
		}
	}

	private function render_pagination( $total_images ) {
		if ( $this -> props['paginate_gallery'] == 'on' ) {

      		$pages = ceil( $total_images / $this -> props['page_size'] );
      		if ( $pages <= 1 )
      			return '';
			
			$paginator = '';
			for ( $i = 1; $i <= $pages; $i++ ) {
				if ($i == 1)
					$paginator .= sprintf( '<li data-value="%1$d" class="dmg-gallery-page-button dmg-gallery-page-button-active">%1$d</li>', $i );
				else	
					$paginator .= sprintf( '<li data-value="%1$d" class="dmg-gallery-page-button">%1$d</li>', $i );
			}

			return sprintf( '<div class="dmg-gallery-pagination-wrapper">
          						<ul data-value="1" data-page-size="%2$d" class="dmg-gallery-pagination">
						            %1$s
          						</ul>
        					</div>', $paginator, $this -> props['page_size'] );
		} else {
			return '';
		}
	}

	private function render_filter() {
		
		if ( $this -> props['display_gallery_categories'] == 'on' && $this -> props['gallery_categories'] ) {
			$filter = sprintf( '<li data-value="" class="dmg-gallery-filter dmg-filter-active">%1$s</li>', $this -> props['gallery_categories_all_text'] );

			$terms = get_terms( array( 
				'taxonomy' => 'media-categories',
				'include' => $this -> props['gallery_categories'],
    			'hide_empty' => false,
			));

			foreach ( $terms as $term ) {
				$filter .= sprintf( '<li data-value="%2$d" class="dmg-gallery-filter">%1$s</li>', $term -> name, $term -> term_id );
			}

			return sprintf( '<div class="dmg-gallery-filter-container">
          						<ul class="dmg-gallery-filter-categories">
						            %1$s
          						</ul>
        					</div>', $filter );
		} else {
			return '';
		}
	}

	public function render( $attrs, $content = null, $render_slug ) {
		$images_size = $this -> props['images_size'];
		$images_ids = explode( ',', $this -> props['gallery'] );
		$columns = $this -> props['columns'];
		$gutter = $this -> props['gutter'];
		$modal_gallery = $this -> props['modal_gallery'] === 'on';
		$gallery_links = $this -> props['modal_gallery'] === 'links';
		$modal_gallery_title = $modal_gallery && $this -> props['modal_title'] !== 'off';
		$modal_gallery_title_value = $this -> props['modal_title'];
		$modal_gallery_title_color = $this -> props['modal_title_color'];
		$modal_gallery_background_color = $this -> props['modal_background_color'];
		$modal_gallery_class = $modal_gallery ? ('dmg-modal-gallery ' . ( $modal_gallery_title ? 'dmg-modal-gallery-with-title ' . 'dmg-modal-title__' . $modal_gallery_title_value  : '')) : '';

		$hover_overlay_effect 			 = $this -> props['hover_overlay_effect'];
		$zoom_icon_color_values          = et_pb_responsive_options()->get_property_values( $this->props, 'zoom_icon_color' );
		$hover_overlay_color_values      = et_pb_responsive_options()->get_property_values( $this->props, 'hover_overlay_color' );
		$hover_title_size          		 = et_pb_responsive_options()->get_property_values( $this->props, 'overlay_title_size' );
		$hover_title_color          	 = et_pb_responsive_options()->get_property_values( $this->props, 'overlay_title_color' );
		$hover_caption_size          	 = et_pb_responsive_options()->get_property_values( $this->props, 'overlay_caption_size' );
		$hover_caption_color          	 = et_pb_responsive_options()->get_property_values( $this->props, 'overlay_caption_color' );

		$hover_icon                      = $this->props['hover_icon'];
		$hover_icon_values               = et_pb_responsive_options()->get_property_values( $this->props, 'hover_icon' );
		$hover_icon_tablet               = isset( $hover_icon_values['tablet'] ) ? $hover_icon_values['tablet'] : '';
		$hover_icon_phone                = isset( $hover_icon_values['phone'] ) ? $hover_icon_values['phone'] : '';
		$filter 						 = $this -> render_filter();

		// Get gallery item data
		$attachments = self::get_gallery( array(
			'gallery'     => $images_ids
		) );

		if ( $modal_gallery && $modal_gallery_title ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => ".mfp-title",
				'declaration' => sprintf( 'color: %1$s;', $modal_gallery_title_color )
			));
		}

		if ( $modal_gallery ) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => ".mfp-bg",
				'declaration' => 'opacity: 1;'
			));
			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => ".mfp-bg",
				'declaration' => sprintf( 'background: %1$s;', $modal_gallery_background_color )
			));
		}

		DMG_MasonryGallery::apply_margin_padding(
			$this -> props,
			$render_slug, 
			'filter_margin', 
			'margin', 
			'%%order_class%% li.dmg-gallery-filter',
			false
		);

		DMG_MasonryGallery::apply_margin_padding(
			$this -> props,
			$render_slug, 
			'filter_padding', 
			'padding', 
			'%%order_class%% li.dmg-gallery-filter',
			false
		);	

		DMG_MasonryGallery::apply_margin_padding(
			$this -> props,
			$render_slug, 
			'pagination_margin', 
			'margin', 
			'%%order_class%% li.dmg-gallery-page-button',
			false
		);

		DMG_MasonryGallery::apply_margin_padding(
			$this -> props,
			$render_slug, 
			'pagination_padding', 
			'padding', 
			'%%order_class%% li.dmg-gallery-page-button',
			false
		);	

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% ul.dmg-gallery-filter-categories",
			'declaration' => sprintf( 'text-align: %1$s;', $this -> props['gallery_buttons_alignment'] )
		));

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% li.dmg-gallery-filter",
			'declaration' => sprintf( 'background-color: %1$s;', $this -> props['gallery_buttons_background'] )
		));

		if (isset($this -> props['gallery_buttons_background__hover']) && $this -> props['gallery_buttons_background__hover'] && strpos($this -> props['gallery_buttons_background__hover_enabled'], 'on') === 0) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => "%%order_class%% li.dmg-gallery-filter:hover",
				'declaration' => sprintf( 'background-color: %1$s;', $this -> props['gallery_buttons_background__hover'] )
			));			
		}

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% li.dmg-gallery-filter.dmg-filter-active",
			'declaration' => sprintf( 'background-color: %1$s;', $this -> props['gallery_buttons_background_active'] )
		));	


		if (isset($this -> props['gallery_buttons_background_active__hover']) && $this -> props['gallery_buttons_background_active__hover'] && strpos($this -> props['gallery_buttons_background_active__hover_enabled'], 'on') === 0) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => "%%order_class%% li.dmg-gallery-filter.dmg-filter-active:hover",
				'declaration' => sprintf( 'background-color: %1$s;', $this -> props['gallery_buttons_background_active__hover'] )
			));			
		}

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% ul.dmg-gallery-pagination",
			'declaration' => sprintf( 'text-align: %1$s;', $this -> props['pagination_buttons_alignment'] )
		));

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% li.dmg-gallery-page-button",
			'declaration' => sprintf( 'background-color: %1$s;', $this -> props['pagination_buttons_background'] )
		));

		if (isset($this -> props['pagination_buttons_background__hover']) && $this -> props['pagination_buttons_background__hover'] && strpos($this -> props['pagination_buttons_background__hover_enabled'], 'on') === 0) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => "%%order_class%% li.dmg-gallery-page-button:hover",
				'declaration' => sprintf( 'background-color: %1$s;', $this -> props['pagination_buttons_background__hover'] )
			));			
		}

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% li.dmg-gallery-page-button.dmg-gallery-page-button-active",
			'declaration' => sprintf( 'background-color: %1$s;', $this -> props['pagination_buttons_background_active'] )
		));

		if (isset($this -> props['pagination_buttons_background_active__hover']) && $this -> props['pagination_buttons_background_active__hover'] && strpos($this -> props['pagination_buttons_background_active__hover_enabled'], 'on') === 0) {
			ET_Builder_Element::set_style( $render_slug, array(
				'selector' => "%%order_class%% li.dmg-gallery-page-button.dmg-gallery-page-button-active:hover",
				'declaration' => sprintf( 'background-color: %1$s;', $this -> props['pagination_buttons_background_active__hover'] )
			));			
		}


		// Zoom Icon Color.
		et_pb_responsive_options()->generate_responsive_css( $zoom_icon_color_values, '%%order_class%% .et_overlay:before', 'color', $render_slug, ' !important;', 'color' );
		// Hover Overlay Color.
		et_pb_responsive_options()->generate_responsive_css( $hover_overlay_color_values, '%%order_class%% .et_overlay', 'background-color', $render_slug, '', 'color' );
		et_pb_responsive_options()->generate_responsive_css( $hover_overlay_color_values, '%%order_class%% .dmg_masonry_gallery_item .dmg-img-overlay', 'background-color', $render_slug, '', 'color' );
		et_pb_responsive_options()->generate_responsive_css( $hover_overlay_color_values, '%%order_class%% .et_overlay', 'border-color', $render_slug, '', 'color' );

		if ( $this -> props['show_overlay_title'] === 'on') {
			et_pb_responsive_options()->generate_responsive_css( $hover_title_size, '%%order_class%% .dmg_masonry_gallery_item .dmg-img-overlay-title', 'font-size', $render_slug);
			et_pb_responsive_options()->generate_responsive_css( $hover_title_color, '%%order_class%% .dmg_masonry_gallery_item .dmg-img-overlay-title', 'color', $render_slug, '', 'color' );
		}

		if ( $this -> props['show_overlay_caption'] === 'on') {
			et_pb_responsive_options()->generate_responsive_css( $hover_caption_size, '%%order_class%% .dmg_masonry_gallery_item .dmg-img-overlay-caption', 'font-size', $render_slug);
			et_pb_responsive_options()->generate_responsive_css( $hover_caption_color, '%%order_class%% .dmg_masonry_gallery_item .dmg-img-overlay-caption', 'color', $render_slug, '', 'color' );
		}

		$images = '';
		$image_index = 0;
		foreach ( $images_ids as $image_id ) {
			$title = '';
            $caption = '';
            $categories = '';
            $image_alt_text = '';

            foreach ( $attachments as $id => $attachment ) {

              	if ($image_id == $attachment->ID) {
                	$title = $attachment->post_title;
                	$caption = $attachment->post_excerpt;
                	$categories = $attachment->media_categories;
                	$image_alt_text = $attachment->image_alt_text;
                	break;
                }
            }
			$data_icon = '' !== $hover_icon
				? sprintf(
					' data-icon="%1$s"',
					esc_attr( et_pb_process_font_icon( $hover_icon ) )
				)
				: '';

			$data_icon_tablet = '' !== $hover_icon_tablet
				? sprintf(
					' data-icon-tablet="%1$s"',
					esc_attr( et_pb_process_font_icon( $hover_icon_tablet ) )
				)
				: '';

			$data_icon_phone = '' !== $hover_icon_phone
				? sprintf(
					' data-icon-phone="%1$s"',
					esc_attr( et_pb_process_font_icon( $hover_icon_phone ) )
				)
				: '';
			$image = '';
			$image_title_html = '';
			$image_sizes = '';
			$image_srcset = '';
			$image_width = '';
			$image_height = '';
			if ( $modal_gallery_title ) {
				if ( $modal_gallery_title_value == 'title_caption' || $modal_gallery_title_value == 'in_title_caption' ) {
					$image_title_html = sprintf(' data-title="%1$s" data-caption="%2$s"', $title, $caption );
				} else {
					$image_title_html = sprintf(' data-title="%1$s" ', esc_html__( $title ));
				}
			}
			$image_data = wp_get_attachment_image_src( $image_id , $images_size );
			if ( $image_data ) {
				$image = $image_data[0];
				$image_width = $image_data[1];
				$image_height = $image_data[2];
				$srcset_sizes = et_get_image_srcset_sizes( $image );
				
				if ( isset( $srcset_sizes['srcset'], $srcset_sizes['sizes'] ) && $srcset_sizes['srcset'] && $srcset_sizes['sizes'] ) {
					$image_srcset = $srcset_sizes['srcset'];
					$image_sizes = $srcset_sizes['sizes'];
				}
			}

			$overlay_content = '';
			if ( $hover_overlay_effect == 'overlay_icon') {
				$overlay_content = sprintf(
					'<span class="et_overlay%1$s%3$s%5$s"%2$s%4$s%6$s></span>',
					( '' !== $hover_icon ? ' et_pb_inline_icon' : '' ),
					$data_icon,
					( '' !== $hover_icon_tablet ? ' et_pb_inline_icon_tablet' : '' ),
					$data_icon_tablet,
					( '' !== $hover_icon_phone ? ' et_pb_inline_icon_phone' : '' ),
					$data_icon_phone
				);
			} else if ($hover_overlay_effect == 'overlay_image_data') {

				$animation = '';
				if ( $this -> props['overlay_content_animation'] != 'none' ) {
					$animation = 'dmg-with-animation ' . $this -> props['overlay_content_animation'];
				} else {
					$animation = 'dmg-no-animation';
				}


				$title_content = '';
				if ( $this -> props['show_overlay_title'] != 'off' && $title )
					$title_content = sprintf( '<p class="dmg-img-overlay-title">%1$s</p>', $title );
				
				$caption_content = '';
				if ( $this -> props['show_overlay_caption']  != 'off' && $caption )
					$caption_content = sprintf( '<p class="dmg-img-overlay-caption">%1$s</p>', $caption );

				$overlay_content = sprintf(
					'<div class="playPauseContainer">
					<div class="playPauseImage"><img src="/wp-content/themes/Divi-child/images/playwhite.png" class="play-image" id="play-pause" data-artist="' . str_replace(array("&", " ", "'", ".", "-"),array("and", "", "", "", ""), $title) . '"/></div>
					<a class="spotifyLogo" href="'. $this->artists[str_replace(array("&", " ", "'", ".", "-"),array("and", "", "", "", ""), $title)] .'"><img src="/wp-content/themes/Divi-child/images/spotifylogo.png" class="spotify-logo"/></a>
					</div>
					<audio src="/wp-content/themes/Divi-child/songs/' . str_replace(array("&", " ", "'", ".", "-"),array("and", "", "", "", ""), $title) . '.mp3" class="audio-file" id="' . str_replace(array("&", " ", "'", ".", "-"),array("and", "", "", "", ""), $title) . '"></audio>
					<span class="dmg-img-overlay %1$s">
						<div>
							%2$s
							%3$s
						</div>
					</span>',
					$animation, $title_content, $caption_content
				);
			}

			$link = '#';
			$target = '';
			$paginate_class = $this -> props['paginate_gallery'] == 'on' && $image_index >= $this -> props['page_size'] ? 'dmg-out-page' : '';
			if ( $modal_gallery || $gallery_links) {
				if ( $gallery_links ) {
					$link = $this -> get_image_link($image_index, $this -> props['gallery_links']);
					if ( $this -> props['gallery_links_target'] === 'on' && $link != '#' ) {
						$target = 'target="_blank"';
					}
				} else {
					$link = wp_get_attachment_url( $image_id );
					if ( !$link ) {
						$link = $image;
					}
				}
				
				$images .= sprintf( '				
				<a href="%2$s" %6$s data-categories="%7$s" class="dmg_masonry_gallery_item %8$s" %4$s>
							<img class="lazyload" alt="%13$s" data-src="%1$s" srcset="%9$s" sizes="%10$s" width="%11$s" height="%12$s" />
							%5$s
						</a>
						', $image, $link, $title,
						$image_title_html, $overlay_content, $target, $categories, $paginate_class, $image_srcset, $image_sizes, $image_width, $image_height,
						$image_alt_text
					);
			} else {
				if ( $image !== '' )
					$images .= sprintf( '
						<div data-categories="%5$s" class="dmg_masonry_gallery_item %6$s">
							<img alt="%11$s" class="lazyload" alt="%2$s" data-src="%1$s" srcset="%7$s" sizes="%8$s" width="%9$s" height="%10$s" />
							%4$s
						</div>
						', $image, $title, '',
						$overlay_content, $categories, $paginate_class, $image_srcset, $image_sizes, $image_width, $image_height, $image_alt_text
					);
			}

			$image_index++;
		}

		// Images: Add CSS Filters and Mix Blend Mode rules (if set)
		if ( array_key_exists( 'image', $this->advanced_fields ) && array_key_exists( 'css', $this->advanced_fields['image'] ) ) {
			$generate_css_filters_item = $this->generate_css_filters(
				$render_slug,
				'child_',
				self::$data_utils->array_get( $this->advanced_fields['image']['css'], 'main', '%%order_class%%' )
			);
		}

		$mobile_columns = et_pb_get_responsive_status( $this -> props['columns_last_edited'] );
		$columns_desktop = intval( $this -> props['columns'] );
		$columns_tablet = $mobile_columns && intval( $this -> props['columns_tablet'] ) > 0 ? intval( $this -> props['columns_tablet'] ) : $columns_desktop;
		$columns_phone = $mobile_columns && intval( $this -> props['columns_phone'] ) > 0 ? intval( $this -> props['columns_phone'] ) : $columns_tablet;

		$mobile_gutter = et_pb_get_responsive_status( $this -> props['gutter_last_edited'] );
		$gutter_desktop = $this -> props['gutter'];
		$gutter_tablet = $mobile_gutter ? $this -> props['gutter_tablet'] : $gutter_desktop;
		$gutter_phone = $mobile_gutter ? $this -> props['gutter_phone'] : $gutter_tablet;


		$columns_desktop_width = ( 100 / $columns_desktop ) . '%';
		$columns_tablet_width = ( 100 / $columns_tablet ) . '%';
		$columns_phone_width = ( 100 / $columns_phone ) . '%';

		$columns_desktop_space = 0;
		$columns_tablet_space = 0;
		$columns_phone_space = 0;

		if ( $gutter_desktop > 0 ) {
			$columns_desktop_space = $gutter_desktop * ( $columns_desktop - 1 ) / $columns_desktop;
		}

		if ( $gutter_tablet > 0) {
			$columns_tablet_space = $gutter_tablet * ( $columns_tablet - 1 ) / $columns_tablet;
		}

		if ( $gutter_phone > 0 ) {
			$columns_phone_space = $gutter_phone * ( $columns_phone - 1 ) / $columns_phone;
		}

		//Responsive properties with calc must be done with ET_Builder_Element::set_style due to a bug
		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% .dmg-gallery-sizer, %%order_class%% .dmg_masonry_gallery_item",
			'declaration' => sprintf( 'width: calc( %1$s - %2$spx );', $columns_desktop_width, $columns_desktop_space )
		));

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% .dmg-gallery-sizer, %%order_class%% .dmg_masonry_gallery_item",
			'declaration' => sprintf( 'width: calc( %1$s - %2$spx );', $columns_tablet_width, $columns_tablet_space ),
			'media_query' => ET_Builder_Element::get_media_query( 'max_width_980' ),
		));

		ET_Builder_Element::set_style( $render_slug, array(
			'selector' => "%%order_class%% .dmg-gallery-sizer, %%order_class%% .dmg_masonry_gallery_item",
			'declaration' => sprintf( 'width: calc( %1$s - %2$spx );', $columns_phone_width, $columns_phone_space ),
			'media_query' => ET_Builder_Element::get_media_query( 'max_width_767' ),
		));


		$gutter_width_values = [
			'desktop' => $gutter_desktop . "px",
			'tablet' => $gutter_tablet . "px",
			'phone' => $gutter_phone . "px"
		];

		et_pb_responsive_options() -> generate_responsive_css( $gutter_width_values, "%%order_class%% .dmg-gutter-sizer", 'width', $render_slug );

		$margin_values = [
			'desktop' => $gutter_desktop . "px !important",
			'tablet' => $gutter_tablet . "px !important",
			'phone' => $gutter_phone . "px !important"
		];

		et_pb_responsive_options() -> generate_responsive_css( $margin_values, "%%order_class%% .dmg_masonry_gallery_item", 'margin-bottom', $render_slug );

		$paginator = $this -> render_pagination( $image_index );

		return sprintf( '
			<div class="dmg-gallery-wrapper">
				%3$s
				<div class="dmg-gallery %2$s">
					<div class="dmg-gallery-sizer"></div>
					<div class="dmg-gutter-sizer"></div>
					%1$s
	  			</div>
	  			%4$s
  			</div>
  			', $images, $modal_gallery_class, $filter, $paginator
  		);
	}
}

new DMG_MasonryGallery;

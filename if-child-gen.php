<?php
	/*
	 Plugin Name:       Child Theme Generator
	 Plugin URI:        https://ilanfirsov.me/wordpress/child-theme-generator
	 Description:       Generat Child Themes inside your WordPress dashboard.
	 Version:           1.0.0
	 Author:            Ilan Firsov
	 Author URI:        https://ilanfirsov.me/
	 License:           GPL-2.0+
	 License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
	 Text Domain:       if-child-gen
	 Domain Path:       /languages
	 */

	// If this file is called directly, abort.
	if ( ! defined( 'WPINC' ) ) {
		die;
	}

	/**
	 * Class IF_Child_Theme_Generator
	 */
	final class IF_Child_Theme_Generator {

		/**
		 * @type string
		 */
		protected $version;
		/**
		 * @type string
		 */
		protected $plugin_slug;
		/**
		 * @type string
		 */
		protected $plugin_dir;
		/**
		 * @type string
		 */
		protected $plugin_url;

		/**
		 * IF_Child_Theme_Generator constructor.
		 */
		public function __construct() {
			$this->version     = '1.0.0';
			$this->plugin_slug = 'if-child-gen';
			$this->plugin_dir  = trailingslashit( plugin_dir_path( __FILE__ ) );
			$this->plugin_url  = trailingslashit( plugin_dir_url( __FILE__ ) );

			$this->init();
		}

		/**
		 * Initialize plugin
		 */
		protected function init() {
			$this->load_dependencies();
			$this->load_textdomain();
			$this->register_hooks();
		}

		/**
		 * Load plugin dependencies
		 */
		protected function load_dependencies() {}

		/**
		 * Load plugin textdomain
		 */
		protected function load_textdomain() {
			$locale    = apply_filters( 'plugin_locale', get_locale(), 'if-child-gen' );

			$global_mo = WP_LANG_DIR . '/' . $this->plugin_slug . '/' . 'if-child-gen' . '-' . $locale . '.mo';
			$local_mo  = $this->plugin_dir . '/languages/' . $locale . '.mo';

			load_textdomain( 'if-child-gen', file_exists( $global_mo ) ? $global_mo : $local_mo );
		}

		/**
		 * Register WordPress hooks
		 */
		protected function register_hooks() {
			if ( is_admin() ) {
				add_filter( 'admin_menu', array( $this, 'register_menus' ) );
				add_filter( 'admin_notices', array( $this, 'admin_notices' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
				add_action( 'wp_ajax_if_child_theme_hide_notice', array( $this, 'ajax_if_child_theme_hide_notice' ) );
				add_action( 'wp_ajax_if_child_theme_create_child_theme', array( $this, 'ajax_if_child_theme_create_child_theme' ) );
				add_action( 'wp_ajax_if_child_theme_activate_theme', array( $this, 'ajax_if_child_theme_activate_theme' ) );
			}
		}

		/**
		 * Register options page menu
		 */
		public function register_menus() {
			add_management_page(
				__( 'Child Theme Generator', 'if-child-gen' ),
				__( 'Child Theme Generator', 'if-child-gen' ),
				'edit_theme_options',
				$this->plugin_slug,
				array( $this, 'options_page_output' )
			);
		}

		/**
		 * Options page output
		 */
		public function options_page_output() {
			include $this->plugin_dir . 'options.php';
		}

		/**
		 * Render admin notices
		 */
		public function admin_notices() {
			$current_theme_slug = get_stylesheet();

			if( ! is_child_theme() && ! get_transient( 'if_hide_child_theme_notice_' . $current_theme_slug ) ) :
				$current_theme = wp_get_theme( $current_theme_slug );
				?>

				<div class="notice notice-warning is-dismissible if-child-theme-notice" style="background: #fcf8e3">
					<p>
						<strong><?php printf( __( 'The current theme &ldquo;%s&rdquo; is not a child theme.', 'if-child-gen' ), $current_theme->name ); ?></strong>
						<?php _e( 'Child themes are the recommended way of modifying an existing theme.', 'if-child-gen' ); ?>
					</p>
					<p>
						<button type="button" class="button button-small button-secondary if-child-theme-button hide-notice" style="opacity: 0">
							<?php printf( __( 'Hide notice for &ldquo;%s&rdquo; theme', 'if-child-gen' ), $current_theme->name ); ?>
						</button>
						<a href="<?php echo admin_url( 'tools.php?page=if-child-gen' ); ?>" class="button button-small button-primary if-child-theme-button" style="opacity: 0">
							<?php _e( 'Create a child theme', 'if-child-gen' ); ?>
						</a>
					</p>
				</div>

				<?php
			endif;

		}

		/**
		* Register the stylesheets for the admin area.
		*
		* @since    1.0.0
		*/
		public static function enqueue_styles() {
			if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == $this->plugin_slug ) {
				wp_enqueue_style( $this->plugin_slug . '-sweetalert2', $this->plugin_url . 'assets/libs/sweetalert/sweetalert2.min.css', array(), '3.0.0', 'all' );
				wp_enqueue_style( $this->plugin_slug, $this->plugin_url . 'assets/css/options.css', array(), $this->version, 'all' );
			}
		}

		/**
		* Register the JavaScript for the admin area.
		*
		* @since    1.0.0
		*/
		public static function enqueue_scripts() {
			wp_enqueue_script( 'jquery' );

			if( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == $this->plugin_slug ) {
				wp_enqueue_script( $this->plugin_slug . '-sweetalert2', $this->plugin_url . 'assets/libs/sweetalert/sweetalert2.min.js', array( 'jquery' ), '3.0.0', true );
				wp_enqueue_script( $this->plugin_slug, $this->plugin_url . 'assets/js/options.js', array( 'jquery', $this->plugin_slug . '-sweetalert2' ), $this->version, true );
			}

			wp_enqueue_script( $this->plugin_slug . '-admin', $this->plugin_url . 'assets/js/admin.js', array( 'jquery' ), $this->version, true );
			wp_localize_script( $this->plugin_slug . '-admin', 'if_child_gen',
				array(
					'_nonce' => wp_create_nonce( $this->plugin_slug ),
					'i18n' => array(
						'ajax_error' => __( 'There has been a problem processing your request.', 'if-child-gen' ),
						'success' => __( 'Success', 'if-child-gen' ),
						'failure' => __( 'Failure', 'if-child-gen' ),

						'child_theme_created' => __( 'The child theme was created.', 'if-child-gen' ),
						'child_theme_failed' => __( 'There has been a problem creating the child theme.', 'if-child-gen' ),

						'child_theme_activated' => __( 'The child theme was activated successfully.', 'if-child-gen' ),
						'child_theme_not_activated' => __( 'The child theme could not be activated at this time.', 'if-child-gen' ),

						'close' => __( 'Close', 'if-child-gen' ),
						'switch_to_theme' => __( 'Activate theme now', 'if-child-gen' ),
					),
				)
			);
			wp_enqueue_script( $this->plugin_slug . '-admin' );
		}


		/**
		 * AJAX callback: hide child theme notice for current theme
		 * @return string
		 */
		public function ajax_if_child_theme_hide_notice() {
			if( ! check_ajax_referer( $this->plugin_slug, '_wpnonce', false ) ) {
				echo 0;
				exit;
			}

			set_transient( 'if_hide_child_theme_notice_' . get_stylesheet(), 1 );
			echo 1;
			exit;
		}

		/**
		 * AJAX callback: create child theme
		 * @return string
		 */
		public function ajax_if_child_theme_create_child_theme() {
			if( ! isset( $_REQUEST['form_data'] ) ) {
				die('0');
			}

			// parse form request
			parse_str( $_REQUEST['form_data'], $_REQUEST );
			if( ! check_ajax_referer( $this->plugin_slug, '_wpnonce', false ) ) {
				die('0');
			}

			// get selected theme data
			$theme = wp_get_theme( $_REQUEST['parent_theme'] );
			if( ! $theme ) {
				die('0');
			}

			// create child theme folder
			$root = get_theme_root();
			$original_chile_theme_name = $child_theme_name = $_REQUEST['parent_theme'] . '-child';
			$original_theme_path = $child_theme_path = wp_normalize_path( trailingslashit( $root ) . $child_theme_name );

			$count = 0;
			while ( file_exists( $child_theme_path ) ) {
				$count++;
				$child_theme_name = $original_chile_theme_name . '-' . $count;
				$child_theme_path = wp_normalize_path( $original_theme_path . '-' . $count );
			}

			mkdir( $child_theme_path, defined( 'FS_CHMOD_DIR' ) ? FS_CHMOD_DIR : 0755, true );

			// parse theme header data
			$parent_theme 		= $_REQUEST['parent_theme'];
			$theme_name 		= ! empty( $_REQUEST['theme_name'] ) 		? trim( $_REQUEST['theme_name'] ) 							: $child_theme_name;
			$theme_uri 			= ! empty( $_REQUEST['theme_uri'] ) 		? trim( $_REQUEST['theme_uri'] ) 							: '';
			$theme_description 	= ! empty( $_REQUEST['theme_description'] ) ? trim( $_REQUEST['theme_description'] ) 					: '';
			$theme_author 		= ! empty( $_REQUEST['theme_author'] ) 		? trim( $_REQUEST['theme_author'] ) 						: '';
			$theme_author_uri 	= ! empty( $_REQUEST['theme_author_uri'] ) 	? trim( $_REQUEST['theme_author_uri'] ) 					: '';
			$theme_version 		= ! empty( $_REQUEST['theme_version'] ) 	? trim( $_REQUEST['theme_version'] ) 						: '';
			$theme_license 		= ! empty( $_REQUEST['theme_license'] ) 	? trim( $_REQUEST['theme_license'] ) 						: '';
			$theme_license_uri	= ! empty( $_REQUEST['theme_license_uri'] ) ? trim( $_REQUEST['theme_license_uri'] ) 					: '';
			$theme_tags 		= ! empty( $_REQUEST['theme_tags'] ) 		? implode( ', ', $_REQUEST['theme_tags'] )					: '';
			$theme_text_domain 	= ! empty( $_REQUEST['theme_text_domain'] ) ? implode( ', ', $_REQUEST['theme_text_domain'] ) 			: $child_theme_name;
			$license_text 		= ! empty( $_REQUEST['license_text'] ) 		? "/* \n" . trim( $_REQUEST['license_text'] ) . " */ \n"	: '';
			$extra_comments 	= ! empty( $_REQUEST['extra_comments'] ) 	? "/* \n" . trim( $_REQUEST['extra_comments'] ) . " */ \n"	: '';

			// save child theme name to allow switching to it
			set_transient( 'if_child_gen_last_theme_created', $child_theme_name, 10 * MINUTE_IN_SECONDS );

			// write `style.css` file
			$style_css_file = <<<EOD
/*
 Theme Name:   {$theme_name}
 Theme URI:    {$theme_uri}
 Description:  {$theme_description}
 Author:       {$theme_author}
 Author URI:   {$theme_author_uri}
 Template:     {$parent_theme}
 Version:      {$theme_version}
 License:      {$theme_license}
 License URI:  {$theme_license_uri}
 Tags:         {$theme_tags}
 Text Domain:  {$theme_text_domain}
*/
{$license_text}

{$extra_comments}

/* This is where you put all the CSS for your child theme */

EOD;
			file_put_contents( wp_normalize_path( trailingslashit( $child_theme_path ) . 'style.css' ), $style_css_file );

			// write `functions.php` file and enqueue styles
			$functions_prefix = mb_strtolower( preg_replace( '/[_\-\t\s]/', '_', trim( $child_theme_name ) ) );
			$functions_php_file = <<<EOD
<?php
	/**
	 * Register theme scripts and styles
	 */
	function {$functions_prefix}_enqueue_styles() {
		wp_enqueue_style( '{$parent_theme}', get_template_directory_uri() . '/style.css' );
	    wp_enqueue_style( '{$child_theme_name}', get_stylesheet_uri(), array( '{$parent_theme}' ) );
	}
	add_action( 'wp_enqueue_scripts', '{$functions_prefix}_enqueue_styles' );

EOD;
			file_put_contents( wp_normalize_path( trailingslashit( $child_theme_path ) . 'functions.php' ), $functions_php_file );

			// copy screenshot from parent theme
			$screenshot_file_path = wp_normalize_path( trailingslashit( $theme->get_stylesheet_directory() ) . $theme->get_screenshot('relative') );
			if( file_exists( $screenshot_file_path ) ) {
				$screenshot_data = file_get_contents( $screenshot_file_path );
				file_put_contents( wp_normalize_path( trailingslashit( $child_theme_path ) . $theme->get_screenshot('relative') ), $screenshot_data );
			}


			die('1');
		}

		/**
		 * AJAX callback: activate child theme
		 * @return string
		 */
		public function ajax_if_child_theme_activate_theme() {
			if( ! check_ajax_referer( $this->plugin_slug, '_wpnonce', false ) ) {
				die('0');
			}

			$parent_theme = trim( $_REQUEST['parent_theme'] );
			$child_theme = get_transient( 'if_child_gen_last_theme_created' );

			if( ! $child_theme || ! $parent_theme ) {
				die('0');
			}

			switch_theme( $parent_theme, $child_theme );
			die('1');
		}


		/**
		 * Get WordPress theme tags
		 * @return array
		 */
		public static function getThemeTags() {
			$theme_tags = get_transient( 'if_child_gen_theme_tags' );
		    if( ! $theme_tags ) {
		        $response = wp_remote_get( 'https://api.wordpress.org/themes/info/1.1/?action=feature_list' );
		        if( ! is_wp_error( $response ) ) {
		            $theme_tags = wp_remote_retrieve_body( $response );
		            $theme_tags = json_decode( $theme_tags, true );
		            $theme_tags = $theme_tags ? $theme_tags : array();
		            set_transient( 'if_child_gen_theme_tags', $theme_tags, WEEK_IN_SECONDS );
		        } else {
		        	$theme_tags = array();
		        }
		    }

		    return array_merge($theme_tags, array(
		    	'Colors' => array(
		    		'black', 'blue', 'brown', 'gray', 'green', 'orange', 'pink', 'purple', 'red', 'silver', 'tan', 'white', 'yellow',
		    		'dark', 'light',
	    		)
	    	));
		}
	}
	new IF_Child_Theme_Generator();

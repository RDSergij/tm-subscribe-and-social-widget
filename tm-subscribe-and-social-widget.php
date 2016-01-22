<?php
/**
 * Plugin Name: TM Subscribe and Social Widget
 * Plugin URI: https://github.com/RDSergij
 * Description: MailChimp subscribe and social link section widget
 * Version: 1.0.0
 * Author: Osadchyi Serhii
 * Author URI: https://github.com/RDSergij
 * Text Domain: photolab-base-tm
 *
 * @package TM_Subscribe_And_Social_Widget
 *
 * @since 1.0.0
 */

if ( ! class_exists( 'TM_Subscribe_And_Social_Widget' ) ) {

	// simple api class for MailChimp from https://github.com/drewm/mailchimp-api/blob/master/src/Drewm/MailChimp.php
	require_once( 'admin/lib/mailchimp-api/mailchimp-api.php' );
	/**
	 * Set constant text domain.
	 *
	 * @since 1.0.0
	 */
	if ( ! defined( 'PHOTOLAB_BASE_TM_ALIAS' ) ) {
		define( 'PHOTOLAB_BASE_TM_ALIAS', 'photolab-base-tm' );
	}

	/**
	 * Set constant path of text domain.
	 *
	 * @since 1.0.0
	 */
	if ( ! defined( 'PHOTOLAB_BASE_TM_PATH' ) ) {
		define( 'PHOTOLAB_BASE_TM_PATH', dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Adds register_tm_subscribe_and_social_widget widget.
	 */
	class TM_Subscribe_And_Social_Widget extends WP_Widget {

		/**
		 * Default settings
		 *
		 * @var type array
		 */
		private $instance_default = array();
		private $social_list = array( 'twitter', 'facebook', 'google-plus', 'vk', 'instagram', 'pinterest', 'youtube', 'linkedin' );
		/**
		 * Register widget with WordPress.
		 */
		function __construct() {
			parent::__construct(
				'tm_subscribe_and_social_widget', // Base ID
				__( 'TM Subscribe and Social Widget', PHOTOLAB_BASE_TM_ALIAS ),
				array( 'description' => __( 'MailChimp subscribe and social link section widget', PHOTOLAB_BASE_TM_ALIAS ) )
			);
			// Set default settings
			$this->instance_default = array(
				'subscribe_is'				=> 'true',
				'subscribe_title'			=> __( 'Subscribe title', PHOTOLAB_BASE_TM_ALIAS ),
				'subscribe_description'		=> __( 'Subscribe description', PHOTOLAB_BASE_TM_ALIAS ),
				'api_key'					=> '',
				'list_id'					=> '',
				'success_message'			=> __('Success', PHOTOLAB_BASE_TM_ALIAS),
				'failed_message'			=> __('Failed', PHOTOLAB_BASE_TM_ALIAS),

				'social_is'					=> 'true',
				'social_title'				=> __( 'Social title', PHOTOLAB_BASE_TM_ALIAS ),
				'social_description'		=> __( 'Social description', PHOTOLAB_BASE_TM_ALIAS ),
				'social_buttons'			=> array(),
			);

			// Need for submit frontend form
			add_action( 'wp_ajax_tm-mailchimp-subscribe', array( &$this, 'subscriber_add' ) );
			add_action( 'wp_ajax_nopriv_tm-mailchimp-subscribe', array( &$this, 'subscriber_add' ) );
		}

		/**
		 * Load languages“э≥
		 *
		 * @since 1.0.0
		 */
		public function include_languages() {
			load_plugin_textdomain( PHOTOLAB_BASE_TM_ALIAS, false, PHOTOLAB_BASE_TM_PATH );
		}

		/**
		 * Add new subscriber
		 */
		public function subscriber_add() {
			foreach ( $this->instance_default as $key => $value ) {
				$$key = ! empty( $instance[ $key ] ) ? $instance[ $key ] : $value;
			}

			$return = array(
				'status'	=> 'failed',
				'message'	=> $failed_message,
			);

			$email		= sanitize_email( $_POST['email'] );
			$api_key	= $_POST['api-key'];
			$list_id	= $_POST['list-id'];

			// Create MailChimp API object
			$mailerAPI_obj = new MailChimp( $api_key );

			if ( is_email( $email ) && ! empty( $api_key ) && $mailerAPI_obj->validate_api_key() ) {

				// Call API
				$result = $mailerAPI_obj->call( '/lists/subscribe', array(
					'id'	=> $list_id,
					'email'	=> array(
						'email'    => $email,
						'euid'     => time() . rand( 1, 1000 ),
						'leid'     => time() . rand( 1, 1000 ),
					),
					'double_optin'	=> true,
				), 20);

				if ( ! empty( $result['leid'] ) ) {

					// Success response
					$return = array(
						'status'	=> 'success',
						'message'	=> $success_message,
					);
				} else {
					$return['message'] = $failed_message;
				}

				$return['result'] = $result;

			}

			// Send answer
			wp_send_json( $return );
		}
		/**
		 * Frontend view
		 *
		 * @param type $args array.
		 * @param type $instance array.
		 */
		public function widget( $args, $instance ) {

			// Custom js
			wp_register_script( 'tm-subscribe-and-social-script-frontend', plugins_url( 'assets/js/frontend.min.js', __FILE__ ), '', '', true );
			wp_localize_script( 'tm-subscribe-and-social-script-frontend', 'TMSubscribeAndShareWidgetParam', array(
						'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
					)
				);
			wp_enqueue_script( 'tm-subscribe-and-social-script-frontend' );

			// Custom styles
			wp_register_style( 'tm-subscribe-and-social-frontend', plugins_url( 'assets/css/frontend.min.css', __FILE__ ) );
			wp_enqueue_style( 'tm-subscribe-and-social-frontend' );

			// Font Awesome
			wp_register_style( 'font-awesome', plugins_url( 'assets/css/font-awesome.min.css', __FILE__ ) );
			wp_enqueue_style( 'font-awesome' );

			foreach ( $this->instance_default as $key => $value ) {
				$$key = ! empty( $instance[ $key ] ) ? $instance[ $key ] : $value;
			}

			$subscribe_submit_src = plugins_url( 'images/', __FILE__ ) . 'subscribe-submit.png';

			require __DIR__ . '/views/frontend.php';
		}

		/**
		 * Create admin form for widget
		 *
		 * @param type $instance array.
		 */
		public function form( $instance ) {
			foreach ( $this->instance_default as $key => $value ) {
				$$key = ! empty( $instance[ $key ] ) ? $instance[ $key ] : $value;
			}

			// Custom js
			wp_register_script( 'tm-subscribe-and-social-script-admin', plugins_url( 'assets/js/admin.min.js', __FILE__ ) );
			wp_localize_script( 'tm-subscribe-and-social-script-admin', 'TMWidgetParam', array(
						'ajaxurl'		=> admin_url( 'admin-ajax.php' ),
					)
				);
			wp_enqueue_script( 'tm-subscribe-and-social-script-admin' );
			// Custom styles
			wp_register_style( 'tm-subscribe-and-social-admin', plugins_url( 'assets/css/admin.min.css', __FILE__ ) );
			wp_enqueue_style( 'tm-subscribe-and-social-admin' );

			// include ui-elements
			require_once __DIR__ . '/admin/lib/fox-ui-elements/ui-switcher.php';
			require_once __DIR__ . '/admin/lib/fox-ui-elements/ui-input.php';
			require_once __DIR__ . '/admin/lib/fox-ui-elements/ui-select.php';

			$subscribe_is_field = new UI_Switcher_Fox(
							array(
								'id'        => $this->get_field_id( 'subscribe_is' ) . ' subscribe_is',
								'class'		=> 'pull-right',
								'name'      => $this->get_field_name( 'subscribe_is' ),
								'values'    => array( 'true' => 'ON', 'false' => 'OFF' ),
								'default'    => $subscribe_is,
							)
					);
			$subscribe_is_html = $subscribe_is_field->output();

			$subscribe_title_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'subscribe_title' ),
						'name'			=> $this->get_field_name( 'subscribe_title' ),
						'value'			=> $subscribe_title,
						'label'			=> __( 'Subscribe title', PHOTOLAB_BASE_TM_ALIAS ),
						'placeholder'	=> __( 'Subscribe title', PHOTOLAB_BASE_TM_ALIAS ),
					)
			);
			$subscribe_title_html = $subscribe_title_field->output();

			$subscribe_description_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'subscribe_description' ),
						'name'			=> $this->get_field_name( 'subscribe_description' ),
						'value'			=> $subscribe_description,
						'label'			=> __( 'Subscribe description', PHOTOLAB_BASE_TM_ALIAS ),
						'placeholder'	=> __( 'Subscribe description', PHOTOLAB_BASE_TM_ALIAS ),
					)
			);
			$subscribe_description_html = $subscribe_description_field->output();

			$api_key_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'api_key' ),
						'name'			=> $this->get_field_name( 'api_key' ),
						'value'			=> $api_key,
						'label'			=> __( 'MailChimp ApiKey', PHOTOLAB_BASE_TM_ALIAS ),
						'placeholder'	=> __( 'MailChimp ApiKey', PHOTOLAB_BASE_TM_ALIAS ),
					)
			);
			$api_key_html = $api_key_field->output();

			$list_id_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'list_id' ),
						'name'			=> $this->get_field_name( 'list_id' ),
						'value'			=> $list_id,
						'label'			=> __( 'MailChimp list id', PHOTOLAB_BASE_TM_ALIAS ),
						'placeholder'	=> __( 'MailChimp list id', PHOTOLAB_BASE_TM_ALIAS ),
					)
			);
			$list_id_html = $list_id_field->output();

			$success_message_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'success_message' ),
						'name'			=> $this->get_field_name( 'success_message' ),
						'value'			=> $success_message,
						'label'			=> __( 'Success message', PHOTOLAB_BASE_TM_ALIAS ),
						'placeholder'	=> __( 'Success', PHOTOLAB_BASE_TM_ALIAS ),
					)
			);
			$success_message_html = $success_message_field->output();

			$failed_message_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'failed_message' ),
						'name'			=> $this->get_field_name( 'failed_message' ),
						'value'			=> $failed_message,
						'label'			=> __( 'Failed message', PHOTOLAB_BASE_TM_ALIAS ),
						'placeholder'	=> __( 'Failed', PHOTOLAB_BASE_TM_ALIAS ),
					)
			);
			$failed_message_html = $failed_message_field->output();

			$social_is_field = new UI_Switcher_Fox(
							array(
								'id'        => $this->get_field_id( 'social_is' ),
								'class'		=> 'pull-right',
								'name'      => $this->get_field_name( 'social_is' ),
								'values'    => array( 'true' => 'ON', 'false' => 'OFF' ),
								'default'    => $social_is,
							)
					);
			$social_is_html = $social_is_field->output();

			$social_title_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'social_title' ),
						'name'			=> $this->get_field_name( 'social_title' ),
						'value'			=> $social_title,
						'label'			=> __( 'Social title', PHOTOLAB_BASE_TM_ALIAS ),
						'placeholder'	=> __( 'Social title', PHOTOLAB_BASE_TM_ALIAS ),
					)
			);
			$social_title_html = $social_title_field->output();

			$social_description_field = new UI_Input_Fox(
					array(
						'id'			=> $this->get_field_id( 'social_description' ),
						'name'			=> $this->get_field_name( 'social_description' ),
						'value'			=> $social_description,
						'label'			=> __( 'social description', PHOTOLAB_BASE_TM_ALIAS ),
						'placeholder'	=> __( 'social description', PHOTOLAB_BASE_TM_ALIAS ),
					)
			);
			$social_description_html = $social_description_field->output();

			$social_items = array();
			if ( is_array( $social_buttons ) && count( $social_buttons ) > 0 ) {
				foreach ( $social_buttons as $key => $button ) {
					$service_field = new UI_Input_Fox(
								array(
									'id'				=> $this->get_field_id( 'service_' . $key ),
									'name'				=> $this->get_field_name( 'service[]' ),
									'value'				=> $button['service'],
									'datalist'			=> $this->social_list,
									'placeholder'	=> __( 'choose or input social media', PHOTOLAB_BASE_TM_ALIAS ),
								)
							);
					$url_field = new UI_Input_Fox(
								array(
									'id'			=> $this->get_field_id( 'url_' . $key ),
									'name'			=> $this->get_field_name( 'url[]' ),
									'value'			=> $button['url'],
									'placeholder'	=> __( 'input url to your page', PHOTOLAB_BASE_TM_ALIAS ),
								)
							);
					$social_items[] = array( 'service' => $service_field->output(),'url' => $url_field->output() );
				}
			}

			$service_field = new UI_Input_Fox(
								array(
									'id'				=> $this->get_field_id( 'service_new' ),
									'name'				=> $this->get_field_name( 'service_new[]' ),
									'value'				=> '',
									'datalist'			=> $this->social_list,
									'placeholder'	=> __( 'choose or input social media', PHOTOLAB_BASE_TM_ALIAS ),
								)
							);
			$url_field = new UI_Input_Fox(
								array(
									'id'			=> $this->get_field_id( 'url_new' ),
									'name'			=> $this->get_field_name( 'ur_newl[]' ),
									'value'			=> '',
									'placeholder'	=> __( 'input url to your page', PHOTOLAB_BASE_TM_ALIAS ),
								)
							);
			$social_new = array( 'service' => $service_field->output(),'url' => $url_field->output() );
			// show view
			require 'views/widget-form.php';
		}

		/**
		 * Update settings
		 *
		 * @param type $new_instance array.
		 * @param type $old_instance array.
		 * @return type array
		 */
		public function update( $new_instance, $old_instance ) {
			$instance = array();

			foreach ( $this->instance_default as $key => $value ) {
				$instance[ $key ] = ! empty( $new_instance[ $key ] ) ? $new_instance[ $key ] : $value;
			}

			foreach ( $new_instance['service'] as $key => $social_item ) {
				$instance['social_buttons'][] = array( 'service' => $social_item, 'url' => $new_instance['url'][ $key ] );
			}

			return $instance;
		}
	}

	/**
	 * Register widget
	 */
	function register_tm_subscribe_and_social_widget() {
		register_widget( 'tm_subscribe_and_social_widget' );
	}
	add_action( 'widgets_init', 'register_tm_subscribe_and_social_widget' );

}

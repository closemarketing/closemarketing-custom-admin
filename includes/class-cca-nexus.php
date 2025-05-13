<?php
/**
 * Nexus functions
 *
 * Has functions optimize the WordPress
 *
 * @author   closemarketing
 * @category Functions
 * @package  Admin
 */

/**
 * Class for admin fields
 */
class CCA_Nexus {
	/**
	 * Construct of Class
	 */
	public function __construct() {
		add_action( 'activated_plugin', array( $this, 'on_activated_deactivated' ), 10, 1 );
		add_action( 'deactivated_plugin', array( $this, 'on_activated_deactivated' ) );
		add_action( 'after_switch_theme', array( $this, 'on_activated_deactivated' ) );
		add_action( 'upgrader_process_complete', array( $this, 'on_plugin_updated' ), 10, 2 );
	}

	/**
	 * Triggered when a plugin is activated or deactivated.
	 *
	 * @return void
	 */
	public function on_activated_deactivated() {
		$this->send_data();
	}

	/**
	 * Triggered when a plugin is updated.
	 *
	 * @param object $upgrader_object The upgrader object.
	 * @param array  $options         The options array.
	 * @return void
	 */
	public function on_plugin_updated( $upgrader_object, $options ) {
		if ( isset( $options['action'] ) && 'update' === $options['action'] && isset( $options['plugins'] ) ) {
			$this->send_data();
		}
	}

	/**
	 * Send data to external URL
	 *
	 * @return void
	 */
	public function send_data() {
		if ( $this->is_dev_installation() ) {
			return;
		}
		$installation_data = $this->get_installation_data();

		error_log( 'wp_json_encode( $installation_data ): ' . print_r( wp_json_encode( $installation_data ), true ) );

		$url = 'local' === wp_get_environment_type() ? 'http://127.0.0.1:8000/' : 'https://nexus.close.red/';
		$url .= 'api/v1/installations';

		$response = wp_remote_post(
			$url,
			array(
				'body'    => wp_json_encode( $installation_data ),
				'headers' => array(
					'Content-Type' => 'application/json',
				),
				'timeout' => 15,
			)
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			error_log( "Error in POST $error_message" );
		}
	}

	/**
	 * Check if the installation is local.
	 *
	 * @return bool
	 */
	private function is_dev_installation() {
		if ( defined( 'WP_TESTING_NEXUS' ) && WP_TESTING_NEXUS ) {
			return false;
		}

		if ( 'local' === wp_get_environment_type() || 'development' === wp_get_environment_type() || 'staging' === wp_get_environment_type() ) {
			return true;
		}

		$site_url = get_site_url();
		if ( false !== strpos( '.local', $site_url ) ) {
			return true;
		}

		if ( false !== strpos( 'localhost', $site_url ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Fetches installation data.
	 * Returns this information in JSON format and sends it to a specified external URL via POST request.
	 *
	 * @return array
	 */
	private function get_installation_data() {
		$site_url    = get_site_url();
		$wp_version  = get_bloginfo( 'version' );
		$php_version = phpversion();
		$plugins     = get_plugins();
		$plugin_data = array();

		foreach ( $plugins as $slug => $plugin ) {
			$plugin_slug = explode( '/', $slug );
			$plugin_slug = $plugin_slug[0] ?? $slug;

			$plugin_data[] = array(
				'name'        => $plugin['Name'],
				'description' => $plugin['Description'],
				'slug'        => $plugin_slug,
				'version'     => $plugin['Version'],
				'is_active'   => is_plugin_active( $slug ),
			);
		}

		// Add the theme data.
		$themes       = wp_get_themes();
		$theme_data   = array();
		$actual_theme = wp_get_theme();
		$theme_slug   = $actual_theme->get_stylesheet();

		foreach ( $themes as $slug => $theme ) {
			$theme_data[] = array(
				'name'        => $theme->get( 'Name' ),
				'description' => $theme->get( 'Description' ),
				'slug'        => $slug,
				'version'     => $theme->get( 'Version' ),
				'is_active'   => $theme_slug === $slug,
			);
		}

		return array(
			'site_url'    => $site_url,
			'wp_version'  => $wp_version,
			'php_version' => $php_version,
			'plugins'     => $plugin_data,
			'themes'      => $theme_data,
		);
	}
}

new CCA_Nexus();

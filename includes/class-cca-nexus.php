<?php

	/**
	 * Fetches WordPress site data including site URL, WordPress version, PHP version, and active plugins.
	 * Returns this information in JSON format and sends it to a specified external URL via POST request.
	 *
	 * @return void
	 */
	function get_wordpress_data() {
		$site_url = get_site_url();
		$wp_version = get_bloginfo('version');
		$php_version = phpversion();
		$plugins = get_plugins();
		$plugin_data = [];

		foreach ( $plugins as $slug => $plugin ) {
			$plugin_info = [
					'name'        => $plugin['Name'],
					'description' => $plugin['Description'],
					'slug'        => $slug,
					'version'     => $plugin['Version'],
					'active'      => is_plugin_active( $slug ),
			];

			$plugin_data[] = $plugin_info;
		}

		$data = [
			'site_url'         => $site_url,
			'wordpress_version' => $wp_version,
			'php_version'      => $php_version,
			'plugins'          => $plugin_data,
		];

		$json_data = json_encode($data);

		$url = 'url/api/installations';

		$response = wp_remote_post( $url, [
			'body'    => $json_data,
			'headers' => [
					'Content-Type' => 'application/json',
			],
			'timeout' => 15,
		]);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			error_log("Error en la solicitud POST: $error_message");
		} else {
			$body = wp_remote_retrieve_body( $response );
			$status_code = wp_remote_retrieve_response_code( $response );

			$response_data = json_decode($body, true);
		}
	}

	/**
	 * Triggered when a plugin is activated.
	 *
	 * @param string $plugin The plugin file path.
	 * @return void
	 */
	function on_plugin_activated($plugin) {
		get_wordpress_data();
	}
	add_action('activated_plugin', 'on_plugin_activated');

	/**
	 * Triggered when a plugin is deactivated.
	 *
	 * @param string $plugin
	 * @return void
	 */
	function on_plugin_deactivated($plugin) {
		get_wordpress_data();
	}
	add_action('deactivated_plugin', 'on_plugin_deactivated');

	/**
	 * Triggered when a plugin is updated.
	 * @param object $upgrader_object 
	 * @param array  $options         
	 * @return void
	 */
	function on_plugin_updated($upgrader_object, $options) {
		if (isset($options['action']) && $options['action'] == 'update' && isset($options['plugins'])) {
			get_wordpress_data();
		}
	}
	add_action('upgrader_process_complete', 'on_plugin_updated', 10, 2);
?>

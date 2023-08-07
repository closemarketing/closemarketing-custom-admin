<?php
/**
 * Plugins recommended
 *
 * @package    WordPress
 * @author     David Perez <david@closemarketing.es>
 * @copyright  2019 Closemarketing
 * @version    1.0
 */

require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'cmk_register_required_plugins' );
/**
 * Register plugins necessary for Closemarketing webs
 *
 * @return void
 */
function cmk_register_required_plugins() {

	//* Recommended for Woocoommerce
	$plugins_woo = array(

		array(
			'name'     => 'SMNTCS WooCommerce Quantity Buttons',
			'slug'     => 'smntcs-woocommerce-quantity-buttons',
			'required' => false,
		),

		array(
			'name'     => 'WooCommerce Menu Cart',
			'slug'     => 'woocommerce-menu-bar-cart',
			'required' => false,
		),

		array(
			'name'     => 'WooCommerce (ES)',
			'slug'     => 'woocommerce-es',
			'required' => false,
		),

		array(
			'name'     => 'Variation Swatches for WooCommerce',
			'slug'     => 'variation-swatches-for-woocommerce',
			'required' => false,
		),

		array(
			'name'     => 'Customer Reviews for WooCommerce',
			'slug'     => 'customer-reviews-woocommerce',
			'required' => true,
		),

		array(
			'name'     => 'Spanish Enhacements for WooCommerce',
			'slug'     => 'woocommerce-es',
			'required' => true,
		),

	);

	//* Recommended for local
	$plugins_local = array(

		array(
			'name'     => 'Show Hooks',
			'slug'     => 'show-hooks',
			'required' => true,
		),

		array(
			'name'     => 'Query Monitor',
			'slug'     => 'query-monitor',
			'required' => true,
		),

	);

	//* Recommended for live
	$plugins_live = array(

		array(
			'name'     => 'Two Factor',
			'slug'     => 'two-factor',
			'required' => true,
		),

		array(
			'name'     => 'Limit Login Attempts Reloaded',
			'slug'     => 'limit-login-attempts-reloaded',
			'required' => true,
		),

		array(
			'name'     => 'GDPR Cookie Compliance',
			'slug'     => 'gdpr-cookie-compliance',
			'required' => false,
		),

		array(
			'name'     => 'Duplicate Post',
			'slug'     => 'duplicate-post',
			'required' => false,
		),

		array(
			'name'     => 'FluentSMTP',
			'slug'     => 'fluentsmtp',
			'required' => false,
		),

		array(
			'name'     => 'DuracellTomi Google Tag Manager para WordPress',
			'slug'     => 'duracelltomi-google-tag-manager',
			'required' => true,
		),

		array(
			'name'     => 'Crop-Thumbnails',
			'slug'     => 'crop-thumbnails',
			'required' => false,
		),

		array(
			'name'     => 'Auto Image Attributes From Filename With Bulk Updater',
			'slug'     => 'auto-image-attributes-from-filename-with-bulk-updater',
			'required' => true,
		),

		array(
			'name'     => 'Broken Link Checker',
			'slug'     => 'broken-link-checker',
			'required' => true,
		),

		array(
			'name'     => 'BBQ Firewall',
			'slug'     => 'block-bad-queries',
			'required' => true,
		),

		array(
			'name'     => 'Stop Spammers',
			'slug'     => 'stop-spammer-registrations-plugin',
			'required' => true,
		),

		array(
			'name'     => 'Maintenance',
			'slug'     => 'maintenance',
			'required' => false,
		),

		array(
			'name'     => 'EWWW Image Optimizer',
			'slug'     => 'ewww-image-optimizer',
			'required' => true,
		),
		array(
			'name'     => 'FormsCRM',
			'slug'     => 'formscrm',
			'required' => false,
		),
		array(
			'name'     => 'Event Tracking for Gravity Forms',
			'slug'     => 'gravity-forms-google-analytics-event-tracking',
			'required' => false,
		),

	);

	// Generic.
	$plugins_generic = array(
		array(
			'name'     => 'Regenerate Thumbnails',
			'slug'     => 'regenerate-thumbnails',
			'required' => false,
		),
		array(
			'name'        => 'WordPress SEO by Yoast',
			'slug'        => 'wordpress-seo',
			'is_callable' => 'wpseo_init',
			'required'    => false,
		),
		array(
			'name'     => 'WordPress SEO Plugin - Rank Math',
			'slug'     => 'seo-by-rank-math',
			'required' => true,
		),
		array(
			'name'     => 'Generate Blocks',
			'slug'     => 'generateblocks',
			'required' => false,
		),
	);

	$plugins = array();

	if ( class_exists( 'WooCommerce' ) ) {
		$plugins = $plugins_woo;
	}

	$server_host = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : '';

	$tld = substr( $server_host, -3 );

	if ( 'localhost' === $server_host || 'loc' === $tld || 'dev' === $tld || 'local' === $tld ) {
		$plugins = array_merge( $plugins, $plugins_generic, $plugins_local );
	} else {
		$plugins = array_merge( $plugins, $plugins_generic, $plugins_live );
	}

	/*
	* Array of configuration settings. Amend each line as needed.
	*
	* TGMPA will start providing localized text strings soon. If you already have translations of our standard
	* strings available, please help us make TGMPA even better by giving us access to these translations or by
	* sending in a pull-request with .po file(s) with the translations.
	*
	* Only uncomment the strings in the config array if you want to customize the strings.
	*/
	$config = array(
		'id'           => 'closemarketing-custom-admin', // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '', // Default absolute path to bundled plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'plugins.php', // Parent menu slug.
		'capability'   => 'manage_options', // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => false, // Show admin notices or not.
		'dismissable'  => true, // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '', // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => false, // Automatically activate plugins after installation or not.
		'message'      => '', // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'closemarketing-custom-admin' ),
			'menu_title'                      => __( 'Install Plugins', 'closemarketing-custom-admin' ),
			'installing'                      => __( 'Installing Plugin: %s', 'closemarketing-custom-admin' ), // %s = plugin name.
			'oops'                            => __( 'Something went wrong with the plugin API.', 'closemarketing-custom-admin' ),
			'notice_can_install_required'     => _n_noop(
				'Closemarketing requires the following plugin: %1$s.',
				'Closemarketing requires the following plugins: %1$s.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop(
				'Closemarketing recommends the following plugin: %1$s.',
				'Closemarketing recommends the following plugins: %1$s.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop(
				'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop(
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with Closemarketing: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with Closemarketing: %1$s.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'notice_ask_to_update_maybe'      => _n_noop(
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop(
				'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop(
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop(
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop(
				'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
				'closemarketing-custom-admin'
			), // %1$s = plugin name(s).
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'closemarketing-custom-admin'
			),
			'update_link'                     => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'closemarketing-custom-admin'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'closemarketing-custom-admin'
			),
			'return'                          => __('Return to Required Plugins Installer', 'closemarketing-custom-admin'),
			'plugin_activated'                => __('Plugin activated successfully.', 'closemarketing-custom-admin'),
			'activated_successfully'          => __('The following plugin was activated successfully:', 'closemarketing-custom-admin'),
			'plugin_already_active'           => __('No action taken. Plugin %1$s was already active.', 'closemarketing-custom-admin'), // %1$s = plugin name(s).
			'plugin_needs_higher_version'     => __('Plugin not activated. A higher version of %s is needed for Closemarketing. Please update the plugin.', 'closemarketing-custom-admin'), // %1$s = plugin name(s).
			'complete'                        => __('All plugins installed and activated successfully. %1$s', 'closemarketing-custom-admin'), // %s = dashboard link.
			'contact_admin'                   => __('Please contact the administrator of this site for help.', 'closemarketing-custom-admin'),

			'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		),
	);

	tgmpa( $plugins, $config );
}

<?php
/**
 * Short Description
 *
 * @package    packace-name
 * @author     example <email>
 * @copyright  year Company
 * @version    Version
 */

defined( 'ABSPATH' ) || exit;

/**
 * Yoast remove ads.
 *
 * Remove ads from free plugin.
 *
 * @since 1.0
 */
class CCA_Yoast_Remove {

    /**
     * Construct of Class
     */
    public function __construct() {
        add_action('get_header', array( $this, example()))
    }

    /* Ocultamos avisos de Yoast SEO */
    function ayudawp_ocultar_avisos_yoast() {
        remove_action('admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'));
        remove_action('all_admin_notices', array(Yoast_Notification_Center::get(), 'display_notifications'));
        }
        add_action('admin_init', 'ayudawp_ocultar_avisos_yoast');
        
        
        /* Baja prioridad del botón de Yoast SEO */
        function ayudawp_ocultar_boton_yoast() {
        return 'low';
        }
        add_filter('wpseo_metabox_prio', 'ayudawp_ocultar_boton_yoast');
        
        
        /* Ocultamos pantalla tras actualizar Yoast */
        function ayudawp_ocultar_pantalla_actualizar_yoast($option) {
        if (is_array($option)) { 
        $option['seen_about'] = true; 
        }
        return $option;
        }
        add_filter('option_wpseo', 'ayudawp_ocultar_pantalla_actualizar_yoast');
        
        
        /* Ocultar icono Yoast en barra admin */
        function ayudawp_ocultar_barra_yoast($wp_admin_bar) {
        $wp_admin_bar->remove_node('wpseo-menu');
        }
        add_action('admin_bar_menu', 'ayudawp_ocultar_barra_yoast', 99);
}

new CCA_Yoast_Remove();


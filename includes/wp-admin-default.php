<?php
function cmk_custom_dashboard_widget() {
     echo '<p>Contacto: <strong>858 958 383</strong>. <a href="mailto:info@closemarketing.es" target="_blank">Correo</a> | <a href="https://www.closemarketing.es/ayuda/" target="_blank">Tutoriales y ayuda</a> | <a href="https://www.facebook.com/closemarketing" target="_blank">Facebook</a></p>';
 }

function cmk_add_custom_dashboard_widget() {
  wp_add_dashboard_widget('cmk_custom_dashboard_widget', 'Contactar con Closemarketing', 'cmk_custom_dashboard_widget');}
  add_action('wp_dashboard_setup', 'cmk_add_custom_dashboard_widget');// disable default dashboard widgets

function cmk_disable_default_dashboard_widgets() {
     remove_meta_box('dashboard_plugins', 'dashboard', 'core');
     remove_meta_box('dashboard_primary', 'dashboard', 'core');
     remove_meta_box('dashboard_secondary', 'dashboard', 'core');     // disable Simple:Press dashboard widget
     remove_meta_box('sf_announce', 'dashboard', 'normal');
} 
add_action('admin_menu', 'cmk_disable_default_dashboard_widgets');

add_filter('admin_footer_text', 'cmk_remove_footer_admin');
function cmk_remove_footer_admin () {
echo "Closemarketing - Dise&ntilde;o y Marketing 2017. Realizado sobre Gestor Contenidos Wordpress.";
}

//Disables image link
function wpb_imagelink_setup() {
  $image_set = get_option( 'image_default_link_type' );
  
  if ($image_set !== 'none') {
    update_option('image_default_link_type', 'none');
  }
}
add_action('admin_init', 'wpb_imagelink_setup', 10);

//Customize TinyMCE
function cmk_change_mce_options( $init ) {
 $init['block_formats'] = 'Párrafo=p;Título 2=h2;Título 3=h3;Título 4=h4;Título 5=h5';
 $init['theme_advanced_disable'] = 'forecolor';
 return $init;
}
add_filter('tiny_mce_before_init', 'cmk_change_mce_options');

//Configurar campos Author
add_filter('user_contactmethods','cmk_remove_profile_fields', 10, 1);
function cmk_remove_profile_fields($contactmethods) {
  // Añade Twitter
  $contactmethods['twitter'] = 'Twitter';
  // Añade Facebook
  $contactmethods['facebook'] = 'Facebook';
  $contactmethods['googleprofile'] = 'Google Profile URL';
  $contactmethods['googleprofileemail'] = 'Google Profile Email';
     unset($contactmethods['aim']);
     unset($contactmethods['jabber']);
     unset($contactmethods['yim']);
     return $contactmethods;
}
//cambiar logo administración y entrada
add_action('login_head', 'cmk_custom_login_logo');
function cmk_custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url('.trailingslashit( plugin_dir_url( __FILE__ ) ).'/logo-login.png) !important; }
        p.galogin-powered {display: none;}
    </style>';
}

//* Remove accents in filenames
function cmk_sanitize_filename_on_upload($filename) {
   $ext = end(explode('.',$filename));
   // Reemplazar todos los caracteres extranos.
   $sanitized = preg_replace('/[^a-zA-Z0-9-_.]/','', substr($filename, 0, -(strlen($ext)+1)));
   // Replace dots inside filename
   $sanitized = str_replace('.','-', $sanitized);
   return strtolower($sanitized.'.'.$ext);
}
add_filter('sanitize_file_name', 'cmk_sanitize_filename_on_upload', 10);

//* Change WordPress Admin bar color
add_action('wp_head', 'cmk_change_bar_color');
add_action('admin_head', 'cmk_change_bar_color');
function cmk_change_bar_color() {

  if($_SERVER['HTTP_HOST']=='localhost') {
    $color = 'red';  //local
  } else {
    $tldcal = explode(".", $_SERVER['HTTP_HOST']);
    $tld = end($tldcal);
    if($tld=='loc'||$tld=='dev') 
      $color = 'red';  //local
    else 
      $color = '#85b83c'; //live
  }
?>
  <style>
  #wpadminbar{
  background: <?php echo $color;?> !important;
  }
  #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu {
    background: <?php echo $color;?> !important;
  }
  #adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover, #adminmenu a:hover, #adminmenu li.menu-top>a:focus,#adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus, #adminmenu li a:focus div.wp-menu-image:before, #adminmenu li.opensub div.wp-menu-image:before, #adminmenu li:hover div.wp-menu-image:before {
    color: <?php echo $color;?> !important;
  }
  </style>
<?php
}


// Quitar menu Editor
add_action('_admin_menu', 'cmk_remove_editor_menu', 1);
function cmk_remove_editor_menu() {
  remove_action('admin_menu', '_add_themes_utility_last', 101);
}


//Sencillez en la elaboración de artículos
add_action('admin_init','cmk_customize_meta_boxes');
function cmk_customize_meta_boxes() {
    $current_user = wp_get_current_user();
     //if current user level is less than 3, remove the postcustom meta box
     if ($current_user->user_level < 3)
          remove_meta_box('postcustom','post','normal');
              remove_meta_box('trackbacksdiv','post','normal');
}


add_action('wp_dashboard_setup', 'cmk_dashboard_widgets');
function cmk_dashboard_widgets() {
     global $wp_meta_boxes;
     // remove unnecessary widgets
     // var_dump( $wp_meta_boxes['dashboard'] ); // use to get all the widget IDs
     unset(
          $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
          $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary']
     );
     // add a custom dashboard widget
     wp_add_dashboard_widget( 'dashboard_custom_feed', 'Noticias de Closemarketing', 'cmk_dashboard_custom_feed_output' ); //add new RSS feed output
}
function cmk_dashboard_custom_feed_output() {
     echo '<div class="rss-widget">';
     wp_widget_rss_output(array(
          'url' => 'http://feeds.feedburner.com/closemarketing',
          'title' => __('Closemarketing News','closemarketing-custom-admin'),
          'items' => 2,
          'show_summary' => 1,
          'show_author' => 0,
          'show_date' => 1
     ));
     echo "</div>";
}

function cmk_rss_post_thumbnail($content) {
    global $post;
    if(has_post_thumbnail($post->ID)) {
        $content = '<p>' . get_the_post_thumbnail($post->ID) .
        '</p>' . get_the_content();
    }

    return $content;
}
add_filter('the_excerpt_rss', 'cwc_rss_post_thumbnail'); add_filter('the_content_feed', 'cmk_rss_post_thumbnail');

//distinto color segun estado de entrada
add_action('admin_footer','cmk_posts_status_color');
function cmk_posts_status_color() {
?>
  <style>
  .status-draft { background: #FCE3F2 !important; }
  .status-pending { background: #87C5D6 !important; }
  .status-publish { /* por defecto */ }
  .status-future { background: #C6EBF5 !important; }
  .status-private { background: #F2D46F; }
  </style>
<?php
}


//Imagenes Miniatura
if (function_exists('add_theme_support'))
{
    add_filter('manage_posts_columns', 'cmk_postsColumns', 5);
    add_filter('manage_pages_columns', 'cmk_postsColumns', 5);

    add_action('manage_posts_custom_column', 'cmk_postsCustomColumn', 5, 2);
    add_action('manage_pages_custom_column', 'cmk_postsCustomColumn', 5, 2);
}
function cmk_postsColumns($columns)
{
    $columns['dj_post_thumbnail'] = __('Thumbnail','closemarketing-custom-admin');
    return ($columns);
}
function cmk_postsCustomColumn($column_name, $id)
{
    if ($column_name === 'dj_post_thumbnail')
        echo the_post_thumbnail(array(125, 80));
}

/**
 * Adding Custom post type counts in 'Right now' Dashboard widget.
 * Acording this changes :
 * - https://core.trac.wordpress.org/ticket/26571
 * - https://core.trac.wordpress.org/ticket/26495
 * now you can't use 'right_now_*' action API to show your custom post type count from your Dashboard.
 * But if you running WP 3.8 or above, you can use 'dashboard_glance_items' instead.
 *
 * @package     Wordpress
 * @subpackage  Hooks
 * @author      Fery Wardiyanto <ferywardiyanto@gmail.com>
 * @link        http://feryardiant.github.com
 * @version     1.0
 */

// Add custom post types count action to WP Dashboard
add_action('dashboard_glance_items', 'cmk_custom_posttype_glance_items');

// Showing all custom posts count
function cmk_custom_posttype_glance_items()
{
	$glances = array();

	$args = array(
		'public'   => true,  // Showing public post types only
		'_builtin' => false  // Except the build-in wp post types (page, post, attachments)
	);

	// Getting your custom post types
	$post_types = get_post_types($args, 'object', 'and');

	foreach ($post_types as $post_type)
	{
		// Counting each post
		$num_posts = wp_count_posts($post_type->name);

		// Number format
		$num   = number_format_i18n($num_posts->publish);
		// Text format
		$text  = _n($post_type->labels->singular_name, $post_type->labels->name, intval($num_posts->publish));

		// If use capable to edit the post type
		if (current_user_can('edit_posts'))
		{
			// Show with link
			$glance = '<a class="'.$post_type->name.'-count" href="'.admin_url('edit.php?post_type='.$post_type->name).'">'.$num.' '.$text.'</a>';
		}
		else
		{
			// Show without link
			$glance = '<span class="'.$post_type->name.'-count">'.$num.' '.$text.'</span>';
		}

		// Save in array
		$glances[] = $glance;
	}

	// return them
	return $glances;
}

//* Allow SVG
function cmk_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cmk_mime_types');

<?php
/*
Library: Resize images before upload
*/

class CMK_Resize_Images_Before_Upload {
        
    /**
     * The constructor 
     * @return void
     */
    function __construct() {
    
        if ( ! defined( 'CMK_RESIZE_WIDTH' ) ) define( 'CMK_RESIZE_WIDTH', $this->get_resize_width() );
        if ( ! defined( 'CMK_RESIZE_HEIGHT' ) ) define( 'CMK_RESIZE_HEIGHT', $this->get_resize_height()  ); 
        if ( ! defined( 'CMK_RESIZE_QUALITY' ) ) define( 'CMK_RESIZE_QUALITY', $this->get_resize_quality() ); 
        if ( ! defined( 'CMK_MAX_UPLOAD_SIZE' ) ) define( 'CMK_MAX_UPLOAD_SIZE', $this->get_max_upload() ); 
        if ( ! defined( 'CMK_FRONTEND_JS' ) ) define( 'CMK_FRONTEND_JS', false ); 
        
        // store the flash warning seen variable as a session
        if ( isset($_GET['you_toldmeabout_flash']) ){
            $_SESSION['you_toldmeabout_flash'] = "donttellmeagain";
        }
    
        add_filter('plupload_init', array($this,'cmk_plupload_init'),20);
        add_filter('plupload_default_settings', array($this,'cmk_plupload_default_settings'),20);
        add_filter('plupload_default_params', array($this,'cmk_plupload_default_settings'),20); 
    
        
        add_action('post-upload-ui', array($this,'cmk_show_note'),10);
        add_action('admin_footer',  array($this,'cmk_print_js'),10); //javascript output is only for the old uploader, new uploader uses plupload_default_settings/plupload_init
        if ( CMK_FRONTEND_JS == true ) {
            add_action('CMK_footer',  array($this,'cmk_print_js'),10); // it's possible to have the media manager on the front end too, but you should ask for it :)
        }
        add_action('admin_init', array($this,'admin_init_settings'),20);
    
   } //construct

    function get_max_upload() {
 
        if ( function_exists('wp_max_upload_size') ){
            return wp_max_upload_size();
        } else{
            return ini_get('upload_max_filesize') . 'b';
        }
 
    }
    
    function cmk_show_note(){
        echo "<p> " . __('Images will be resized to the large image dimensions, as specified in your media settings','closemarketing-custom-admin') . "</p>";
    }
    
    
    function cmk_print_js(){
        $quality =  $this->get_resize_quality() ;
    ?>
    <script type="text/javascript"> 
        
        jQuery(window).load(function($){
        
                try{ 
                    if (uploader =='undefined' );
                    
                            uploader.settings.max_file_size = '200097152b';
                            uploader.settings['resize'] = { width: <?php echo CMK_RESIZE_WIDTH; ?>, height: <?php echo CMK_RESIZE_HEIGHT; ?>, quality: <?php echo CMK_RESIZE_QUALITY; ?>  };
                    
                }catch(err){ }
                    
                    
                
        });

    </script>
    <?php 
        
    if ( $this->incompatible_browser() && !isset($_SESSION['you_toldmeabout_flash']) ){
    
        ?>
            <script type="text/javascript">
                
                var hasFlash = false;
                try {
                  var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
                  if(fo) hasFlash = true;
                }catch(e){
                  if(navigator.mimeTypes ["application/x-shockwave-flash"] != undefined) hasFlash = true;
                }

                if(!hasFlash){
                
                alert('<?php echo __('Automatic resizing of images is not possible with your web browser (the Adobe Flash plug-in is required for otherwise incompatible browsers). \n\nEither install Flash or use a more suitable web browser (Firefox 3.5+, Chrome) ','closemarketing-custom-admin'); ?>');
                location.href = "<?php echo add_query_arg('you_toldmeabout_flash','donttellmeagain');?>";
        
                }
        
            </script>
        <?php
    
    }
        
        
    }

    function cmk_plupload_init($plupload_init_array){
        
        //remove max file size
        unset($plupload_init_array['max_file_size']);
        
        
         //change runtime to flash for non firefox/chrome browsers, unless this action is cancelled by the cmk_cancel_force_flash setting
         if (!get_option('cmk_cancel_force_flash')){
        
            // if incompatible and we havent told them about flash being missing then lets use flash runtime -
            // we can't be sure if they have flash though - and if they don't we'll load this again after telling them about no resize/flash, once told we will just roll without flash, no resize possible
            if ( $this->incompatible_browser() ){
                $plupload_init_array['runtimes'] = "flash"; // 'runtimes' => 'html5,silverlight,flash,html4',
            }
        
         }

            return $plupload_init_array;
    }
    
    function cmk_plupload_default_settings($plupload_setting_array){
        
        //remove max file size by makinb it huge
        $plupload_setting_array['max_file_size'] = "200097152b";
        
        $plupload_setting_array['resize'] = array("width" => CMK_RESIZE_WIDTH, "height" => CMK_RESIZE_HEIGHT, "quality" => CMK_RESIZE_QUALITY);
        
        
         //change runtime to flash for non firefox/chrome browsers, unless this action is cancelled by the cmk_cancel_force_flash setting
         if (!get_option('cmk_cancel_force_flash')){
        
            // if incompatible and we havent told them about flash being missing then lets use flash runtime -
            // we can't be sure if they have flash though - and if they don't we'll load this again after telling them about no resize/flash, once told we will just roll without flash, no resize possible
            if ( $this->incompatible_browser() ){
                $plupload_setting_array['runtimes'] = "flash"; // 'runtimes' => 'html5,silverlight,flash,html4',
            }
        
         }

            return $plupload_setting_array;
    }
    
    // Register and define the settings
    function admin_init_settings(){
        
        // create settings section
        add_settings_section('cmk_media_settings_section',
                'Resize before upload',
                array($this,'media_settings_section_callback_function'),
                'media');
        
        // settings, put it in our new section
        add_settings_field('cmk_resize_quality',
            'Resize quality',
            array($this,'resize_quality_callback_function'),
            'media',
            'cmk_media_settings_section');
            
        // settings, put it in our new section
        add_settings_field('cmk_resize_height',
            'Resize height',
            array($this,'resize_height_callback_function'),
            'media',
            'cmk_media_settings_section');
        // settings, put it in our new section
        add_settings_field('cmk_resize_width',
            'Resize width',
            array($this,'resize_width_callback_function'),
            'media',
            'cmk_media_settings_section');
         
        
        add_settings_field('cmk_cancel_force_flash', __('Disable force flash','closemarketing-custom-admin'), array($this,'cancel_force_flash_callback_function'), 'media', 'cmk_media_settings_section');
        
        // Register our setting so that $_POST handling is done for us and
        register_setting('media', 'cmk_resize_quality', array($this,'resize_quality_validate_input') );
        register_setting('media', 'cmk_resize_height' );
        register_setting('media', 'cmk_resize_width' );
        register_setting('media', 'cmk_cancel_force_flash');
    }
    
    function media_settings_section_callback_function(){
        //output nothing at this stage.
    }
    
    function resize_quality_callback_function(){
        echo '<input name="cmk_resize_quality" id="cmk_resize_quality" type="text" value="'. $this->get_resize_quality() .'" class="small-text" /> <em class="description">'.__('1 - 100   (a low quality value will result in a considerably smaller file size and lower quality images - 80 is optimum)','closemarketing-custom-admin').'</em>';
    }
    
    function resize_width_callback_function(){
        echo '<input name="cmk_resize_width" id="cmk_resize_width" type="text" value="'. $this->get_resize_width() .'" class="small-text" /> <em class="description">'.__('you can override this by setting CMK_RESIZE_WIDTH in your wp-config file','closemarketing-custom-admin').'</em>';
    }
    
    function resize_height_callback_function(){
        echo '<input name="cmk_resize_height" id="cmk_resize_height" type="text" value="'. $this->get_resize_height() .'" class="small-text" /> <em class="description">'.__('you can override this by setting CMK_RESIZE_HEIGHT in your wp-config file','closemarketing-custom-admin').'</em>';
    }
    
    function cancel_force_flash_callback_function(){
        echo '<input name="cmk_cancel_force_flash" id="cmk_cancel_force_flash" type="checkbox" value="1" ' . checked( 1, get_option('cmk_cancel_force_flash'), false ) . ' class="small-text" /> <em class="description">'.__('Do not force the Flash uploader for non Chrome/Firefox browsers.','closemarketing-custom-admin').'</em>';
    }
    
    function incompatible_browser(){
        
        if (! preg_match("#Firefox|Chrome|iPad|iPhone|Opera|Safari#", $_SERVER['HTTP_USER_AGENT']) ){
            return true;
        }
        
        return false;
    }
    
    function resize_quality_validate_input($quality){
        
        $quality = absint( $quality ); //validate
        
        if ($quality > 0 && $quality < 101){
            return $quality;
        }else{
            add_settings_error(
                'cmk_resize_quality',           // setting title
                'cmk_resize_quality_error',            // error ID
                __('Invalid resize quality, a value between 1-100 is required -  so a default value of 80 has been set.','closemarketing-custom-admin'),   // error message
                'error'                        // type of message
            );
            return 80;
        }
        
    }
    
    function get_resize_quality(){
        
        //get quality out of settings
        $quality = get_option('cmk_resize_quality');
        
        //return quality or default setting 
        if ($quality > 0 && $quality < 101){
            return $quality;
        }else{
            return 80;
        }
    }
    
    function get_resize_width(){
        
        //get quality out of settings
        $width = get_option('cmk_resize_width');

        //return width or false 
        if ($width){
            return $width;
        }else{
            return get_option('large_size_w');
        }
    }
    
    function get_resize_height(){
        
        //get quality out of settings
        $height = get_option('cmk_resize_height');
        
        //return width or false 
        if ($height){
            return $height;
        }else{
            return get_option('large_size_h');
        }
    }

}

/**
 * Register the plugin - unless we have told them about a flash problem in which case this plugin is useless
 */
if ( !isset($_SESSION['you_toldmeabout_flash']) ){
    add_action("init", create_function('', 'new CMK_Resize_Images_Before_Upload();'));
}

// Ending PHP tag is not needed, it will only increase the risk of white space 
// being sent to the browser before any HTTP headers.
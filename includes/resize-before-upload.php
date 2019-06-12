<?php
/**
 * Library to crop images
 *
 * @package    WordPress
 * @author     David Perez <david@closemarketing.es>
 * @copyright  2019 Closemarketing
 * @version    1.0
 */
class CMK_Resize_Images_Before_Upload {

	/**
	 * The constructor
	 *
	 * @return void
	 */
	public function __construct() {

		// store the flash warning seen variable as a session.
		if ( isset( $_GET['you_toldmeabout_flash'] ) ) {
			$_SESSION['you_toldmeabout_flash'] = 'donttellmeagain';
		}

		add_filter( 'plupload_init', array( $this, 'cmk_plupload_init' ), 20 );
		add_filter( 'plupload_default_settings', array( $this, 'cmk_plupload_default_settings' ), 20 );
		add_filter( 'plupload_default_params', array( $this, 'cmk_plupload_default_settings' ), 20 );

		add_action( 'post-upload-ui', array( $this, 'cmk_show_note' ), 10 );
		add_action( 'admin_footer', array( $this, 'cmk_print_js' ), 10 );

	} //construct

	function get_max_upload() {

		if ( function_exists( 'wp_max_upload_size' ) ) {
			return wp_max_upload_size();
		} else {
			return ini_get( 'upload_max_filesize' ) . 'b';
		}

	}

	function cmk_show_note() {
		echo '<p> ' . __( 'Images will be resized to the large image dimensions, as specified in your media settings', 'closemarketing-custom-admin' ) . '</p>';
	}


	public function cmk_print_js() {
		$quality = CMK_RESIZE_QUALITY;
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

		if ( $this->incompatible_browser() && ! isset( $_SESSION['you_toldmeabout_flash'] ) ) {
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
				
				alert('<?php echo __( 'Automatic resizing of images is not possible with your web browser (the Adobe Flash plug-in is required for otherwise incompatible browsers). \n\nEither install Flash or use a more suitable web browser (Firefox 3.5+, Chrome) ', 'closemarketing-custom-admin' ); ?>');
				location.href = "<?php echo add_query_arg( 'you_toldmeabout_flash', 'donttellmeagain' ); ?>";
		
				}
		
			</script>
			<?php

		}

	}

	function cmk_plupload_init( $plupload_init_array ) {

		// remove max file size
		unset( $plupload_init_array['max_file_size'] );

		 // change runtime to flash for non firefox/chrome browsers, unless this action is cancelled by the cmk_cancel_force_flash setting
		if ( ! get_option( 'cmk_cancel_force_flash' ) ) {

			// if incompatible and we havent told them about flash being missing then lets use flash runtime -
			// we can't be sure if they have flash though - and if they don't we'll load this again after telling them about no resize/flash, once told we will just roll without flash, no resize possible
			if ( $this->incompatible_browser() ) {
				$plupload_init_array['runtimes'] = 'flash'; // 'runtimes' => 'html5,silverlight,flash,html4',
			}
		}

			return $plupload_init_array;
	}

	function cmk_plupload_default_settings( $plupload_setting_array ) {

		// remove max file size by makinb it huge
		$plupload_setting_array['max_file_size'] = '200097152b';

		$plupload_setting_array['resize'] = array(
			'width'   => CMK_RESIZE_WIDTH,
			'height'  => CMK_RESIZE_HEIGHT,
			'quality' => CMK_RESIZE_QUALITY,
		);

		 // change runtime to flash for non firefox/chrome browsers, unless this action is cancelled by the cmk_cancel_force_flash setting
		if ( ! get_option( 'cmk_cancel_force_flash' ) ) {

			// if incompatible and we havent told them about flash being missing then lets use flash runtime -
			// we can't be sure if they have flash though - and if they don't we'll load this again after telling them about no resize/flash, once told we will just roll without flash, no resize possible
			if ( $this->incompatible_browser() ) {
				$plupload_setting_array['runtimes'] = 'flash'; // 'runtimes' => 'html5,silverlight,flash,html4',
			}
		}

			return $plupload_setting_array;
	}

	function incompatible_browser() {
		if ( ! preg_match( '#Firefox|Chrome|iPad|iPhone|Opera|Safari#', $_SERVER['HTTP_USER_AGENT'] ) ) {
			return true;
		}
		return false;
	}

	function resize_quality_validate_input( $quality ) {

		$quality = absint( $quality ); // validate

		if ( $quality > 0 && $quality < 101 ) {
			return $quality;
		} else {
			add_settings_error(
				'cmk_resize_quality',           // setting title
				'cmk_resize_quality_error',            // error ID
				__( 'Invalid resize quality, a value between 1-100 is required -  so a default value of 80 has been set.', 'closemarketing-custom-admin' ),   // error message
				'error'                        // type of message
			);
			return 80;
		}

	}

}

/**
 * Register the plugin 
 */
if ( is_admin() ) {
	global $resize_images;
	$resize_images = new CMK_Resize_Images_Before_Upload();
}

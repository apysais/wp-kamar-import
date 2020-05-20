<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Sync To.
 */
class WKI_SyncTo {
  /**
	 * instance of this class
	 *
	 * @since 1.0.0
	 * @access protected
	 * @var	null
	 */
	protected static $instance = null;

	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		/*
		 * - Uncomment following lines if the admin class should only be available for super admins
		 */
		/* if( ! is_super_admin() ) {
			return;
		} */

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function __construct()	{
	}

  public function sync_to() {
    if ( $file_json == '' ) {
			$files = WKI_GetJsonFile::get_instance()->getFilesInDir();
		} else {
			$files = pathinfo( WP_KAMAR_JSON_DIRECTORY . $file_json );
		}

		if ( !empty( $files ) ) {
			foreach ( $files as $file ) {
				$json_file = '';
				$file_extension = isset( $file['filename_ext'] ) ? $file['filename_ext'] : $files['extension'];
				if ( $file_extension == 'json' ) {
					$file_path = isset( $file['path'] ) ? $file['path'] : $files['dirname'];
					$file_name = isset( $file['filename'] ) ? $file['filename'] : $files['basename'];
					$json_file = $file_path .'/' . $file_name;

					$get_content_json_obj = new WKI_GetJsonContent;
					$json_data = $get_content_json_obj->getContent($json_file);
					if ( isset($json_data['SMSDirectoryData']) ) {
						$sync = $json_data['SMSDirectoryData']['sync'];
						$sync_to = 'WKI_' . ucfirst($sync);

						if ( class_exists($sync_to) ) {
							$sync_obj = new $sync_to($json_data);
						}

					}

				}// if ext json
			}//foreach $files
		}//if !empty $files
  }

}//KIE_GetFiles

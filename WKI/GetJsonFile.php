<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Get Files in directory.
 */
class WKI_GetJsonFile {
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

  /**
   * Get the files in the directory.
   */
  public function getFilesInDir( $dir = WP_KAMAR_JSON_DIRECTORY ) {
    $fileSystemIterator = new FilesystemIterator( $dir );

    $entries = [];

    foreach ($fileSystemIterator as $fileInfo){
        $entries[$fileInfo->getMTime()] = [
          'filename' => $fileInfo->getFilename(),
          'filename_ext' => $fileInfo->getExtension(),
          'path' => $fileInfo->getPath()
        ];
    }
		krsort($entries);
    return $entries;
  }

  public function rename( $current_name, $new_name ) {
    rename( $current_name,  $new_name );
  }

  public function move( $current_folder, $new_folder ) {
    $this->rename( $current_folder, $new_folder );
  }

  public function delete( $file ) {
    return unlink($file);
  }

  /**
   * This is used for single file json to get.
   */
  public function getSingle( $file_json, $kamar_dir = WP_KAMAR_JSON_DIRECTORY ) {
    $path = $kamar_dir;
    $path_file = $path . $file_json;

  	if ( file_exists( $path_file ) ) {
  		return $path_file;
  	}

  	return false;

  }

}//KIE_GetFiles

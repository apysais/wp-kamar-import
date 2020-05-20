<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * Get Content in directory.
 */
class WKI_GetJsonContent {
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
   * Get the file json contents.
   */
  public function getContent( $file_json ) {
    $json_kamar = false;

    $string = file_get_contents( $file_json );

    if ( $string ) {
      $json_kamar = json_decode( $string, true );
    }

    return $json_kamar;
  }

}//KIE_GetFiles

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_Calendar {
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

	public function __construct( $json_data )	{
		return $this->init( $json_data );
  }

	public function init($json_data) {
		$data = [];
		$ret = false;

		if ( isset( $json_data['SMSDirectoryData'] ) && isset( $json_data['SMSDirectoryData']['calendars'] ) ) {

			if ( count( $json_data['SMSDirectoryData']['calendars']['events'] ) >= 1) {
				$json_data = $json_data['SMSDirectoryData']['calendars']['events'];
				WKI_Import_Calendar::get_instance()->init( ['data' => $json_data] );
				$ret = true;
			}

		}

		return $ret;
	}

}//WKI_Notices

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_Import_Part_Staff {
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

	public function init( $args = [] ) {
		$datas = [];
		if ( isset($args['data']) ) {
			$datas = $args['data'];
			$ret = $this->import($datas);
			return true;
		}
		return false;
	}

	public function import( $datas = [] ) {
		$ret = [];
		if ( count($datas) >= 1 ) {

		}// !empty($datas)
		return $ret;
	}//import

}//WKI_Notices

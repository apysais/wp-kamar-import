<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_Shortcode_Notices {
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
    add_shortcode( 'phs_show_notice_meeting', [ $this, 'init' ] );
  }

  public function init() {

    //$current_date = date( 'Ymd', current_time( 'timestamp', 0 ) );
    $current_date = \Carbon\Carbon::parse( current_time( 'timestamp', 0 ) );
    if ( isset( $_GET['notice_date'] ) ) {
      $get_current_date = sanitize_text_field( $_GET['notice_date'] );
      $current_date = \Carbon\Carbon::parse( $get_current_date );
    }

		//pagination
		$yesterday_date = \Carbon\Carbon::parse($get_current_date)->subDays(1);
		$tomorrow_date = \Carbon\Carbon::parse($current_date)->addDays(1);
		//pagination

    $notices = WKI_DB_Notices::get_instance()->get_by_date([
      'current_date' => $current_date->format('Ymd')
    ]);
    $meetings = WKI_DB_Meetings::get_instance()->get_by_date([
      'current_date' => $current_date->format('Ymd')
    ]);

		$data = [
			'notices' => $notices,
			'meetings' => $meetings,
			'current_date' => $current_date->format('Y-m-d'),
			'current_date_uri' => '?notice_date=' . $current_date->format('Y-m-d'),
			'yesterday_date' => $yesterday_date->format('Y-m-d'),
			'yesterday_date_uri' => '?notice_date=' . $yesterday_date->format('Y-m-d'),
			'tomorrow_date' => $tomorrow_date->format('Y-m-d'),
			'tomorrow_date_uri' => '?notice_date=' . $tomorrow_date->format('Y-m-d'),
		];

		$template_str = 'template-phs_show_notice_meeting.php';
		$check_in_theme = WKI_View::get_instance()->get_in_theme( $template_str );

		ob_start();

		if ( ! $check_in_theme ) {
			WKI_View::get_instance()->public_partials( $template_str , $data);
		} else {
			extract($data);
			include( locate_template( $template_str ) );
		}

		return ob_get_clean();
  }

}//WKI_Notices

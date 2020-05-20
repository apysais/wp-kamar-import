<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_Import_Notices {
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

	public function import( $args = [] ) {
    $datas = [];
    if ( isset($args['data']) ) {
      $datas = $args['data'];
      foreach( $datas as $data ) {

        if ( $data['PublishWeb'] == 1) {
          $post_data = [
            'post_title'   => esc_html($data['Subject']),
            'post_content' => $data['Body'],
            'post_type'    => 'wki_notices',
            'post_status'  => 'publish',
            'meta_input'   => [
              'wki_notice_level'        => $data['Level'],
              'wki_notice_uuid'         => $data['uuid'],
              'wki_notice_date_start'   => $data['DateStart'],
              'wki_notice_date_finish'  => $data['DateFinish'],
              'wki_notice_teacher'      => $data['Teacher'],
            ],
          ];

          $check_notice = get_posts([
  					'post_type'      => 'wki_notices',
  					'posts_per_page' => 1,
  					'meta_key'       => 'wki_notice_uuid',
  					'meta_value'     => $data['uuid'],
  				]);

          if ( !$check_notice ) {
            //insert
            wp_insert_post($post_data);
          } else {
            //update
            $post_data['ID'] = $check_notice[0]->ID;
            wp_update_post( $post_data );
          }

        }//if PublishWeb

      }//foreach datas
    }//if data
	}//import

}//WKI_Notices

<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_Import_Meetings {
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
			//remove data that is removed in json
			WKI_DB_Notices::get_instance()->remove_notices($datas);
		}
	}

	public function import( $datas = [] ) {
		$ret = [];
		if ( count($datas) >= 1 ) {
			foreach( $datas as $data ) {
        if ( $data['PublishWeb'] == 1
				 		&& ( $data['MeetingTime'] != '' || $data['MeetingPlace'] != '' )
				) {
          $post_data = [
            'post_title'   => esc_html($data['Subject']),
            'post_content' => $data['Body'],
            'post_type'    => 'wki_meetings',
            'post_status'  => 'publish',
            'meta_input'   => [
              'wki_meetings_level'        => $data['Level'],
              'wki_meetings_uuid'         => $data['uuid'],
              'wki_meetings_date_start'   => $data['DateStart'],
              'wki_meetings_date_finish'  => $data['DateFinish'],
              'wki_meetings_teacher'      => $data['Teacher'],
              'wki_meetings_date'         => $data['MeetingDate'],
              'wki_meetings_time'         => $data['MeetingTime'],
              'wki_meetings_place'        => $data['MeetingPlace'],
            ],
          ];

          $check_meetings = get_posts([
  					'post_type'      => 'wki_meetings',
  					'posts_per_page' => 1,
  					'meta_key'       => 'wki_meetings_uuid',
  					'meta_value'     => $data['uuid'],
  				]);

          if ( !$check_meetings ) {
            //insert
            $insert_id = wp_insert_post($post_data);
						$ret['insert']['mettings'][] = $insert_id;
          } else {
            //update
						$post_id = $check_notice[0]->ID;
						$post_data['ID'] = $post_id;
            wp_update_post( $post_data );
						$ret['update']['mettings'][] = $post_id;
          }
        }//if PublishWeb
      }//foreach datas
		}// !empty($datas)
		return $ret;
	}//import

}//WKI_Notices

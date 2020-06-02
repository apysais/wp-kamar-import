<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_DB_Meetings {
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

	public function get_by_date( $args = [] ) {
		$current_date = isset( $args['current_date'] ) ? $args['current_date'] : false;
		$data = [];
		if ( $current_date ) {
			$args = array(
	      'post_limits' => -1,
	      'post_type' => 'wki_meetings',
	      'meta_key' => 'wki_meetings_date_start',
	      'meta_query' => array(
	          'relation' => 'AND',
	              array(
	                  'key' => 'wki_meetings_date_start',
	                  'value' => $current_date,
	                  'compare' => '<',
	                  'type' => 'NUMERIC',
	              ),
	              array(
	                  'key' => 'wki_meetings_date_finish',
	                  'value' => $current_date,
	                  'compare' => '>=',
	                  'type' => 'NUMERIC',
	              )
	          ),
	      'order_by' => 'meta_value_num',
	      'order' => 'ASC',
	    );
			//wki_dd($args);
	    $query = new WP_Query( $args );
	    if ( $query->have_posts() ) {
				//wki_dd($query->posts);
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_id = get_the_ID();
					$get_date = get_post_meta( $post_id, 'wki_meetings_date', true );

					if ( $get_date ) {
						$parse_date = \Carbon\Carbon::parse($get_date);
					}

					$data[] = [
						'title' => get_the_title(),
						'content' =>get_the_content(),
						'level' => get_post_meta( $post_id, 'wki_meetings_level', true ),
						'location' => get_post_meta( $post_id, 'wki_meetings_place', true ),
						'time' => get_post_meta( $post_id, 'wki_meetings_time', true ),
						'raw_date' => get_post_meta( $post_id, 'wki_meetings_date', true ),
						'format_date' => $parse_date->format('D d F'),
						'staff' => get_post_meta( $post_id, 'wki_meetings_teacher', true),
					];
				}
				$data = json_decode( json_encode( $data ) );
	    }
	    wp_reset_postdata();
		}
		return $data;
	}

	public function get_all() {
    $data = [];
		$check_meetings = get_posts([
			'post_type'      => 'wki_meetings',
			'posts_per_page' => -1,
			'meta_key'       => 'wki_meetings_uuid'
		]);

    if ( $check_meetings ) {
      foreach( $check_meetings as $check_meeting ) {
  			$uuid = get_post_meta($check_meeting->ID, 'wki_meetings_uuid', 1);
  			$data[$uuid] = [
  				'uuid' => $uuid,
  				'name' => $check_meeting->post_title
  			];
  		}
    }

		return $data;
  }

  public function get_data_uuids( $datas ) {
    $uuid = [];
    if ( count( $datas ) >= 1 ) {
      foreach( $datas as $data ) {
        $uuid[] = $data['uuid'];
      }
    }
    return $uuid;
  }

  public function remove_meetings( $data ) {

		$meetings = $this->get_all();

    $uuids = $this->get_data_uuids( $data );

		$key_kamars   = $uuids;
		$key_meetings = array_keys( $meetings );
		$results      = array_diff( $key_meetings , $key_kamars );

		if ( count( $results ) >= 1 ) {
			$args = array(
			    'post_type'  			=> 'wki_meetings',
					'posts_per_page' 	=> -1,
			    'meta_query' 			=> array(
			        array(
			            'key' => 'wki_meetings_uuid',
			            'value'   => $results,
			        ),
			    ),
			);
			$query = new WP_Query( $args );

			if ( $query->have_posts() ) {
				$posts = $query->get_posts();
				foreach( $posts as $post ) {
					$post_id = $post->ID;
					wp_delete_post( $post_id );
				}
			}
			wp_reset_postdata();
		}
  }

  public function purge() {

  }

}//WKI_DB_Meetings

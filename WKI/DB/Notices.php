<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_DB_Notices {
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
		if ( $current_date ) {
			$args = array(
	      'post_limits' => -1,
	      'post_type' => 'wki_notices',
	      'meta_key' => 'wki_notice_date_start',
	      'meta_query' => array(
	        'relation' => 'AND',
	            array(
	                'key' => 'wki_notice_date_start',
	                'value' => $current_date,
	                'compare' => '<',
	                'type' => 'NUMERIC',
	            ),
	            array(
	                'key' => 'wki_notice_date_finish',
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
				while ( $query->have_posts() ) {
					$query->the_post();
					$post_id = get_the_ID();
					$data[] = [
						'title' => get_the_title(),
						'content' =>get_the_content(),
						'level' => get_post_meta( $post_id, 'wki_notice_level', true ),
						'staff' => get_post_meta( $post_id, 'wki_notice_teacher', true),
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
		$check_notices = get_posts([
			'post_type'      => 'wki_notices',
			'posts_per_page' => -1,
			'meta_key'       => 'wki_notice_uuid'
		]);

    if ( $check_notices ) {
      foreach( $check_notices as $check_notice ) {
  			$uuid = get_post_meta($check_notice->ID, 'wki_notice_uuid', 1);
  			$data[$uuid] = [
  				'uuid' => $uuid,
  				'name' => $check_notice->post_title
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

  public function remove_notices($data) {

		$notices = $this->get_all();

    $uuids = $this->get_data_uuids( $data );

		$key_kamars   = $uuids;
		$key_notices  = array_keys( $notices );
		$results      = array_diff( $key_notices , $key_kamars );
		if ( count( $results ) >= 1 ) {
			$args = array(
			    'post_type'  => 'wki_notices',
					'posts_per_page' => -1,
			    'meta_query' => array(
			        array(
			            'key' => 'wki_notice_uuid',
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

}//WKI_Notices

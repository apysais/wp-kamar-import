<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_DB_Calendar {
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

  public function __construct()	{ }

	public function get_all() {
    $data = [];
		$check_events = get_posts([
			'post_type'      => 'event',
			'posts_per_page' => -1,
			'meta_key'       => 'kamar_uuid'
		]);

    if ( $check_events ) {
      foreach( $check_events as $check_event ) {
  			$uuid = get_post_meta( $check_event->ID , 'kamar_uuid' , 1 );
  			$data[ $uuid ] = [
  				'uuid' => $uuid,
  				'name' => $check_event->post_title
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

  public function remove_data($data) {

		$notices = $this->get_all();

    $uuids = $this->get_data_uuids( $data );

		$key_kamars   = $uuids;
		$key_notices  = array_keys( $notices );
		$results      = array_diff( $key_notices , $key_kamars );

		if ( count( $results ) >= 1 ) {
			$args = array(
		    'post_type'  			=> 'event',
				'posts_per_page' 	=> -1,
				'meta_query' 			=> [
					[
						'key' => 'kamar_uuid',
						'value'   => $results,
					],
				],
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

}//WKI_Notices

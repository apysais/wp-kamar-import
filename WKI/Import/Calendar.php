<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_Import_Calendar {
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

	public function init( $args = [] ) {
		$datas = [];
		if ( isset($args['data']) ) {
			$datas = $args['data'];
			//check if eo events is active
			if ( defined( 'EVENT_ORGANISER_VER' ) ) {
				$ret = $this->import($datas);
				//remove data that is removed in json
				WKI_DB_Calendar::get_instance()->remove_data( $datas );
				return true;
			}
		}
		return false;
	}

	public function set_category( $kamar_category, $event_object ) {
		$event_category = '';
		// Purple – Special Character
		// Green - Sport
		// Red – Formal uniform days
		// Black – General
		if ( $kamar_category == 'Purple' ) {
			$event_category = 'Special Character';
		} elseif ( $kamar_category == 'Green' ) {
			$event_category = 'Sport';
		} elseif ( $kamar_category == 'Red' ) {
			$event_category = 'Formal uniform days';
		} elseif ( $kamar_category == 'Black' ) {
			$event_category = 'General';
		}

		$tag = term_exists( $event_category, 'event-category' );

		if ( $tag ) {
			if ( $event_category != '' ) {
				$event_cat_term = get_term_by( 'name', $event_category, 'event-category' );
				wp_set_post_terms( $event_object, $event_cat_term->term_id, 'event-category' );
			}
		} else {
			if ( $event_category != '' ) {
				$term = wp_insert_term(
						$event_category,   // the term
						'event-category' // the taxonomy
				);
				if ( !is_wp_error( $term ) && isset( $term[ 'term_id' ] )	) {
					wp_set_post_terms( $event_object, $term['term_id'], 'event-category' );
				}
			}
		}
	}

	public function import( $datas = [] ) {
		$ret = [];
		$ret_data = [];
		$event_data = [];
		if ( count( $datas ) >= 1 ) {

			foreach ( $datas as $k => $v ) {
				if ( $v['published'] ) {

					if ( isset( $v['datestart'] ) ) {
						$date_start = $v['datestart'];
						$time_start = '235900';
						$data_date_start = $date_start .' '. $time_start;
						if ( isset( $v['timestart'] ) ) {
							$data_date_start = $date_start .' '. $v['timestart'];
						}
						$event_data['start'] = new DateTime( $data_date_start, eo_get_blog_timezone() );
					}

					$time_end = '235900';
					if ( isset( $v['timefinish'] ) ) {
						$time_end = $v['timefinish'];
					}

					if ( isset( $v['datefinish'] ) ) {
						$data_date_end = $v['datefinish'] .' '. $time_end;
						$event_data['end'] = new DateTime( $data_date_end, eo_get_blog_timezone() );
					} else {
						$data_date_end = $v['datestart'] .' '. $time_end;
						$event_data['end'] = $data_date_end;
					}

					$event_data['all_day'] = 1;

					if ( isset( $v['location'] ) ) {
							$event_data['venue'] = $v['location'];
					}

					$description = $v['summary'];
					if ( isset($v['description']) ) {
						$description = $v['description'];
					}

					$post_data = array(
					 'post_name'		=>	sanitize_title($v['summary']),
					 'post_title'		=>	$v['summary'],
					 'post_content'	=>	$description,
					);

					$check_event = get_posts([
						'post_type'      => 'event',
						'posts_per_page' => 1,
						'meta_key'       => 'kamar_uuid',
						'meta_value'     => $v['uuid'],
					]);

					if ( !$check_event ) {
						$e = eo_insert_event( $post_data , $event_data );
						wp_publish_post( $e );
						update_post_meta( $e, 'kamar_uuid', $v['uuid']);

						$this->set_category( $v['colour'], $e );

						if ( $e ) {
							$update_post = array(
								'ID'          => $e,
								'post_name'		=>	sanitize_title($v['summary']),
							);
							// Update the post into the database
							wp_update_post( $update_post );
						}//if e event update

					} else {
						//update here
						if ( $check_event ) {
							$post_id = $check_event[0]->ID;

							$e = eo_update_event( $post_id, $event_data, $post_data  );
							$this->set_category( $v['colour'], $e );

							if ( $e ) {
								$update_post = array(
									'ID'          => $post_id,
									'post_name'		=>	sanitize_title($v['summary']),
								);
								// Update the post into the database
								wp_update_post( $update_post );
							}//if e event update
						}//if check event update
					}//if checked event insert
				}//if published
			}//foreach
		}// !empty($datas)
		return $ret_data;
	}//import

}//WKI_Import_Calendar

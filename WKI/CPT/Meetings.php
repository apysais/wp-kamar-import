<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_CPT_Meetings {
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
    $labels = array(
  		'name'               => _x( 'Meetings', 'post type general name' ),
  		'singular_name'      => _x( 'Meetings', 'post type singular name' ),
  		'menu_name'          => _x( 'Meetings', 'admin menu' ),
  		'name_admin_bar'     => _x( 'Meetings', 'add new on admin bar' ),
  		'add_new'            => _x( 'Add New', 'Meetings' ),
  		'add_new_item'       => __( 'Add New Meetings' ),
  		'new_item'           => __( 'New Meetings' ),
  		'edit_item'          => __( 'Edit Meetings' ),
  		'view_item'          => __( 'View Meetings' ),
  		'all_items'          => __( 'All Meetings' ),
  		'search_items'       => __( 'Search Meetings' ),
  		'parent_item_colon'  => __( 'Parent Meetings:' ),
  		'not_found'          => __( 'No Meetings found.' ),
  		'not_found_in_trash' => __( 'No Meetings found in Trash.' )
  	);

  	$args = array(
  		'labels'             => $labels,
  		'description'        => __( 'Description.' ),
  		'public'             => true,
  		'publicly_queryable' => true,
  		'show_ui'            => true,
  		'show_in_menu'       => true,
  		'query_var'          => true,
  		'rewrite'            => array( 'slug' => 'wki-meetings' ),
  		'capability_type'    => 'post',
  		'has_archive'        => true,
  		'hierarchical'       => false,
  		'menu_position'      => null,
  		'supports'           => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
  	);

  	register_post_type( 'wki_meetings', $args );
  }

}//WKI_Notices

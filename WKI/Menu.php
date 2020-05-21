<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
/**
 * get the notices.
 */
class WKI_Menu {
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

	public function __construct( )	{
    add_action( 'admin_menu', [$this, 'init'] );

  }

	public function init() {
    add_menu_page(
        __( 'Kamar Import' ),
        'Kamar Import',
        'manage_options',
        'kamar-import-notice',
        [$this, 'show'],
        '',
        6
    );
	}

  public function show() {
    $files = WKI_GetJsonFile::get_instance()->getFilesInDir();
    ?>
    <div class="wrap">
        <h1><?php _e( 'Files to import.', 'textdomain' ); ?></h1>
				<h3><?php _e( 'Click to Import.', 'textdomain' ); ?></h3>
				<?php if ( $files ) : ?>
					<table>
						<thead>
							<th>Filename</th>
							<th>Date Uploaded</th>
						</thead>
						<tbody>
							<?php foreach ( $files as $file ) : ?>
								<tr>
									<?php if ( $file['filename_ext'] == 'json' ) : ?>
										<?php $file_name = $file['filename']; ?>
										<?php $full_path_file_name = $file['path'] .'/'. $file['filename']; ?>
										<td>
											<h3><a href="?kamar_import_notice=1&kamar_import_notice_json_file=<?php echo $file_name; ?>"><?php echo $file_name; ?></a></h3>
										</td>
										<td><h3><?php echo date("D d F ", filemtime($full_path_file_name) ); ?> at <?php echo date("G:i:s", filemtime($full_path_file_name) ); ?></h3></td>
									<?php endif; ?>
								</tr>
							<?php endforeach; ?>

						</tbody>
					</table>
					<ul>

					</ul>
				<?php endif; ?>
    </div>
    <?php
  }


}//WKI_Notices

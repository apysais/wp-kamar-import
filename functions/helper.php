<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function wki_dd( $arr = [] ) {
  echo '<pre>';
  print_r($arr);
  echo '</pre>';
}
function  wki_redirect_to($url) {
	?>
	<script type="text/javascript">
		window.location = '<?php echo $url; ?>';
	</script>
	<?php
	die();
}

function  wki_delete_custom_posts($post_type = ''){
    global $wpdb;
		$result = false;
		if ( $post_type != '' ) {
			$sql_str = "
			DELETE posts,pt,pm
			FROM wp_posts posts
			LEFT JOIN wp_term_relationships pt ON pt.object_id = posts.ID
			LEFT JOIN wp_postmeta pm ON pm.post_id = posts.ID
			WHERE posts.post_type = %s
			";
			$result = $wpdb->query(
	        $wpdb->prepare($sql_str, $post_type)
	    );
		}
    return $result;
}

function  wki_run_cron_manually() {
	if ( isset( $_GET['kie_cron'] ) && $_GET['x'] = 'x123x456' ) {
		if ( is_user_logged_in() && current_user_can('administrator') ) {
			//WKI_Cron::get_instance()->eo();
		}
		die();
	}
}

function wkie_custom_logs($message, $custom_log_name = 'custom') {
    if(is_array($message)) {
        $message = json_encode($message);
    }
		$custom_file = wki_get_plugin_dir() . $custom_log_name . '_logs.log';
    $file = fopen($custom_file,"a");
    fwrite($file, "\n" . date('Y-m-d h:i:s') . " :: " . $message);
    fclose($file);
}

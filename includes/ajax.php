<?php

/*------------------------------------------------------------------------------------------
	Frontend ajax handler file
------------------------------------------------------------------------------------------*/


# Loading assets
add_action('wp_ajax_nopriv_sp_ajax', 'sp_ajax');
add_action('wp_ajax_sp_ajax', 'sp_ajax');
function sp_ajax() {
	global $wpdb,$current_user,$sp_table_name;
    if( is_user_logged_in() ) $c_user_id = get_current_user_id( );
    $file_id   = $_POST['file'];
    $visitor_ip  = $_SERVER["REMOTE_ADDR"];
    $prouser   = (get_option('sharepro_pro_downloader_role')) ? true : false;

    $can_download_free = $wpdb->get_var(" SELECT count FROM $sp_table_name WHERE ip = '$visitor_ip' AND DATE(time) = DATE(NOW()) ORDER BY time DESC LIMIT 1 ");
	if( $can_download_free == get_option('sharepro_download_files_limit',10) ) $can_download_free = 'full';

    if( ($c_user_id && $prouser==false) || user_can( $current_user, "proaccess" ) || $can_download_free != 'full' ) :
    	echo wp_get_attachment_url( $file_id );
        $checkif_not_first_dl = $wpdb->get_var(" SELECT id FROM $sp_table_name WHERE ip = '$visitor_ip' AND DATE(time) = DATE(NOW()) AND count < 10 ORDER BY time DESC LIMIT 1 ");
        if($checkif_not_first_dl) {
            $wpdb->query(" UPDATE $sp_table_name SET count = count + 1 WHERE id = $checkif_not_first_dl ");
        } else {
            $now = date( 'Y-m-d h:i:s',strtotime('now') );
            $wpdb->query(" INSERT INTO $sp_table_name VALUES ('', '$now', '$visitor_ip', '1') ");
        }
    endif;

	die();
}


add_action('wp_ajax_spadmin_ajax', 'spadmin_ajax');
function spadmin_ajax() {
    global $wpdb;

    if($_POST['offset']>0) $offset = $wpdb->escape($_POST['offset']);

    $n_offset = ($offset) ? $offset*10 : 0;

    $all_files = $wpdb->get_results(" SELECT ID,post_title FROM $wpdb->posts WHERE post_type = 'attachment' ORDER BY post_date DESC LIMIT $n_offset,10 ");

    foreach ($all_files as $file) {
        echo '<li class="pick_file" id="file_'.$file->ID.'"> '.$file->post_title.' </li>';
    }

    die();
}


?>
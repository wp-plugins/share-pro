<?php

/*------------------------------------------------------------------------------------------
	Frontend main file
------------------------------------------------------------------------------------------*/


# Loading assets
add_action( 'wp_enqueue_scripts', 'sp_loadassets' );
function sp_loadassets() {
    wp_register_script('sharepro', SHAREPRO_URL.'includes/js/script.js',false,"1.0");
	wp_register_style('sharepro', SHAREPRO_URL.'includes/css/style.css', false, "1.0", "all");
	
    if( !wp_script_is('jquery') ) wp_enqueue_script('jquery');
	wp_enqueue_script('sharepro');
    wp_enqueue_style('sharepro');
}


# Loading Header script
add_action('wp_head', 'sp_script');
function sp_script() { ?>
<style type="text/css"> .sp_container .sp_button,.sp_container .sp_button:hover { background-color: <?php echo get_option('sharepro_button_bg','#067ed2'); ?>; border-color: <?php echo get_option('sharepro_button_bg','#067ed2'); ?>; } </style>
<script type="text/javascript"> var wpajax_url = "<?php echo admin_url('admin-ajax.php'); ?>"; </script>
    <?php
}


# Adding sharepro shortcode
add_shortcode( 'sharepro', 'show_sharepro_shortcode' );
function show_sharepro_shortcode( $atts ) { 
	global $current_user,$wpdb,$sp_table_name;
	$prouser 	 = (get_option('sharepro_pro_downloader_role')) ? true : false;
	$current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
	$visitor_ip  = $_SERVER["REMOTE_ADDR"];
	$next_time   = get_option('sharepro_next_file_waiting',30);

	$can_download_free  = $wpdb->get_row(" SELECT count,time FROM $sp_table_name WHERE ip = '$visitor_ip' AND DATE(time) = DATE(NOW()) ORDER BY time DESC LIMIT 1 ");
	$last_time_download = $can_download_free->time;	$can_download_free  =  $can_download_free->count;
	if( $can_download_free == get_option('sharepro_download_files_limit',10) ) $can_download_free = 'full';
	if($atts) extract($atts); 


	### PRO ACCESS INTERACTION

	$sp_content = '<div class="sp_container alignleft">'.
	 '<input type="hidden" id="timer_minimum" value="'.$next_time.'" />';
	if( !is_user_logged_in() && $_GET['register'] && $_GET['register']=='true' ) :
	 $sp_content .= '<p> You\'ve have been registered successfully, please check your email then login. </p>';
	endif;

	if( is_user_logged_in() && $_GET['request'] && $_GET['request']=='true' ) : 
	 wp_get_current_user();
	 $username = $current_user->user_login;
	 $useremail = $current_user->user_email;
	 $message = "User with username: $username and email: $useremail requested Pro Access Role, promote or contact the user.";
	 wpmail(get_option('admin_email'),'Pro Access Request',$message,'FROM: '.$useremail);
	 $sp_content .= '<p> You\'ve have sent the request successfully, please wait to be promoted or contacted. </p>';
	endif;

	$sp_content .= '<div class="sp_pro_access alignleft">'.
	 '<div class="sp_title"> Download with Pro Access </div>'.
	 '<div class="sp_display" id="promotion">';

	if( !is_user_logged_in() ) :
	 $sp_content .= '<ul>'.
	  '<li> No waiting time </li>'.
	  '<li> No daily downloads limit </li>'.
	  '<div class="clear"></div>'.
	 '</ul> <div class="clear"></div>'.
	 '<a href="#" class="sp_button register alignleft"> Register </a>'.
	 '<a href="#" class="sp_button login alignright"> Login </a>';
	elseif( !$prouser || user_can( $current_user, "proaccess" ) ) :
	 $sp_content .= '<ul>'.
	  '<li> Enjoy quick downloads </li>'.
	  '<li> Enjoy unlimited downloads </li>'.
	  '<div class="clear"></div>'.
	 '</ul> <div class="clear"></div>'.
	 '<a href="#" class="sp_button download" id="file_'.$file.'"> Download </a>';
	elseif( !user_can( $current_user, "proaccess" ) ) :
	 $sp_content .= '<ul>'.
	  '<li> No waiting time </li>'.
	  '<li> No daily downloads limit </li>'.
	  '<div class="clear"></div>'.
	 '</ul> <div class="clear"></div>'.
	 '<a href="'.$current_url.'?&request=true" class="sp_button request alignleft"> Request </a>';
	endif;

	$sp_content .= '</div>'.
	 '<div class="sp_display" id="login_form" style="display:none">'.
	  '<form name="loginform" id="loginform" action="'.get_bloginfo('url').'/wp-login.php" method="post">'.
	   '<span> Username </span>'.
	   '<input type="text" name="log" class="sp_input alignright" /> <br/>'.
	   '<span> Password </span>'.
	   '<input type="password" name="pwd" class="sp_input alignright" />'.
	   '<div class="clear"></div>'.
	   '<input type="submit" name="wp-submit" value="Login" class="sp_button register_user alignleft" />'.
	   '<input type="button" value="Cancel" class="sp_button cancel alignright" />'.
	   '<input type="hidden" name="redirect_to" value="'.$current_url.'" />'.
	   '<input type="hidden" name="testcookie" value="1" />'.
	  '</form>'.
	 '</div>'.
	 '<div class="sp_display" id="register_form" style="display:none">'.
	  '<form name="registerform" id="registerform" method="post" action="'.site_url('wp-login.php?action=register', 'login_post').'">'.
	   '<span> Username </span>'.
	   '<input type="text" name="user_login" class="sp_input alignright" /> <br/>'.
	   '<span> Email </span>'.
	   '<input type="text" name="user_email" class="sp_input alignright" />'.
	   '<div class="clear"></div>'.
	   '<input type="submit" name="user-submit" value="Register" class="sp_button login_user alignleft" />'.
	   '<input type="button" value="Cancel" class="sp_button cancel alignright" />'.
	   '<input type="hidden" name="redirect_to" value="'.$current_url.'?register=true" />'.
	   '<input type="hidden" name="user-cookie" value="1" />'.
	  '</form>'.
	 '</div>'.
	'</div>';


	### FREE ACCESS INTERACTION

	if( !is_user_logged_in() || ($prouser && !user_can( $current_user, "proaccess" ) ) ) :

     if( $can_download_free == 0 ) :
		$timer_value = get_option('sharepro_wait_for_download',60);
		$timer_term  = 'seconds';
	 else :
		$minutes_passed = round( (strtotime('now') - strtotime($last_time_download))/60 );
		$timer_value = $next_time-$minutes_passed;
		$timer_term  = 'minutes';
		if($timer_value<=0) {
			$timer_value = get_option('sharepro_wait_for_download',60);
			$timer_term  = 'seconds';
		}
	 endif;

	 $sp_content .= '<div class="sp_free_access alignleft">'.
	  '<div class="sp_title"> Download with Free Access </div>'.
	  '<div class="sp_display">';

	 if( $can_download_free != 'full' ) :
	  $sp_content .= '<p> Waiting time: </p>'.
	   '<h2 class="sp_timer">'.$timer_value.'</h2>'.
	   '<p class="sp_term">'.$timer_term.'</p>'.
	   '<a href="#" class="sp_button download" id="file_'.$file.'"> Download </a>';
     else :
      $sp_content .= '<p style="width:170px"> Unfortunatelly you reached the maximum of your daily free download limit, 
		please register and login to downoad or come back again tomorrow. </p>';
	 endif;
	 $sp_content .= '</div>'.
	 '</div>';

	endif;

	$sp_content .='</div>'.
	 '<div class="clear"></div>';

	return $sp_content;
}


?>
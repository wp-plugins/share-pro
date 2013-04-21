<?php

/*------------------------------------------------------------------------------------------
	Admin main file
------------------------------------------------------------------------------------------*/


# Loading assets
add_action( 'admin_enqueue_scripts', 'spadmin_loadassets' );
function spadmin_loadassets() {
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');
}


# Loading Header script
add_action('admin_head', 'spadmin_script');
function spadmin_script() { ?>
    <script type="text/javascript">

        jQuery(document).ready(function(){
            if( jQuery('.sp_picker').length > 0 ) { jQuery('.sp_picker').wpColorPicker(); }
        });

    </script>
    <?php
}



add_action('init', 'sharepro_addbuttons');
function sharepro_addbuttons() {
    add_filter("mce_external_plugins", "sharepro_tinymce_plugin");
    add_filter('mce_buttons', 'register_sharepro_button');
}
 
function register_sharepro_button($buttons) {
   array_push($buttons, "separator", "sharepro");
   return $buttons;
}
 
// Load the TinyMCE plugin : editor_plugin.js (wp2.5)
function sharepro_tinymce_plugin($plugin_array) {
   $plugin_array['sharepro'] = SHAREPRO_URL.'admin/js/script_editor.js';
   return $plugin_array;
}
 



# Getting listed on menu
add_action('admin_menu', 'sharepro_settings_menu');
function sharepro_settings_menu() {
	add_options_page('SharePro Settings', 'SharePro', 'administrator', 'sharepro.php', 'sharepro_settings');
}


# Display and save SharePro settings
function sharepro_settings() { ?>
	
	<div class="wrap">  
        <?php screen_icon('themes'); ?> <h2>SharePro settings</h2>

		<?php if($_POST['save_changes']) :

			unset($_POST['save_changes']);

            if(!$_POST['pro_downloader_role']) {
                if( get_option( 'sharepro_pro_downloader_role' ) ) delete_option( 'sharepro_pro_downloader_role' );
            }

			foreach ($_POST as $option_name => $option_value) {
				update_option("sharepro_$option_name",$option_value);
			} ?>

        	<div id="message" class="updated"> <p> Settings saved. </p> </div>

    	<?php endif; ?>

        
  
        <form method="POST" action="" style="margin-bottom:35px">

        	<input type="hidden" name="save_changes" value="1" />

            <table class="form-table">

                <tr valign="top">
                    <th scope="row" style="width:250px">
                        <label for="wait_for_download">
                            Before download waiting, time in seconds:
                        </label>
                    </th>
                    <td>
                        <input type="text" name="wait_for_download" value="<?php echo get_option('sharepro_wait_for_download',60); ?>" />
                        <span class="description"> Default is: 60</span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row" style="width:250px" style="width:250px">
                        <label for="next_file_waiting">
                            Next download waiting, time in minutes:
                        </label>
                    </th>
                    <td>
                        <input type="text" name="next_file_waiting" value="<?php echo get_option('sharepro_next_file_waiting',30); ?>" />
                        <span class="description">Default is: 30</span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row" style="width:250px">
                        <label for="download_files_limit">
                            Daily download files limit:
                        </label>
                    </th>
                    <td>
                        <input type="text" name="download_files_limit" value="<?php echo get_option('sharepro_download_files_limit',10); ?>" />
                        <span class="description">Default is: 10</span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row" style="width:250px">
                        <label for="pro_downloader_role">
                            Only Downloader role Pro Access:
                        </label>
                    </th>
                    <td>
                        <?php $check = ( get_option('sharepro_pro_downloader_role') ) ? 'checked="checked"' : ''; ?>
                        <input type="checkbox" name="pro_downloader_role" value="1" <?php echo $check; ?> />
                        <span class="description">Check if you want to push subscribers place request for Pro Access approvement by admin, otherwise any role will have Pro Access.</span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row" style="width:250px">
                        <label for="wait_for_download">
                            Pick Button color:
                        </label>
                    </th>
                    <td>
                        <input type="text" name="button_bg" value="<?php echo get_option('sharepro_button_bg','#067ed2'); ?>" class="sp_picker" data-default-color="#067ed2" />
                    </td>
                </tr>

            </table>

            <p>  
                <input type="submit" value="Save settings" class="button-primary" />  
            </p> 

        </form>
        
    </div>

<?php 

}

?>
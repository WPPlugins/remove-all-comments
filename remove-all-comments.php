<?php 
/*
Plugin Name: Remove All Comments
Plugin URI: http://wordpress.org/plugins/remove-all-comments/
Description: This plugin remove all comments from your current wordpress site.when Plugine is active its remove all comments for all user.you can also remove comments from specific post type. 
Version: 3.0
Requires at least: 3.0.1
Tested up to: 4.7.2
Author: php-developer  
Author URI: https://profiles.wordpress.org/php-developer-1
Contributors: php-developer 
Tags: comments, spam, delete comments,delete all comments, remove comments, no comments, spam free comments, comments less, remove all comments, auto remove comments
Network: false
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html 
*/
// create custom plugin settings menu
add_action('admin_menu', 'remove_all_comments');
function remove_all_comments() {
    //create new top-level menu
    add_menu_page('Remove All Comments Plugin Settings', 'Remove All Comments ', 'administrator', __FILE__, 'admin_form_remove_all_comments',plugins_url('/images/icon.png', __FILE__));
    //call register settings function
    add_action( 'admin_init', 'remove_all_comments_mysettings' );
}
function remove_all_comments_mysettings() {
    //register our settings
    register_setting( 'remove_all_comments_settings-group', 'remove_all_is_page' );
    register_setting( 'remove_all_comments_settings-group', 'remove_all_is_post' );    
    register_setting( 'remove_all_comments_settings-group', 'remove_all_is_all' ); 
    
}
///////////////// function for display admin side settings
function admin_form_remove_all_comments() { ?> <div class="wrap">
<h2><?php  _e('Remove All Comments Plugin Settings'); ?></h2>
<?php
count_comments_current_website();
   my_admin_note_reovellAllComments();
 ///////////// admin side form start ?>                  
<form method="post" action="options.php" >
    <?php settings_fields( 'remove_all_comments_settings-group' ); ?>
    <table class="form-table" width="100%">
         <?php
              global $wpdb;
              $comments_count = $wpdb->get_var("SELECT count(comment_id) from $wpdb->comments");
         ?>
             <tr valign="top">
        <th scope="row"><?php  _e('Do you want to remove all comments ?') ?> </th>
        <td>
        <?php $select_value_is_all = get_option('remove_all_is_all'); 
        $sel0_is_all = $sel1_is_all = null;
        if($select_value_is_all=='no')
        {
            $sel0_is_all ='selected';
        }else{
           $sel1_is_all ='selected'; 
        }
        ?>
        <select name="remove_all_is_all">
        <option><?php  _e('Select') ?></option>
        <option <?php echo $sel0_is_all ?> value="no"><?php  _e('No') ?></option>
        <option <?php echo $sel1_is_all ?> value="yes"><?php  _e('Yes') ?></option>
        </select>
       
        </td>
        </tr>
         
        <tr valign="top">
         <th scope="row"><?php  _e('Remove all comments only from Page ?') ?> </th>
        <td>
        <?php $select_value = get_option('remove_all_is_page'); 
        $sel0 = $sel1 = null;
        if($select_value=='no')
        {
            $sel0 ='selected';
        }else{
           $sel1 ='selected'; 
        }
        ?>
        <select name="remove_all_is_page">
        <option><?php  _e('Select') ?></option>
        <option <?php echo $sel0 ?> value="no"><?php  _e('No') ?></option>
        <option <?php echo $sel1 ?> value="yes"><?php  _e('Yes') ?></option>
        </select>
       
        </td>
        </tr>
        
        <tr valign="top">
         <th scope="row"><?php  _e('Remove all comments only from Post ?') ?> </th>
        <td>
        <?php $select_value_caption = get_option('remove_all_is_post'); 
        $sel1_caption = $sel0_caption = null;
        if($select_value_caption=='no')
        {
            $sel0_caption ='selected';
        }else{
           $sel1_caption ='selected'; 
        }
        ?>
        <select name="remove_all_is_post">
        <option><?php  _e('Select') ?></option>
        <option <?php echo $sel0_caption ?> value="no"><?php  _e('No') ?></option>
        <option <?php echo $sel1_caption ?> value="yes"><?php  _e('Yes') ?></option>
        </select>        
        </td>
        </tr>            
        </table>         
        <input type="hidden" name="action" value="update" />
        <input type="hidden" name="page_options" value="remove_all_is_page,remove_all_is_post,remove_all_is_all" />

        <p class="submit">
        <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
        </p>      

</form>
<div class="update-nag">
<p>If you want to remove all comments from specific post type then you have to add this function in your theme's function.php file.<br/> <br/>
 Example : If you want to remove all comments from post type 'book' then below is your php code.add this function in your theme's function.php file.
 <br/>
<strong>
 &lt?php removeCommentsFromSite("Book");  ?&gt
</strong>
<br/>
<h3>Need any kind of Support for your website feel free and mail us to  <a href="mailto:codex.wordpress@gmail.com?Subject=Remove All Comments Plugin Support" target="_top">codex.wordpress@gmail.com</a>    </h3>
<a href="http://www.kevalwebsoft.com/" target='_blank' > <img src="http://www.kevalwebsoft.com/wp-content/uploads/2015/04/FAQ-300x148.jpg" alt=""> </a> </p>
</div>
<?php ///////////// admin side form end  ?>
<?php /////// reset all data of each post  ?>
</div>
<?php }
function removeCommentsFromSite($post_type=null)
{
    global $wpdb;
    
    $count = count_comments_current_website();
    if($count > 100){
        
       return true; 
    }
    
    if($post_type=='all'){
         
         $wpdb->query(" DELETE $wpdb->comments FROM $wpdb->comments LEFT JOIN $wpdb->posts ON $wpdb->comments.comment_post_ID = $wpdb->posts.ID ");
         return true;
    }else {
        if (isset($post_type)){
             
             $wpdb->query(" DELETE $wpdb->comments FROM $wpdb->comments LEFT JOIN $wpdb->posts ON $wpdb->comments.comment_post_ID = $wpdb->posts.ID WHERE $wpdb->posts.post_type = '$post_type' ");
             return true;
        }                            
    }
}  
function removeAllCustomComments() {
	
    
    $select_value_is_all = get_option('remove_all_is_all');
    if($select_value_is_all=='yes')
    {
        
                removeCommentsFromSite($post_type='all');
                
        
    }else{       
                $select_value_is_page = get_option('remove_all_is_page');
                if($select_value_is_page=='yes')
                {
                 removeCommentsFromSite($post_type='page');   
                }
                $select_value_is_post = get_option('remove_all_is_post');
                if($select_value_is_post=='yes')
                {
                 removeCommentsFromSite($post_type='post');   
                }
    
    }
    return false ;   
	
} 
add_action('init', 'removeAllCustomComments'); 
register_uninstall_hook( __FILE__,'remove_all_comments_uninstall_data');
function remove_all_comments_uninstall_data()
{   
    delete_option( 'remove_all_is_page' );
    delete_option( 'remove_all_is_post' );
    delete_option( 'remove_all_is_all' );     
}      
function my_admin_notice_reovellAllComments(){     
//print_r();  
    $msg = 'Removell All Comments Settings Saved.';
    if (isset($_REQUEST['settings-updated'])) {
         return '<div class="updated">
             <p>'._($msg).'</p>
         </div>';  
    }
    
}
function error_message(){
$count = count_comments_current_website();
    if($count){
    $msg = 'Its looks there are many comments in your website.This plugin will not work with your website. <a target="_blank" href="http://www.kevalwebsoft.com/downloads/worpress-remove-all-comments-spammer-assaults/">You can get full version from here.</a>';
    return '<div class="error"><h3>'._($msg).'</h3></div>';
    }
}

add_action('admin_notices', 'error_message');

function my_admin_note_reovellAllComments(){     
    echo error_message();
    echo my_admin_notice_reovellAllComments();    
    $msg .= '<b>NOTE:</b> When you select <b>"Yes"</b> to <b>"Do you want to remove all comments ?"</b>  its override all other option settings.( Removed All Comments/review from your web site.)';
    ?>
    <div class="notice update-nag is-dismissible">
        <p><?php _e( $msg, 'sample-text-domain' ); ?></p>
    </div>
    <?php
}

function count_comments_current_website(){
          global $wpdb;
         $data = wp_count_comments();
         return $data->all;
}
?>
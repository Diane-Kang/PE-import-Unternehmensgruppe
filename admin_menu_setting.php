<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

//Admin Menu php - import button 
//inc/Admin 
// - Register page 
// - meunu site php 
// - button for excute Funktion 
// - Button for delete all 


if ( ! defined( 'PE_22Uhr_Import_Plugin_Path' ) ) {
	define( 'PE_22Uhr_Import_Plugin_Path', plugin_dir_path( __FILE__ ) );
}


//////------------importing ----------------//
require_once PE_22Uhr_Import_Plugin_Path . 'import_list.php';


class PE_Import_Company_List_Setting_Menu{

  function __construct() {
    add_action('admin_menu', array($this, 'adminPage'));
  }

  function adminPage() {
    add_menu_page( 'Company list import', 'PE - Import', 'manage_options', 'pe-list-import-page', array($this, 'settingHTML') );
  }

  function settingHTML() { 

  // This function creates the output for the admin page.
  // It also checks the value of the $_POST variable to see whether
  // there has been a form submission. 

  // The check_admin_referer is a WordPress function that does some security
  // checking and is recommended good practice.

  // General check for user permissions.
  if (!current_user_can('manage_options'))  {
    wp_die( __('You do not have sufficient allowance to access this page.')    );
  }

  // Start building the page
  ?> 
  <div class="wrap">
    <h2>Import Unternehmenlist</h2>

    <div class="generate_head_post"></div>
      <p>This button for generate the head post(unternehmen post type) of the company gruppe</p>
      <?php
      // Check whether the button has been pressed AND also check the nonce
      if (isset($_POST['do_generate_head_post']) && check_admin_referer('do_generate_head_post_clicked')) {
        // the button has been pressed AND we've passed the security check
        $this::do_generate_head_post();
      }?>
      <form action="admin.php?page=pe-list-import-page" method="post">
        <?php
        // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
        wp_nonce_field('do_generate_head_post_clicked');
        ?>
        <input type="hidden" value="true" name="do_generate_head_post" />
        <?php submit_button('Generate head post') ?>
      </form>
    </div>


    <div class="generate_posts"></div>
      <p>Generate posts from csv, before click the button, check the follwing list</p>
      <ui>
        <li>Head Post required : slug 'g-u-t', hierachie:0, ... when it is not, click the first button, it will generate head post</li>
        <li>csv file need to be under Plugin directory. check file name matches</li>
        <li>abek.jpg need to be manually uploaded. and then the attachment-id need to be applied</li>
        <li>Check logo.jpg location</li>
      </ui>
      <p>When everything looks good, good luck</p>

      <?php
      // Check whether the button has been pressed AND also check the nonce
      if (isset($_POST['do_generate_posts']) && check_admin_referer('do_generate_posts_clicked')) {
        // the button has been pressed AND we've passed the security check
        $this::do_generate_posts();
      }?>
      <form action="admin.php?page=pe-list-import-page" method="post">
        <?php
        // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
        wp_nonce_field('do_generate_posts_clicked');
        ?>
        <input type="hidden" value="true" name="do_generate_posts" />
        <?php submit_button('Generate posts from csv') ?>
      </form>
    </div>

    <div class="delet_posts"></div>
      <p>Delet all posts of CTP:unternehmen with metadata: firmengruppen:'G.U.T.'
        It will deelete all thumbnails together.
      </p>
      <?php
      // Check whether the button has been pressed AND also check the nonce
      if (isset($_POST['do_delet_posts']) && check_admin_referer('do_delet_posts_clicked')) {
        // the button has been pressed AND we've passed the security check
        $this::do_delet_posts();
      }?>
      <form action="admin.php?page=pe-list-import-page" method="post">
        <?php
        // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces
        wp_nonce_field('do_delet_posts_clicked');
        ?>
        <input type="hidden" value="true" name="do_delet_posts" />
        <?php submit_button('delete posts') ?>
      </form>
    </div>

  </div>
  <?php
  }
        
  
  

  function do_generate_head_post(){
    $pe_Import_Company_List_excute = new PE_Import_Company_List_excute();
    $pe_Import_Company_List_excute::generate_Head_Post();
  }
  

  function do_generate_posts(){
    $pe_Import_Company_List_excute = new PE_Import_Company_List_excute();
    $pe_Import_Company_List_excute::importing();
  } 


  function do_delet_posts(){
    echo '<div id="message" class="updated fade"><p>'
    .'delet all G.U.T. unternehmen Posts ' . '</p></div>';
 
    $arg = array( 
      'post_type'       => 'unternehmen', 
      'posts_per_page'  => -1,
      'meta_query'      => array(
        'relation'      => 'and',
        array(
            'key'       => 'firmengruppen',
            'value'     => 'G.U.T.',
            'compare'   => '='
        ),
      ),
    );
    
    $the_delete = new WP_Query($arg);
  
    if ( $the_delete->have_posts() ) {
      
        while ( $the_delete->have_posts() ) {
          $the_delete->the_post();
          $postID = get_the_ID();
          if(has_post_thumbnail( $postID )){
            $attachment_id = get_post_thumbnail_id( $postID );
            wp_delete_attachment($attachment_id, true);
          }
          wp_delete_post($postID);
        }
    }
  }



} // Class end  
$pe_Import_Company_List_Setting_Menu = new PE_Import_Company_List_Setting_Menu();
<?php

/*
  English
  Plugin Name: Unternehmensgruppe import Plugin - 22Uhr Project
  Plugin URI: 
  Description: 
  Automatic importing when we get a list of company group.
  Version: 0.0.1
  Author: Page-effect - Diane 
  Author URI: Page-effect.com
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( ! defined( 'PE_22Uhr_Plugin_Path' ) ) {
	define( 'PE_22Uhr_Plugin_Path', plugin_dir_path( __FILE__ ) );
}

//////------------add Admin Menu----------------//
require_once PE_22Uhr_Plugin_Path . 'includes/PE-22uhr-import-Firmengruppe.php';
    /////////------- setting map page, and map ceter 
    add_action('admin_menu', array($this, 'adminPage'));
    add_action('admin_init', array($this, 'settings'));

//Admin Menu php - import button 
//inc/Admin 
// - Register page 
// - meunu site php 
// - button for excute Funktion 
// - Button for delete all 

//Import function php 
//inc/Import
// - Import setting 

// Initialize php 
//inc/pre-import 
// G.U.T. map seite 
//    - slug : g-u-t
//    - some required Meta data   
// G.U.T. post 
//    - slug: g-u-t
//    - some required Meta data
//    - link to G.U.T. map seite 
//    - Featured picture( Thumbnail)  

// Delet all function php 
//inc/Delet
// - Delet all post by condition 
// - custom post type: Unternehmen 
// - Firmengruppe - G.U.T.
 
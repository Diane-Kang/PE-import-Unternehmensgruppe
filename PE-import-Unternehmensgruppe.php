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


if ( ! defined( 'PE_22Uhr_Import_Plugin_Path' ) ) {
	define( 'PE_22Uhr_Import_Plugin_Path', plugin_dir_path( __FILE__ ) );
}

//////------------add Admin Menu----------------//
require_once PE_22Uhr_Import_Plugin_Path . 'admin_menu_setting.php';


//Admin Menu php - import button 
//inc/Admin 
// - Register page 
// - meunu site php 
// - button for excute Funktion 
// - Button for delete all 

//Import function php 
//inc/Import
// - Local Head (Hierachie : 1) and Branches  (Hierachie : 2)
// - All same level of child of HEAD POST 
// 

// Initialize php 
//inc/pre-import 
// G.U.T. map seite--------------------------------------- Head_page 
//    - slug : g-u-t
//    - some required Meta data   
//    - Child Page of Main Map page (Firmenverzeichnis)


// G.U.T. post   ----------------------------------------- Head_Post 
//    - slug: g-u-t
//    - some required Meta data
//         -- Hierachie : 0    
//    - link to G.U.T. map seite 
//    - Featured picture( Thumbnail)  

// Delet all function php 
//inc/Delet
// - Delet all post by condition 
// - custom post type: Unternehmen 
// - Firmengruppe - G.U.T.

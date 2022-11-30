<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( ! defined( 'PE_22Uhr_Import_Plugin_Path' ) ) {
	define( 'PE_22Uhr_Import_Plugin_Path', plugin_dir_path( __FILE__ ) );
}


class PE_Import_Company_List_excute{

  private $Firmengruppe_name = 'G.U.T.';
  private $Firmengruppe_slug = 'g-u-t';
  private $logo_server = 'https://page-effect.de/wp-content/uploads/logos/';

  /* __getter function */
  public function __get( $variable ){
    if( !empty($this->$variable) ){
        $get_variable = $this->$variable;
    }

    return $get_variable;
  }
  
   
    function generate_Head_Post(){
  
      $postId = wp_insert_post(array(
        'post_type'     => 'unternehmen',
        'post_title'    => 'G.U.T.-<span>Gruppe</span>',
        'post_content'  => '', 
        'post_status'   => 'publish',
        'meta_input'    => array(
          '1-Breitengrad' => '53.21951431194988',
          '2-Laengengrad' => '8.241075142327427',
          'Land'          => 'DE',
          'Bundesland'    => 'Niedersachsen',
          'Postleitzahl'  => '26180',
          'Straße und Hausnummer'     => 'Schafjückenweg 1',
          'firmengruppen' => 'G.U.T.',
          'firmengruppen-hierarchie'  => 0,
          'firmengruppen-seite'=> 'g-u-t/',
          //'Werbebeleuchtung wurde im Projektrahmen angepasst (j/n)' =>  $vorProjekt=="ja" ? 'nein' : 'ja',
                ),
        'tax_input' => array(
          'branche' => term_exists( 'Baumärkte', 'branche'),
            //term_exists( 'Baumärkte', 'branche')
        'abschaltung' => 'nicht-vorhanden',
        ),
        'post_name' => 'g-u-t',
      ));
      self::Generate_Featured_Image( 'https://page-effect.de/wp-content/uploads/logos/'.'GUT-Logo.jpg', $postId  );
    }
  
  
  


  public function generate_list_from_csv(){
    $rows   = array_map('str_getcsv', file( PE_22Uhr_Import_Plugin_Path . 'Import_list_30Nov.csv'));
    $header = array_shift($rows);
    $csv    = array();
    foreach($rows as $row) {
        $csv[] = array_combine($header, $row);
    }
    return $csv;
  }

  function importing(){

    $array = self::generate_list_from_csv();
 
    foreach ($array as $single) {

      // First generate post for local head
      if ($single['Standorttyp Name'] == 'HAUPTHAUS'){
      $vorProjekt = $single['Unabhängige Umsetzung bereits vor Projekt?'];
      $postId = wp_insert_post(array(
          'post_type' => 'unternehmen',
          'post_title' => $single['Standortname'] .' '. $single['Werblicher Anzeige Name'],
          'post_content' => $single['Statement'] . '<h4>'. $single['Statementgeber']. '('.$single['Funktion des Statementgebers'] .'), Okt.2022</h4>', 
          'post_status' => 'publish',
          'meta_input' => array(
            'Logo Filename' => $single['Logo Filename'],
            '1-Breitengrad' => $single['Breitengrad (Komma)'],
            '2-Laengengrad' => $single['Längengrad (Komma)'],
            'Land'          => 'DE',
            'Bundesland' => $single['Bundesland'],
            'Postleitzahl' => $single['PLZ'],
            'Straße und Hausnummer' => $single['Straße'] . $single['Hausnummer'],
            'firmengruppen' => 'G.U.T.',
            'firmengruppen-hierarchie' => 1,
            'firmen_slug' => $single['Slug'], 
            'Werbebeleuchtung wurde im Projektrahmen angepasst (j/n)' =>  $vorProjekt=="ja" ? 'nein' : 'ja',
                  ),
          'tax_input' => array(
            'branche' => array(
              'Baumärkte',
              //term_exists( 'Baumärkte', 'branche'),
            ),
            'abschaltung' => $single['Um wie viel Uhr wird das Licht ausgestellt?'],
          ),
          'post_name' => $single['Slug'],
          'post_parent' => get_page_by_path('g-u-t', OBJECT, 'unternehmen')->ID
        ));

        self::Generate_Featured_Image( 'https://page-effect.de/wp-content/uploads/logos/'. $single['Logo Filename'], $postId  );
      }
    }
 
    $attachment_id = self::upload_abek_logo('https://page-effect.de/wp-content/uploads/logos/gut-abex-logo.jpg');

    // Then generate child posts of local head
    foreach ($array as $single) {
      if($single['Standorttyp Name'] == 'ABEX'|| $single['Standorttyp Name'] == 'NIEDERLASSUNG') {
        $vorProjekt = $single['Unabhängige Umsetzung bereits vor Projekt?'];
        $postId = wp_insert_post(array(
            'post_type' => 'unternehmen',
            'post_title' => $single['Standortname'] .' '. $single['Werblicher Anzeige Name'],
            'post_content' => $single['Statement'] . '<h4>'. $single['Statementgeber']. '('.$single['Funktion des Statementgebers'] .'), Okt.2022</h4>', 
            'post_status' => 'publish',
            'meta_input' => array(
              'Logo Filename' => '--',
              '1-Breitengrad' => $single['Breitengrad (Komma)'],
              '2-Laengengrad' => $single['Längengrad (Komma)'],
              'Land'          => 'DE',
              'Bundesland' => $single['Bundesland'],
              'Postleitzahl' => $single['PLZ'],
              'Straße und Hausnummer' => $single['Straße'] . $single['Hausnummer'],
              'firmengruppen' => 'G.U.T.',
              'firmengruppen-hierarchie' => 2,
              'firmen_slug' => $single['Slug'],
              'Werbebeleuchtung wurde im Projektrahmen angepasst (j/n)' =>  $vorProjekt=="ja" ? 'nein' : 'ja',
                    ),
            'tax_input' => array(
              'branche' => array(
                'Baumärkte',
                //term_exists( 'Baumärkte', 'branche'),
              ),
              'abschaltung' => $single['Um wie viel Uhr wird das Licht ausgestellt?'],
            ),
          'post_parent' => get_page_by_path('g-u-t/'.$single['Slug'], OBJECT, 'unternehmen')->ID
        ));
        // apek log ist manualle gegeben
        set_post_thumbnail( $postId, $attachment_id );    
      }
    }
  }

  public function upload_abek_logo($image_url){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if(wp_mkdir_p($upload_dir['path']))
      $file = $upload_dir['path'] . '/' . $filename;
    else
      $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, 0);
    
    return $attach_id;

  }


  public function Generate_Featured_Image( $image_url, $post_id  ){
    $upload_dir = wp_upload_dir();
    $image_data = file_get_contents($image_url);
    $filename = basename($image_url);
    if(wp_mkdir_p($upload_dir['path']))
      $file = $upload_dir['path'] . '/' . $filename;
    else
      $file = $upload_dir['basedir'] . '/' . $filename;
    file_put_contents($file, $image_data);

    $wp_filetype = wp_check_filetype($filename, null );
    $attachment = array(
        'post_mime_type' => $wp_filetype['type'],
        'post_title' => sanitize_file_name($filename),
        'post_content' => '',
        'post_status' => 'inherit'
    );
    $attach_id = wp_insert_attachment( $attachment, $file, $post_id );
    require_once(ABSPATH . 'wp-admin/includes/image.php');
    $attach_data = wp_generate_attachment_metadata( $attach_id, $file );
    $res1= wp_update_attachment_metadata( $attach_id, $attach_data );
    $res2= set_post_thumbnail( $post_id, $attach_id );
}

}//Class end

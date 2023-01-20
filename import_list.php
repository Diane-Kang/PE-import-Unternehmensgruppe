<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


if ( ! defined( 'PE_22Uhr_Import_Plugin_Path' ) ) {
	define( 'PE_22Uhr_Import_Plugin_Path', plugin_dir_path( __FILE__ ) );
}


class PE_Import_Company_List_excute{

  private $Firmengruppe_name = 'G.U.T.';
  private $Firmengruppe_slug = 'g-u-t';
  const LOGO_SERVER = 'https://page-effect.de/wp-content/uploads/logos-22uhr/g-u-t/';
  const ZERIFIKAT_GUT_2023 = '/wp-content/plugins/PE-import-Unternehmensgruppe/2023-Zertifikat-GUT-Gruppe-Projekt-22Uhr.pdf';
  

  public function generate_Head_Post(){
  
      $postId = wp_insert_post(array(
        'post_type'     => 'unternehmen',
        'post_title'    => 'G.U.T.-GRUPPE',
        'post_content'  => '<p>Nachhaltigkeit, Menschlichkeit und das Bewusstsein für Umwelt und Klimawandel sind tief im Unternehmensverständnis der G.U.T.-GRUPPE verankert. Schließlich trägt der Verbund aus etablierten Fachgroßhändlern für Haustechnik schon den Nachhaltigkeitsgedanken im Namen: G.U.T. = Gebäude- und Umwelttechnik. Das, was die Partnerhäuser, ihre Gesellschafter, alle Mitarbeiter und mittlerweile auch die Kundschaft aus Fachhandwerkern bereits verinnerlicht haben und wie selbstverständlich leben, wird darüber hinaus durch ein überdurchschnittliches Umwelt-Engagement begleitet. Daher engagieren wir uns mit unseren mehr als 50 Partnerhäusern an über 260 Standorten in Deutschland an dem Projekt „22 Uhr – Licht aus“. Dabei sparen wir nicht nur enorm Energie ein, sondern freuen uns riesig, noch zusätzlich etwas gegen den Klimawandel und gegen die wachsende Lichtverschmutzung in Ballungszentren tun zu können.</p><h4>Bernd Reinke (Persönlich haftender Gesellschafter), Okt. 2022</h4>', 
        'post_status'   => 'publish',
        'meta_input'    => array(
          '1-Breitengrad' => '53.21951431194988',
          '2-Laengengrad' => '8.241075142327427',
          'Land'          => 'DE',
          'Ort'           => 'Rastede',
          'Bundesland'    => 'Niedersachsen',
          'Postleitzahl'  => '26180',
          'Straße und Hausnummer'     => 'Schafjückenweg 1',
          'Internet'     => 'https://www.gut-gruppe.de/',
          'PDF Pfad' => self::ZERIFIKAT_GUT_2023,
          'firmengruppen' => 'G.U.T.',
          'firmengruppen-hierarchie'  => 0,
          'firmengruppen-seite'=> 'g-u-t/',
          'Abschaltung' => '<h5>Was wir für weniger Lichtverschmutzung und weniger Energieverbrauch tun:</h5><p>Die einzelnen Partnerhäuser der G.U.T.-GRUPPE entscheiden eigenständig für ihre Niederlassungen, Ausstellungen und ABEX-Abholläger, welche Beleuchtung sie wann abschalten. Wir haben mal nachgefragt und sind bereits zu Beginn der Aktion zu einem tollen Ergebnis gekommen: <strong>Bei zwei Dritteln (65,9%) aller G.U.T.-Standorte sind um 21 Uhr die Werbelichter und alle Beleuchtungen ausgeschaltet! </strong>Knapp 16 Prozent legen den Schalter schon eine Stunde früher um. Bei immerhin noch etwas mehr als 10 Prozent aller G.U.T.-Häuser ist es bereits um 19 Uhr und bei knapp 8 Prozent bereits um 18 Uhr alles dunkel.</p><p>Aber ganz besonders stolz sind wir darauf, dass ziemlich genau die Hälfte dieser Maßnahmen bereits vor unserer Beteiligung am Projekt „22 Uhr – Licht aus“ umgesetzt waren. Wir arbeiten stetig daran, unseren Beitrag zu mehr Energieeffizienz und weniger Lichtverschmutzung zu optimieren. Für eine bessere Umwelt.</p>',
          'Werbebeleuchtung wurde im Projektrahmen angepasst (j/n)' =>  'j',
                ),
        'tax_input' => array(
          'branche' => term_exists( 'Großhandel', 'branche'),
          'abschaltung' => '21 Uhr',
        ),
        'post_name' => 'g-u-t',
      ));
      self::Generate_Featured_Image( self::LOGO_SERVER.'GUT-Logo.jpg', $postId  );
    }
  
    


  public function generate_list_from_csv(){
    $rows   = array_map('str_getcsv', file( PE_22Uhr_Import_Plugin_Path . 'Import_list_20_jan.csv'));
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
      $vorProjekt = $single['Änderung der Beleuchtung durch Teilnahme am Projekt?'];
      $postId = wp_insert_post(array(
          'post_type' => 'unternehmen',
          'post_title' => $single['Standortname'] .' <span>'. $single['Werblicher Anzeige Name'] . '</span>',
          'post_content' => $single['Statement'] . '<h4>'. $single['Statementgeber']. ' ('.$single['Funktion des Statementgebers'] .'), Okt. 2022</h4>', 
          'post_status' => 'publish',
          'meta_input' => array(
            'Logo Filename' => $single['Logo Filename'],
            '1-Breitengrad' => str_replace(',', '.',$single['Breitengrad (Komma)']),
            '2-Laengengrad' => str_replace(',', '.',$single['Längengrad (Komma)']),
            'Land'          => 'DE',
            'Ort'           => $single['Stadt'],
            'Bundesland' => $single['Bundesland'],
            'Postleitzahl' => $single['PLZ'],
            'Straße und Hausnummer' => $single['Straße'] . '&nbsp' . $single['Hausnummer'],
            'Internet'     => $single['Homepage'],
            'PDF Pfad' => self::ZERIFIKAT_GUT_2023,
            'Abschaltung' => $single['Details zur Lichtabschaltung'],
            'firmengruppen' => 'G.U.T.',
            'firmengruppen-hierarchie' => 1,
            'firmen_slug' => $single['Slug'], 
            'Werbebeleuchtung wurde im Projektrahmen angepasst (j/n)' =>  $vorProjekt,
          ),
          'tax_input' => array(
            'branche' => term_exists( 'Großhandel', 'branche'),
            'abschaltung' => $single['Um wie viel Uhr wird das Licht ausgestellt?'],
          ),
          'post_name' => $single['Slug'],
          'post_parent' => get_page_by_path('g-u-t', OBJECT, 'unternehmen')->ID
        ));

        self::Generate_Featured_Image( self::LOGO_SERVER. $single['Logo Filename'], $postId  );
      }
    }
 
    $attachment_id = self::upload_abek_logo( self::LOGO_SERVER . '/gut-abex-logo.jpg');

    // Then generate child posts of local head
    foreach ($array as $single) {
      if($single['Standorttyp Name'] == 'ABEX'|| $single['Standorttyp Name'] == 'NIEDERLASSUNG') {
        $vorProjekt = $single['Unabhängige Umsetzung bereits vor Projekt?'];
        $postId = wp_insert_post(array(
            'post_type' => 'unternehmen',
            'post_title' => $single['Standortname'] .'  <span>'. $single['Werblicher Anzeige Name'] . '</span>',
            'post_content' => $single['Statement'] . '<h4>'. $single['Statementgeber']. ' ('.$single['Funktion des Statementgebers'] .'), Okt. 2022</h4>', 
            'post_status' => 'publish',
            'meta_input' => array(
              'Logo Filename' => '--',
              '1-Breitengrad' => str_replace(',', '.',$single['Breitengrad (Komma)']),
              '2-Laengengrad' => str_replace(',', '.',$single['Längengrad (Komma)']),
              'Land'          => 'DE',
              'Ort'           => $single['Stadt'],
              'Bundesland' => $single['Bundesland'],
              'Postleitzahl' => $single['PLZ'],
              'Straße und Hausnummer' => $single['Straße'] . '&nbsp' . $single['Hausnummer'],
              'Internet'     => $single['Homepage'],
              'PDF Pfad' => self::ZERIFIKAT_GUT_2023,
              'Abschaltung' => $single['Details zur Lichtabschaltung'],
              'firmengruppen' => 'G.U.T.',
              'firmengruppen-hierarchie' => 2,
              'firmen_slug' => $single['Slug'],
              'Werbebeleuchtung wurde im Projektrahmen angepasst (j/n)' =>  $vorProjekt,
              ),
              'tax_input' => array(
                'branche' => term_exists( 'Großhandel', 'branche'),
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

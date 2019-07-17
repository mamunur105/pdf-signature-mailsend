<?php
/**
* Plugin Name: PDF Generate
* Plugin URI:  https://example.com/plugin-name
* Description: Description of the plugin.
* Version:     1.0.0
* Author:      
* Author URI:  https://example.com
* Text Domain: 
* License:     GPL v2 or later
* License URI: http://www.gnu.org/licenses/gpl-2.0.txt
*/

require_once __DIR__ . '/vendor/autoload.php'; 

function pdf_genarator(){

    // $mpdf = new \Mpdf\Mpdf();
    // $mpdf->WriteHTML('<h1>Hello world!</h1>');
    // $mpdf->Output();
    
}

add_action('init', 'pdf_genarator');
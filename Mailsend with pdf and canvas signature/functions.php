<?php
// Declaring Global Variable for Theme Options
define( 'CODEXIN_THEME_OPTIONS', 'codexin_get_option' );

require_once 'lib/menus.php';
require_once 'lib/shortcodes.php';
require_once 'lib/scripts.php';
require_once 'lib/widgets.php';
require_once 'lib/wp-debloat.php';
require_once 'lib/helpers.php';
require_once 'lib/color-patterns.php';
require_once 'vendors/meta-box/meta-box.php';

if ( class_exists( 'RW_Meta_Box' ) && is_admin() ) {
    // Add plugin meta-box-show-hide
    require_once 'vendors/meta-box/extensions/meta-box-show-hide.php';

    // Add plugin meta-box-tabs
    require_once 'vendors/meta-box/extensions/meta-box-tabs.php';

    // Add plugin meta-box-conditional-logic
    require_once 'vendors/meta-box/extensions/meta-box-conditional-logic.php';
}

require_once 'lib/metaboxes.php';

add_theme_support( 'post-thumbnails' );

add_theme_support( 'html5', array(
	'search-form',
	'comment-form',
	'comment-list',
	'gallery',
	'caption',
) );


/**
 * Let WordPress manage the document title.
 * By adding theme support, we declare that this theme does not use a
 * hard-coded <title> tag in the document head, and expect WordPress to
 * provide it for us.
 */
add_theme_support( 'title-tag' );

// Declaring woocommerce support

add_action( 'after_setup_theme', 'codexin_woocommerce_support' );
function codexin_woocommerce_support() {
    add_theme_support( 'woocommerce' );
}


// include Redux framework theme  options

if ( !class_exists( 'ReduxFramework' ) && file_exists( dirname( __FILE__ ) . '/vendors/ReduxFramework/ReduxCore/framework.php' ) ) {
    require_once( dirname( __FILE__ ) . '/vendors/ReduxFramework/ReduxCore/framework.php' );
}
if ( !isset( $redux_demo ) && file_exists( dirname( __FILE__ ) . '/vendors/ReduxFramework/admin-config.php' ) ) {
    require_once( dirname( __FILE__ ) . '/vendors/ReduxFramework/admin-config.php' );
}

// /* Removing 'Redux Framework' sub menu under Tools */

// /** remove redux menu under the tools **/
add_action( 'admin_menu', 'remove_redux_menu',12 );
function remove_redux_menu() {
    remove_submenu_page('tools.php','redux-about');
}


/**
 * Enable support for adding custom image sizes
 *
 */
add_image_size( 'single-post-image', 800, 450, true );

add_image_size( 'recent-post',350, 200,true );

add_action( 'admin_init', 'codexin_editor_styles' );
if ( ! function_exists( 'codexin_editor_styles' ) ) {
	/**
	 * Apply theme's stylesheet to the visual editor.
	 *
	 * @uses 	add_editor_style() Links a stylesheet to visual editor
	 * @since 	v1.0
	 */
	function codexin_editor_styles() {
		add_editor_style( 'css/admin/editor-style.css' );
	}
}


// Adding woocommerce compitability to theme structure

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'codexin_wrapper_start', 10);
add_action('woocommerce_after_main_content', 'codexin_wrapper_end', 10);

function codexin_wrapper_start() {
  echo '<div class="container">';
}

function codexin_wrapper_end() {
  echo '</div>';
}

// woocommerce finished



add_filter( 'widget_text', 'do_shortcode' );

// add_filter( 'excerpt_more', function ( $more ) {
//  	return ' ... <a class="readmore" href="' . get_the_permalink() . '">Read More.</a>';
// });

# returns a custom-formated page title
function get_page_title ( $title, $id = null ) {
	?>
	<div id="page_title" class="section site-content">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<h1><?php echo $title ?></h1>	
				</div>
			</div>
		</div>
	</div>
	<?php
} // get_page_title( $title, $id = null )


// Removing srcset from featured image
add_filter( 'max_srcset_image_width', create_function( '', 'return 1;' ) );

// Removing width & height from featured image
add_filter( 'post_thumbnail_html', 'remove_thumbnail_dimensions', 10, 3 );
function remove_thumbnail_dimensions( $html, $post_id, $post_image_id ) {
    $html = preg_replace( '/(width|height)=\"\d*\"\s/', "", $html );
    return $html;
}



# removes query strings from static resources
add_filter( 'script_loader_src', '_remove_script_version', 15, 1 );
add_filter( 'style_loader_src', '_remove_script_version', 15, 1 );
function _remove_script_version ( $src ) {
	$parts = explode( '?ver', $src );
	return $parts[0];
} // _remove_script_version ( $src )



if(!function_exists('codexin_header_tracking_code')){
    /**
     * Add advanced tracking code to header
     *
     * @uses 	add_action()
     * @since 	v1.0
     */
    function codexin_header_tracking_code() {
        $advanced_tracking_code = codexin_get_option( 'cx_advanced_tracking_code' );

        if( $advanced_tracking_code ) {
            printf( '%s', $advanced_tracking_code );
        }
    }
    add_action( 'wp_head', 'codexin_header_tracking_code', 999 );
}


function ip_details() 
{
    $ip = $_SERVER['REMOTE_ADDR'];
    $details = json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip={$ip}"));
    return $details;
}
 

function pdf_genarator($data_pdf=null){
    if ($data_pdf != null) {
        if (file_exists(__DIR__.'/vendors/pdfgenerate/vendor/autoload.php')) {
            require_once __DIR__ . '/vendors/pdfgenerate/vendor/autoload.php';
            
            $file_name = 'agreement_docs_'.date("M_d_Y h-i-s A").'_'.time().'.pdf'; 
            $file_name = preg_replace('/\s+/', '_', $file_name);
            $file_to_upload = ABSPATH . 'wp-content/uploads/'.$file_name;
            if (class_exists('\Mpdf\Mpdf')) {
                $mpdf = new \Mpdf\Mpdf();
                $mpdf->WriteHTML($data_pdf);

                $mpdf->Output($file_to_upload , \Mpdf\Output\Destination::FILE);

                $headers = 'From: Mamun <mamun@codexin.com>' . "\r\n";
                // wp_mail( 'rmamunur105@gmail.com', 'Pdf Test', 'Pleace check', $headers, $attachments );
              
                // wp_mail( string|array $to, 
                //     string $subject, string $message, 
                //     string|array $headers = '', 
                //     string|array $attachments = array() 
                // );

                $attachments = array( $file_to_upload );
                $to = array(
                    'rmamunur105@gmail.com',
                    'mamun@codexin.com'
                );
                wp_mail($to, 'Agreement signature form ', 'The message is for agreement signature form ', $headers , $attachments);
                 $mpdf->Output($file_name,"D");   
                foreach ($attachments as $file) {
                   unlink($file);
                }
                
               
            } 
        }else{
            echo "file is not exist";
        }
    }
}
/*
 https://euclid.codexin.com/checkout/order-received/527/?key=wc_order_jtaBoNQwFmfhM
*/
// add_action('init', 'pdf_genarator');

if (isset($_POST['email']) && isset($_POST['_order_number'])) { 

    $email = $_POST['email'];

    $_order_number =$_POST['_order_number'];

    $phone_number = $_POST['phone_number'];

    $country = $_POST['country'];

    $signature = '<img class="signature" src="'.$_POST['signature'].'" />';

    ob_start();
    ?>

    <!DOCTYPE html>
    <html>
    <head>
    <style>
    .signature{
        width: 200px; 
    }
    .pdf-footer{
        text-align: left;
    }
    .details{
        display: block;
        overflow: hidden;
    }
    .details .title{
        float: left;
        max-width: 100px;
        width: 100%;
    }
    .details .description{
        float: left;
        max-width: calc(100% - 120px);
        width: 100%;
    }

    </style>
    </head>
    <body>


        <div class="pdf-wrap">
            <div class="pdf-header">
                <h1 class="heading"> Here is contract details </h1>
            </div>
            <div class="pdf-body">
                <div class="details">
                    <table>
                        <tr>
                            <td> 
                                <div class="title" style="float: left">Emil:</div>
                            </td>
                            <td> 
                                 <div class="description">
                                    <?php echo $email ;?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="details">
                    <table>
                        <tr>
                            <td> 
                                <div class="title" style="float: left">Order Number:</div>
                            </td>
                            <td> 
                                 <div class="description">
                                   <?php echo $_order_number ;?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="details">
                    <table>
                        <tr>
                            <td> 
                                <div class="title" style="float: left">Phone Number:</div>
                            </td>
                            <td> 
                                 <div class="description">
                                   <?php echo $phone_number ;?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="details">
                    <table>
                        <tr>
                            <td> 
                                <div class="title" style="float: left">Country Name:</div>
                            </td>
                            <td> 
                                 <div class="description">
                                   <?php echo $country ;?>
                                </div>
                            </td>
                        </tr>
                    </table>
                </div>

                <div class="description">
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod
                        tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam,
                        quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo
                        consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse
                        cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non
                        proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                    </p>
                </div>

            </div>
            <div class="pdf-footer">
                <h6 class="signature"> Signature</h6>
                <?php echo $signature ;?>
            </div>
        </div>

    </body>
    </html>

    <?php
    $data_pdf = ob_get_clean();

    pdf_genarator($data_pdf) ;
}


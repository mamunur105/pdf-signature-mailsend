<?php

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


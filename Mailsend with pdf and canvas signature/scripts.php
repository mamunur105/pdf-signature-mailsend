<?php
function codexin_admin_scripts () {
	wp_enqueue_style( 'codexin-theme-options', get_template_directory_uri() . '/css/admin/admin-styles.css', false, '1.0','all');
}

function codexin_scripts () {

	//import icon fonts for materializecss
	wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css');
	
	//Load the stylesheets
	wp_enqueue_style( 'bootstrapcss', get_template_directory_uri() . '/css/bootstrap.min.css',false,'3.3.7','all');
	wp_enqueue_style( 'superfishcss', get_template_directory_uri() . '/css/superfish.css',false,'1.1','all');
	wp_enqueue_style( 'animatecss', get_template_directory_uri() . '/css/animate.min.css',false,'1.1','all');
	wp_enqueue_style( 'codexin-slick', get_template_directory_uri() . '/css/slick.css',false,'1.1','all');
	wp_enqueue_style( 'codexin-magnific-style', get_template_directory_uri() . '/css/magnific-popup.css',false,'1.1','all');

	wp_enqueue_style( 'superslides-style', get_template_directory_uri() . '/css/superslides.css',false,'1.1','all');
	

	wp_enqueue_style( 'wp-stylesheet', get_template_directory_uri() . '/css/wp-base.css',false,'1.1','all');
	wp_enqueue_style( 'codexin-shortcode-style', get_template_directory_uri() . '/css/shortcodes.css',false,'1.1','all');
	
	wp_enqueue_style( 'main-stylesheet', get_stylesheet_uri() );

	// wp_enqueue_style( 'codexin-responsive-menu', get_template_directory_uri() . '/css/responsive.css',false,'1.1','all');


	// Insert IE9 specific stylesheet in header
	wp_enqueue_style( 'IE9-styleshet', get_template_directory_uri() . '/css/ie9.css' );
	wp_style_add_data( 'IE9-styleshet', 'conditional', 'IE 9' );
	
	//load <=IE9 scripts
	wp_enqueue_script( 'htmlshiv', 'https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js', '', '3.7.3', false);
	wp_script_add_data( 'htmlshiv', 'conditional', 'lt IE 9' );
	wp_enqueue_script( 'respond', 'https://oss.maxcdn.com/respond/1.4.2/respond.min.js', '', '1.4.2', false);
	wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );
	

	// Load scripts
	wp_enqueue_script( 'bootstrapjs', get_template_directory_uri() . '/js/bootstrap.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'modernizr-custom', get_template_directory_uri() . '/js/modernizr-custom.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'easing', get_template_directory_uri() . '/js/jquery.easing.1.3.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'superfish', get_template_directory_uri() . '/js/superfish.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'wow', get_template_directory_uri() . '/js/wow.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'mobile-menu', get_template_directory_uri() . '/js/menu.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'codexin-slick-scripts', get_template_directory_uri() . '/js/slick.js', array ( 'jquery' ), 1.1, true);

	wp_enqueue_script( 'superslides-js', get_template_directory_uri() . '/js/jquery.backstretch.min.js', array ( 'jquery' ), 1.1, true);
	//wp_enqueue_script( 'codexin-parallax', get_template_directory_uri() . '/js/parallax.min.js', array ( 'jquery' ), 1.1, true);
	wp_enqueue_script( 'codexin-magnific-script', get_template_directory_uri() . '/js/jquery.magnific-popup.min.js', array ( 'jquery' ), 1.1, true);

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
	wp_enqueue_script( 'codexin-js', get_template_directory_uri() . '/js/codexin.js', array ( 'jquery' ), 1.1, true);

} // codexin_styles ()

add_action( 'admin_enqueue_scripts', 'codexin_admin_scripts');
add_action( 'wp_enqueue_scripts', 'codexin_scripts');

add_action( 'wp_enqueue_scripts', 'codexin_canvas_scripts',999);

function codexin_canvas_scripts () {
	
	if (is_order_received_page()) {

		wp_enqueue_style( 'codexin-canvas', get_template_directory_uri() . '/vendors/canvas/css/signature-pad.css', false, '1.0','all');

		wp_enqueue_script( 'codexin-signature_pad-script', get_template_directory_uri() . '/vendors/canvas/js/signature_pad.umd.js', array ( 'jquery' ), 1.1, true);

		wp_enqueue_script( 'codexin-canvas-script', get_template_directory_uri() . '/vendors/canvas/js/app.js', array ( 'jquery' ), 1.1, true);
	
	}

}


function cx_footer_script(){ ?>

	<?php if(is_front_page()){ ?>
		<script type="text/javascript">
			var $window 		= jQuery(window),
			$fillscreen 		= jQuery(".front-page");
		    $fillscreen.backstretch([
				<?php 
					$slider = codexin_get_option('cx_slider');
					if (!empty($slider)) {
						$ids = explode(',', $slider) ;
						foreach ($ids as $id) {
							$src = wp_get_attachment_image_src($id,'full');
							echo '"'.$src[0].'",';
						}
					}

				?>

		      ], {
		        duration: 4000,
	            fade: 750,
	            preload: 0,
	            start: 2
		    });

		</script>

	<?php }?>

<?php } 

add_action('wp_footer', 'cx_footer_script',105); 
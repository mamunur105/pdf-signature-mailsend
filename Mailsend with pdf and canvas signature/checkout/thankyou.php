<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @package 	WooCommerce/Templates
 * @version     3.2.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$_order_number = "";
$_billing_email = '';
$_total = '';
?>

<div class="woocommerce-order">

	<?php if ( $order ) : ?>

	<?php if ( $order->has_status( 'failed' ) ) : ?>

	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed">
		<?php _e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?>
	</p>

	<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
		<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
			class="button pay"><?php _e( 'Pay', 'woocommerce' ) ?></a>
		<?php if ( is_user_logged_in() ) : ?>
		<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
			class="button pay"><?php _e( 'My account', 'woocommerce' ); ?></a>
		<?php endif; ?>
	</p>

	<?php else : ?>

	<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
		<?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), $order ); ?>
	</p>

	<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">

		<li class="woocommerce-order-overview__order order">
			<?php _e( 'Order number:', 'woocommerce' ); ?>
			<strong><?php echo $order->get_order_number(); ?></strong>
		</li>
		<?php 
					$_order_number = $order->get_order_number();
				?>

		<li class="woocommerce-order-overview__date date">
			<?php _e( 'Date:', 'woocommerce' ); ?>
			<strong><?php echo wc_format_datetime( $order->get_date_created() ); ?></strong>
		</li>

		<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
		<li class="woocommerce-order-overview__email email">
			<?php _e( 'Email:', 'woocommerce' ); ?>
			<strong><?php echo $order->get_billing_email(); ?></strong>
		</li>

		<?php endif; ?>
		<?php 
					$_billing_email = $order->get_billing_email();
				?>

		<li class="woocommerce-order-overview__total total">
			<?php _e( 'Total:', 'woocommerce' ); ?>
			<strong><?php echo $order->get_formatted_order_total(); ?></strong>
		</li>
		<?php 
					$_total = $order->get_formatted_order_total();
				?>
		<?php if ( $order->get_payment_method_title() ) : ?>
		<li class="woocommerce-order-overview__payment-method method">
			<?php _e( 'Payment method:', 'woocommerce' ); ?>
			<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
		</li>
		<?php endif; ?>

	</ul>

	<!-- <form>
				<input type="text" name="" placeholder="Name" value="">
				<input type="text" name="" placeholder="email" value="">
				<input type="text" name="" placeholder="Phone" value="">
			</form> -->

	<?php endif; ?>

	<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
	<?php do_action( 'woocommerce_thankyou', $order->get_id() ); ?>

	<?php else : ?>

	<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received">
		<?php echo apply_filters( 'woocommerce_thankyou_order_received_text', __( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?>
	</p>

	<?php endif; ?>

	<div style="position:relative;">



<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_order_item_class', 'woocommerce-table__line-item order_item', $item, $order ) ); ?>">

	
	<td class="woocommerce-table__product-total product-total">
		<?php echo $order->get_formatted_line_subtotal( $item ); ?>
	</td>

</tr>

		
	<?php 

		$phone_number = '';
		$country = '';
		$details    =   ip_details();
		// print_r($details);
	?>
	<form action="/" method="post">

		<input type="text" name="email" value="<?php echo $_billing_email ?>">

		<br>
		<input type="text" name="_order_number" value="<?php echo $_order_number ?>">

		<input type="text" name="phone_number" placeholder="Phone" value="<?php echo $phone_number ?>">
		<input type="hidden" name="country" placeholder="Country" value="<?php echo $details->geoplugin_countryName; ?>">

		<br>
			<div id="signature-pad" class="signature-pad">
			<div class="signature-pad--body">
				<canvas></canvas>
			</div>
			<div class="signature-pad--footer">
				<div class="description">Sign above</div>

				<div class="signature-pad--actions">
					<div>
						<button type="button" class="button clear" data-action="clear">Clear</button>
						<button style="display:none" type="button" class="button" data-action="change-color">Change color</button>
						<button style="display:none"  type="button" class="button" data-action="undo">Undo</button>

					</div>
					<div>
						<button type="button" class="button save" data-action="save-png">Save Sign</button>
						<button style="display:none" type="hidden" class="button save" data-action="save-jpg">Save as JPG</button>
						<button style="display:none" type="hidden" class="button save" data-action="save-svg">Save as SVG</button>
					</div>
				</div>
			</div>
		</div>
		<br>
		<!-- <canvas id="canvas_value"></canvas> -->
		<div id="image_section">
			<img id="image_source" src="" />
		</div>
		<br/>
		<input type="hidden" name="signature" id="signature" value="">

		<input type="submit" value="Download Pdf" />

	</form>


</div>



	<?php 

	
	// $order_number = "Order NUmber ".$order->get_order_number();
	// $order_number = "Order NUmber ".$order->get_order_number();
	// // $order->get_billing_email();


	// $table = '<table style="width:100%">';
	// $data .= '<tr><td>Total </td><td>'.$_total.'</td></tr>';
	// $data .= '<tr><td>order number</td><td>'.$_order_number.'</td></tr>';
	// $data .= '<tr><td>Billing email</td><td>'.$_billing_email.'</td></tr>';
	// $data .= '</table>';


?>




</div>
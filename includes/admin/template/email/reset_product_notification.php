<?php
do_action( 'woocommerce_email_header', 'Bid reset to a product', $attributes );
?>
<p>
	<?php
	// translators: %s is the bidder username.
	echo wp_kses_post( sprintf( __( 'Hi %s,', 'yith-auctions-for-woocommerce' ), $attributes->seller_login ) );
	?>
</p>
<p>
	<?php echo wp_kses_post( sprintf( __( 'The bid for product  “<a href="%1$s"><strong>%2$s</strong></a>” has been reset due to the bidder wasn\'t able to pay.', 'yith-auctions-for-woocommerce' ), $attributes->get_permalink(), $attributes->get_title() ) );?>
</p>
<?php
$args = array(
	'product'      => $attributes,
	'url'          => $attributes->get_permalink(),
	'product_name' => $attributes->get_title(),
);
wc_get_template( 'product-email.php', $args, '', hybrid_get_path('/includes/admin/template/email/'));
?>

<div>
	<p><?php echo esc_html__( 'We will keep you updated!', 'yith-auctions-for-woocommerce' ); ?></p>
</div>
<div style="padding-top: 10px; padding-bottom: 10px;">
	<p><?php echo esc_html__( 'Regards,', 'yith-auctions-for-woocommerce' ); ?></p>
	<p><?php echo wp_kses_post( get_bloginfo( 'name' ) ) . ' ' . esc_html__( 'Staff', 'yith-auctions-for-woocommerce' ); ?></p>
</div>

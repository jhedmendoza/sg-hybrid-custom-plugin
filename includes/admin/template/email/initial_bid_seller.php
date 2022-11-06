<style>
.btn {display: inline-block;font-weight: 400;text-align: center;white-space: nowrap;vertical-align: middle;-webkit-user-select: none;-moz-user-select: none;  -ms-user-select: none;user-select: none;border: 1px solid transparent;  padding: 0.375rem 0.75rem;font-size: 1rem;line-height: 1.5;border-radius: 0.25rem;width:80px}
.btn-success {color: #fff;background-color: #28a745;border-color: #28a745;}
.btn-danger {color: #fff;background-color: #dc3545;border-color: #dc3545;}
.table-action {margin:0 auto}
.table-action td {padding:20px}
.table-action a {text-decoration: none}
</style>
<?php
do_action( 'woocommerce_email_header', 'New initial bid to a product', $attributes );
?>
<p>
	<?php
	// translators: %s is the bidder username.
	echo wp_kses_post( sprintf( __( 'Hi %s,', 'yith-auctions-for-woocommerce' ), $attributes->seller_login ) );
	?>
</p>
<p>
	<?php
	echo wp_kses_post( sprintf( __( 'There is a new initial bid to the product “<a href="%1$s"><strong>%2$s</strong></a>”.', 'yith-auctions-for-woocommerce' ), $attributes->get_permalink(), $attributes->get_title() ) );
	?>
</p>

<?php
$args = array(
	'product'      => $attributes,
	'url'          => $attributes->get_permalink(),
	'product_name' => $attributes->get_title(),
);
wc_get_template( 'product-email.php', $args, '', hybrid_get_path('/includes/admin/template/email/'));
?>
<table class="table-action">
	<tr>
		<td><a class="btn btn-success" href="#">Approve</a></td>
		<td><a class="btn btn-danger" href="#">Reject</a></td>
	</tr>
</table>
<div>
	<p><?php echo esc_html__( 'We will keep you updated!', 'yith-auctions-for-woocommerce' ); ?></p>
</div>
<div style="padding-top: 10px; padding-bottom: 10px;">
	<p><?php echo esc_html__( 'Regards,', 'yith-auctions-for-woocommerce' ); ?></p>
	<p><?php echo wp_kses_post( get_bloginfo( 'name' ) ) . ' ' . esc_html__( 'Staff', 'yith-auctions-for-woocommerce' ); ?></p>
</div>

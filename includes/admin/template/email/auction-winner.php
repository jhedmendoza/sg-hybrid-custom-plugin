<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_email_header','You won the auction', $attributes );

?>

<p>
	<?php
	// translators: %s is the bidder username.
	echo wp_kses_post( sprintf( __( 'Hi  %s,', 'yith-auctions-for-woocommerce' ), $attributes->user_login ) );
	?>
</p>
<p><?php esc_html_e( 'Congratulations, you\'re the winner of the auction:', 'yith-auctions-for-woocommerce' ); ?></p>

<?php

$args = array(
	'product'      => $attributes,
	'url'          => $attributes->get_permalink(),
	'product_name' => $attributes->get_title(),
	'bidder'			 => $attributes->bidder,
);

wc_get_template( 'product-email.php', $args, '', YITH_WCACT_PATH . 'templates/emails/product-emails/' );

/**
 * DO_ACTION: yith_wcact_after_content_winner_email
 *
 * Allow to render some content after the email content in the email sent to the auction winner.
 *
 * @param WC_Email $email Email object
 */
do_action( 'yith_wcact_after_content_winner_email', 'jhed@hybridanchor.com' );

?>

<div style="padding-top: 10px; padding-bottom: 10px;">
	<p><?php echo esc_html__( 'Regards,', 'yith-auctions-for-woocommerce' ); ?></p>
	<p>
		<?php
		// translators: %s is the blog name.
		printf( esc_html__( '%s Staff ', 'yith-auctions-for-woocommerce' ), esc_html( wp_specialchars_decode( get_bloginfo( 'name' ), ENT_QUOTES ) ) );
		?>
	</p>
</div>

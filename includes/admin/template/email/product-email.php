<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>

<div class="yith-wcact-auction-thumbnail-email" style=" margin: 20px 0px !important; padding: 10px; background-color:#f5f5f5">
	<table>
		<tr>
			<td>
				<?php
				/**
				 * APPLY_FILTERS: yith_wcact_email_auction_thumbnail
				 *
				 * Filter the auction product image in the emails.
				 *
				 * @param string $auction_thumbnail Auction thumbnail
				 *
				 * @return string
				 */
				echo wp_kses_post( apply_filters( 'yith_wcact_email_auction_thumbnail', '<img src="' . ( $product->get_image_id() ? current( wp_get_attachment_image_src( $product->get_image_id(), 'thumbnail' ) ) : wc_placeholder_img_src() ) . '" alt="' . esc_attr__( 'Item Image', 'yith-auctions-for-woocommerce' ) . '"width="150px" style="vertical-align:middle; margin-right: 10px;" />', $product ) );
				?>
			</td>
			<td>
				<a style="text-decoration: none;" target="_blank" href="<?php echo esc_url( $url ); ?>"><?php echo wp_kses_post( $product_name ); ?></a></br>
				<p class="ywcat-image-price" style="display: block; margin-bottom: 0px;"><span style="font-weight: 800 !important;"> <?php echo esc_html__( 'Current bid:', 'yith-auctions-for-woocommerce' ); ?> </span>
					<span> <?php echo wp_kses_post( isset( $bidder ) && !empty($bidder) ? wc_price( $bidder->bid ) : wc_price( $product->get_price() ) ); ?> </span>
				</p>
			</td>
		</tr>
	</table>
</div>

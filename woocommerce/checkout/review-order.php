<?php
/**
 * Review order table - Luxury Biossance Style
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-checkout-review-order-table-wrapper border border-gray-100 p-6">
	<table class="shop_table woocommerce-checkout-review-order-table w-full">
		<thead>
			<tr class="border-b border-gray-100">
				<th class="product-name text-left text-[11px] font-bold uppercase tracking-widest text-gray-400 pb-3"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-total text-right text-[11px] font-bold uppercase tracking-widest text-gray-400 pb-3"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
					$thumbnail = $_product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-16 h-20 object-cover bg-gray-50' ) );
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?> border-b border-gray-100">
						<td class="product-name py-4">
							<div class="flex items-center gap-4">
								<!-- Ảnh sản phẩm -->
								<div class="flex-shrink-0">
									<?php echo $thumbnail; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
								<!-- Tên và thông tin -->
								<div class="flex-grow">
									<div class="text-[11px] font-bold uppercase tracking-widest leading-tight mb-1">
										<?php echo wp_kses_post( $product_name ); ?>
									</div>
									<div class="text-[10px] text-gray-500 font-light">
										<?php echo apply_filters( 'woocommerce_checkout_cart_item_quantity', ' <span class="product-quantity">' . sprintf( '%s x', $cart_item['quantity'] ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
									</div>
									<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								</div>
							</div>
						</td>
						<td class="product-total text-right py-4 text-[12px] font-semibold">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>
					</tr>
					<?php
				}
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody>
		<tfoot>
			<tr class="cart-subtotal border-t border-gray-100">
				<th class="text-left text-[12px] font-bold uppercase tracking-widest py-3"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
				<td class="text-right text-[12px] font-semibold py-3"><?php wc_cart_totals_subtotal_html(); ?></td>
			</tr>

			<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
				<tr class="cart-discount coupon-<?php echo esc_attr( sanitize_title( $code ) ); ?> border-t border-gray-100">
					<th class="text-left text-[12px] font-bold uppercase tracking-widest py-3"><?php wc_cart_totals_coupon_label( $coupon ); ?></th>
					<td class="text-right text-[12px] font-semibold py-3"><?php wc_cart_totals_coupon_html( $coupon ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>
				<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>
				<?php wc_cart_totals_shipping_html(); ?>
				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>
			<?php endif; ?>

			<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
				<tr class="fee border-t border-gray-100">
					<th class="text-left text-[12px] font-bold uppercase tracking-widest py-3"><?php echo esc_html( $fee->name ); ?></th>
					<td class="text-right text-[12px] font-semibold py-3"><?php wc_cart_totals_fee_html( $fee ); ?></td>
				</tr>
			<?php endforeach; ?>

			<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
				<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
					<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
						<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?> border-t border-gray-100">
							<th class="text-left text-[12px] font-bold uppercase tracking-widest py-3"><?php echo esc_html( $tax->label ); ?></th>
							<td class="text-right text-[12px] font-semibold py-3"><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
						</tr>
					<?php endforeach; ?>
				<?php else : ?>
					<tr class="tax-total border-t border-gray-100">
						<th class="text-left text-[12px] font-bold uppercase tracking-widest py-3"><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
						<td class="text-right text-[12px] font-semibold py-3"><?php wc_cart_totals_taxes_total_html(); ?></td>
					</tr>
				<?php endif; ?>
			<?php endif; ?>

			<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

			<tr class="order-total border-t-2 border-black">
				<th class="text-left text-[13px] font-bold uppercase tracking-widest py-4"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
				<td class="text-right text-[13px] font-bold py-4"><?php wc_cart_totals_order_total_html(); ?></td>
			</tr>

			<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

		</tfoot>
	</table>
</div>


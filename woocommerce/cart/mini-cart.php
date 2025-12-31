<?php
/**
 * Mini-cart - Luxury Biossance Style
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 10.0.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( WC()->cart && ! WC()->cart->is_empty() ) : ?>

	<ul class="woocommerce-mini-cart cart_list product_list_widget space-y-0">
		<?php
		do_action( 'woocommerce_before_mini_cart_contents' );

		foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
			$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
			$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

			if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
				$product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
				$thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image( 'woocommerce_thumbnail' ), $cart_item, $cart_item_key );
				$product_price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
				$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
				?>
				<li class="woocommerce-mini-cart-item flex items-start gap-4 py-4 border-b border-gray-100 relative">
					<?php
					// Nút Remove - Góc phải trên
					echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
						'woocommerce_cart_item_remove_link',
						sprintf(
							'<a role="button" href="%s" class="remove remove_from_cart_button absolute top-0 right-0 text-gray-400 hover:text-black text-[10px] uppercase tracking-widest transition-colors" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-product_sku="%s">Remove</a>',
							esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
							esc_attr( sprintf( __( 'Remove %s from cart', 'woocommerce' ), wp_strip_all_tags( $product_name ) ) ),
							esc_attr( $product_id ),
							esc_attr( $cart_item_key ),
							esc_attr( $_product->get_sku() )
						),
						$cart_item_key
					);
					?>
					
					<!-- Ảnh sản phẩm -->
					<div class="flex-shrink-0 w-20 h-24 bg-gray-50 overflow-hidden">
						<?php if ( empty( $product_permalink ) ) : ?>
							<?php 
							// Thay thế class của ảnh để có kích thước cố định
							$thumbnail_html = str_replace( array( 'class="', 'class=\'' ), array( 'class="w-full h-full object-cover ', 'class=\'w-full h-full object-cover ' ), $thumbnail );
							echo $thumbnail_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
							?>
						<?php else : ?>
							<a href="<?php echo esc_url( $product_permalink ); ?>" class="block w-full h-full">
								<?php 
								$thumbnail_html = str_replace( array( 'class="', 'class=\'' ), array( 'class="w-full h-full object-cover ', 'class=\'w-full h-full object-cover ' ), $thumbnail );
								echo $thumbnail_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped 
								?>
							</a>
						<?php endif; ?>
					</div>

					<!-- Thông tin sản phẩm -->
					<div class="flex-grow min-w-0">
						<?php if ( empty( $product_permalink ) ) : ?>
							<h4 class="text-[12px] font-bold uppercase tracking-widest leading-tight mb-2">
								<?php echo wp_kses_post( $product_name ); ?>
							</h4>
						<?php else : ?>
							<h4 class="text-[12px] font-bold uppercase tracking-widest leading-tight mb-2">
								<a href="<?php echo esc_url( $product_permalink ); ?>" class="hover:text-[#7d6349] transition-colors">
									<?php echo wp_kses_post( $product_name ); ?>
								</a>
							</h4>
						<?php endif; ?>
						
						<?php echo wc_get_formatted_cart_item_data( $cart_item ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						
						<div class="text-[11px] text-gray-500 font-light tracking-wider mt-1">
							<?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s x %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</div>
				</li>
				<?php
			}
		}

		do_action( 'woocommerce_mini_cart_contents' );
		?>
	</ul>

	<?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>

	<!-- Ẩn buttons mặc định của WooCommerce, chúng ta sẽ dùng buttons trong footer.php -->
	<p class="woocommerce-mini-cart__buttons buttons hidden"><?php do_action( 'woocommerce_widget_shopping_cart_buttons' ); ?></p>

	<?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>

<?php else : ?>

	<p class="woocommerce-mini-cart__empty-message text-center py-20 text-gray-500 uppercase tracking-widest text-sm">
		<?php esc_html_e( 'No products in the cart.', 'woocommerce' ); ?>
	</p>

<?php endif; ?>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>


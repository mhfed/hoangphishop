<?php
/**
 * Checkout Form - Luxury Biossance Style
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_checkout_form', $checkout );

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
	return;
}

?>

<div class="checkout-page py-20 bg-white">
    <div class="max-w-[1400px] mx-auto px-6">
        <h1 class="text-3xl md:text-4xl font-light uppercase tracking-widest mb-12 text-center">Thanh toán</h1>

        <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data" aria-label="<?php echo esc_attr__( 'Checkout', 'woocommerce' ); ?>">

            <?php if ( $checkout->get_checkout_fields() ) : ?>

                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-16">
                    <!-- Cột trái: Thông tin thanh toán (7/12) -->
                    <div class="lg:col-span-7">
                        <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] text-gray-400 mb-8">Thông tin thanh toán</h2>
                        <div id="customer_details">
                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                        </div>
                    </div>

                    <!-- Cột phải: Tóm tắt đơn hàng (5/12) - Sticky -->
                    <div class="lg:col-span-5">
                        <div class="bg-[#f9f9f9] p-8 sticky top-24">
                            <h3 class="text-[13px] font-bold uppercase tracking-[0.3em] text-gray-400 mb-6">
                                Đơn hàng của bạn
                            </h3>
                            
                            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                            <div id="order_review" class="woocommerce-checkout-review-order">
                                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                            </div>

                            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                        </div>
                    </div>
                </div>

                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

            <?php endif; ?>

        </form>
    </div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>


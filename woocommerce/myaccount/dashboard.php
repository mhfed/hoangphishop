<?php
/**
 * My Account Dashboard - Luxury Tailwind Style
 *
 * Shows the first intro screen on the account dashboard.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/dashboard.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 4.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

$allowed_html = array(
	'a' => array(
		'href' => array(),
	),
);
?>

<div class="space-y-8">
    <div>
        <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-6 text-gray-900">Tổng quan tài khoản</h2>
        <p class="text-sm font-light text-gray-600 mb-4">
            <?php
            printf(
                /* translators: 1: user display name 2: logout url */
                wp_kses( __( 'Xin chào <strong>%1$s</strong> (không phải %1$s? <a href="%2$s" class="text-black hover:underline">Đăng xuất</a>)', 'woocommerce' ), $allowed_html ),
                esc_html( $current_user->display_name ),
                esc_url( wc_logout_url() )
            );
            ?>
        </p>
    </div>

    <div class="border-t border-gray-100 pt-8">
        <p class="text-sm font-light leading-relaxed text-gray-500 mb-6">
            <?php
            /* translators: 1: Orders URL 2: Address URL 3: Account URL. */
            $dashboard_desc = __( 'Từ bảng điều khiển tài khoản, bạn có thể xem <a href="%1$s" class="text-black hover:underline">đơn hàng gần đây</a>, quản lý <a href="%2$s" class="text-black hover:underline">địa chỉ thanh toán</a>, và <a href="%3$s" class="text-black hover:underline">chỉnh sửa mật khẩu và thông tin tài khoản</a>.', 'woocommerce' );
            if ( wc_shipping_enabled() ) {
                /* translators: 1: Orders URL 2: Addresses URL 3: Account URL. */
                $dashboard_desc = __( 'Từ bảng điều khiển tài khoản, bạn có thể xem <a href="%1$s" class="text-black hover:underline">đơn hàng gần đây</a>, quản lý <a href="%2$s" class="text-black hover:underline">địa chỉ giao hàng và thanh toán</a>, và <a href="%3$s" class="text-black hover:underline">chỉnh sửa mật khẩu và thông tin tài khoản</a>.', 'woocommerce' );
            }
            printf(
                wp_kses( $dashboard_desc, $allowed_html ),
                esc_url( wc_get_endpoint_url( 'orders' ) ),
                esc_url( wc_get_endpoint_url( 'edit-address' ) ),
                esc_url( wc_get_endpoint_url( 'edit-account' ) )
            );
            ?>
        </p>
    </div>

    <?php
        /**
         * My Account dashboard.
         *
         * @since 2.6.0
         */
        do_action( 'woocommerce_account_dashboard' );

        /**
         * Deprecated woocommerce_before_my_account action.
         *
         * @deprecated 2.6.0
         */
        do_action( 'woocommerce_before_my_account' );

        /**
         * Deprecated woocommerce_after_my_account action.
         *
         * @deprecated 2.6.0
         */
        do_action( 'woocommerce_after_my_account' );
    ?>
</div>


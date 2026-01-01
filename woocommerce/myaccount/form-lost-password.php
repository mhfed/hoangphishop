<?php
/**
 * Lost password form - Luxury Tailwind Style
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-lost-password.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.2.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_lost_password_form' );
?>

<div class="max-w-[600px] mx-auto px-6 py-16">
    <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-10 text-gray-900">Quên mật khẩu</h2>
    
    <?php
    // Hiển thị error messages với Tailwind
    wc_print_notices();
    ?>
    
    <p class="text-sm font-light leading-relaxed text-gray-500 mb-8">
        <?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( 'Quên mật khẩu? Vui lòng nhập tên đăng nhập hoặc địa chỉ email của bạn. Bạn sẽ nhận được liên kết để tạo mật khẩu mới qua email.', 'woocommerce' ) ); ?>
    </p>

    <form method="post" class="flex flex-col gap-6 woocommerce-ResetPassword lost_reset_password">

        <p class="flex flex-col gap-2">
            <label for="user_login" class="text-[11px] uppercase tracking-widest font-bold">
                <?php esc_html_e( 'Tên đăng nhập hoặc email', 'woocommerce' ); ?>
                <span class="text-red-500 ml-1">*</span>
            </label>
            <input 
                type="text" 
                class="w-full border border-gray-200 p-4 focus:border-black outline-none transition-all font-light text-sm" 
                name="user_login" 
                id="user_login" 
                autocomplete="username" 
                required 
                aria-required="true" 
            />
        </p>

        <?php do_action( 'woocommerce_lostpassword_form' ); ?>

        <input type="hidden" name="wc_reset_password" value="true" />
        <?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>
        
        <button 
            type="submit" 
            class="w-full bg-black text-white py-5 text-[11px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors" 
            value="<?php esc_attr_e( 'Đặt lại mật khẩu', 'woocommerce' ); ?>"
        >
            <?php esc_html_e( 'Đặt lại mật khẩu', 'woocommerce' ); ?>
        </button>

    </form>
    
    <div class="mt-8 pt-8 border-t border-gray-100">
        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="text-xs font-light text-gray-500 hover:text-black transition-colors">
            ← Quay lại đăng nhập
        </a>
    </div>
</div>

<?php
do_action( 'woocommerce_after_lost_password_form' );


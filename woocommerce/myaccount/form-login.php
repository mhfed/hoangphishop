<?php
/**
 * Login Form - Luxury Tailwind Style
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' ); ?>

<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>

<div class="max-w-[1200px] mx-auto px-6 py-16 grid grid-cols-1 lg:grid-cols-2 gap-20" id="customer_login">

    <div class="login-column">
        <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-10 text-gray-900">Đăng nhập</h2>
        
        <?php
        // Hiển thị error messages với Tailwind
        wc_print_notices();
        ?>
        
        <form class="flex flex-col gap-6 woocommerce-form woocommerce-form-login login" method="post" novalidate>

            <?php do_action( 'woocommerce_login_form_start' ); ?>

            <p class="flex flex-col gap-2">
                <label for="username" class="text-[11px] uppercase tracking-widest font-bold">
                    <?php esc_html_e( 'Email hoặc tên đăng nhập', 'woocommerce' ); ?>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input 
                    type="text" 
                    class="w-full border border-gray-200 p-4 focus:border-black outline-none transition-all font-light text-sm" 
                    name="username" 
                    id="username" 
                    autocomplete="username" 
                    value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" 
                    required 
                    aria-required="true" 
                />
            </p>
            
            <p class="flex flex-col gap-2">
                <label for="password" class="text-[11px] uppercase tracking-widest font-bold">
                    <?php esc_html_e( 'Mật khẩu', 'woocommerce' ); ?>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input 
                    type="password" 
                    class="w-full border border-gray-200 p-4 focus:border-black outline-none transition-all font-light text-sm" 
                    name="password" 
                    id="password" 
                    autocomplete="current-password" 
                    required 
                    aria-required="true" 
                />
            </p>

            <?php do_action( 'woocommerce_login_form' ); ?>

            <div class="flex items-center justify-between mt-2">
                <label class="flex items-center gap-2 text-xs font-light cursor-pointer">
                    <input 
                        type="checkbox" 
                        class="accent-black" 
                        name="rememberme" 
                        id="rememberme" 
                        value="forever" 
                    />
                    <?php esc_html_e( 'Ghi nhớ mật khẩu', 'woocommerce' ); ?>
                </label>
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-xs font-light hover:underline">
                    <?php esc_html_e( 'Quên mật khẩu?', 'woocommerce' ); ?>
                </a>
            </div>
            
            <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
            
            <button 
                type="submit" 
                class="w-full bg-black text-white py-5 text-[11px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors" 
                name="login" 
                value="<?php esc_attr_e( 'Đăng nhập', 'woocommerce' ); ?>"
            >
                <?php esc_html_e( 'Đăng nhập', 'woocommerce' ); ?>
            </button>

            <?php do_action( 'woocommerce_login_form_end' ); ?>

        </form>
    </div>

    <div class="register-column lg:border-l lg:pl-20 border-gray-100">
        <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-10 text-gray-900">Khách hàng mới</h2>
        <p class="text-sm font-light leading-relaxed text-gray-500 mb-8">
            Tạo tài khoản để nhận được những ưu đãi đặc quyền, theo dõi trạng thái đơn hàng và quản lý thông tin cá nhân một cách nhanh chóng.
        </p>
        
        <form method="post" class="flex flex-col gap-6 woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

            <?php do_action( 'woocommerce_register_form_start' ); ?>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                <p class="flex flex-col gap-2">
                    <label for="reg_username" class="text-[11px] uppercase tracking-widest font-bold">
                        <?php esc_html_e( 'Tên đăng nhập', 'woocommerce' ); ?>
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input 
                        type="text" 
                        class="w-full border border-gray-200 p-4 focus:border-black outline-none transition-all font-light text-sm" 
                        name="username" 
                        id="reg_username" 
                        autocomplete="username" 
                        value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" 
                        required 
                        aria-required="true" 
                    />
                </p>

            <?php endif; ?>

            <p class="flex flex-col gap-2">
                <label for="reg_email" class="text-[11px] uppercase tracking-widest font-bold">
                    <?php esc_html_e( 'Địa chỉ Email', 'woocommerce' ); ?>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input 
                    type="email" 
                    class="w-full border border-gray-200 p-4 focus:border-black outline-none transition-all font-light text-sm" 
                    name="email" 
                    id="reg_email" 
                    autocomplete="email" 
                    value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" 
                    required 
                    aria-required="true" 
                />
            </p>

            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                <p class="flex flex-col gap-2">
                    <label for="reg_password" class="text-[11px] uppercase tracking-widest font-bold">
                        <?php esc_html_e( 'Mật khẩu', 'woocommerce' ); ?>
                        <span class="text-red-500 ml-1">*</span>
                    </label>
                    <input 
                        type="password" 
                        class="w-full border border-gray-200 p-4 focus:border-black outline-none transition-all font-light text-sm" 
                        name="password" 
                        id="reg_password" 
                        autocomplete="new-password" 
                        required 
                        aria-required="true" 
                    />
                </p>

            <?php else : ?>

                <p class="text-xs font-light text-gray-400">
                    <?php esc_html_e( 'Một mật khẩu sẽ được gửi đến địa chỉ email của bạn.', 'woocommerce' ); ?>
                </p>

            <?php endif; ?>

            <?php do_action( 'woocommerce_register_form' ); ?>

            <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
            
            <button 
                type="submit" 
                class="w-full border border-black text-black py-5 text-[11px] font-bold uppercase tracking-[0.2em] hover:bg-black hover:text-white transition-all" 
                name="register" 
                value="<?php esc_attr_e( 'Tạo tài khoản', 'woocommerce' ); ?>"
            >
                <?php esc_html_e( 'Tạo tài khoản', 'woocommerce' ); ?>
            </button>

            <?php do_action( 'woocommerce_register_form_end' ); ?>

        </form>
    </div>

</div>

<?php else : ?>

    <!-- Nếu không bật registration, chỉ hiển thị form login -->
    <div class="max-w-[600px] mx-auto px-6 py-16">
        <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-10 text-gray-900">Đăng nhập</h2>
        
        <?php wc_print_notices(); ?>
        
        <form class="flex flex-col gap-6 woocommerce-form woocommerce-form-login login" method="post" novalidate>
            <?php do_action( 'woocommerce_login_form_start' ); ?>

            <p class="flex flex-col gap-2">
                <label for="username" class="text-[11px] uppercase tracking-widest font-bold">
                    <?php esc_html_e( 'Email hoặc tên đăng nhập', 'woocommerce' ); ?>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input 
                    type="text" 
                    class="w-full border border-gray-200 p-4 focus:border-black outline-none transition-all font-light text-sm" 
                    name="username" 
                    id="username" 
                    autocomplete="username" 
                    value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" 
                    required 
                    aria-required="true" 
                />
            </p>
            
            <p class="flex flex-col gap-2">
                <label for="password" class="text-[11px] uppercase tracking-widest font-bold">
                    <?php esc_html_e( 'Mật khẩu', 'woocommerce' ); ?>
                    <span class="text-red-500 ml-1">*</span>
                </label>
                <input 
                    type="password" 
                    class="w-full border border-gray-200 p-4 focus:border-black outline-none transition-all font-light text-sm" 
                    name="password" 
                    id="password" 
                    autocomplete="current-password" 
                    required 
                    aria-required="true" 
                />
            </p>

            <?php do_action( 'woocommerce_login_form' ); ?>

            <div class="flex items-center justify-between mt-2">
                <label class="flex items-center gap-2 text-xs font-light cursor-pointer">
                    <input 
                        type="checkbox" 
                        class="accent-black" 
                        name="rememberme" 
                        id="rememberme" 
                        value="forever" 
                    />
                    <?php esc_html_e( 'Ghi nhớ mật khẩu', 'woocommerce' ); ?>
                </label>
                <a href="<?php echo esc_url( wp_lostpassword_url() ); ?>" class="text-xs font-light hover:underline">
                    <?php esc_html_e( 'Quên mật khẩu?', 'woocommerce' ); ?>
                </a>
            </div>
            
            <?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
            
            <button 
                type="submit" 
                class="w-full bg-black text-white py-5 text-[11px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors" 
                name="login" 
                value="<?php esc_attr_e( 'Đăng nhập', 'woocommerce' ); ?>"
            >
                <?php esc_html_e( 'Đăng nhập', 'woocommerce' ); ?>
            </button>

            <?php do_action( 'woocommerce_login_form_end' ); ?>
        </form>
    </div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_customer_login_form' ); ?>


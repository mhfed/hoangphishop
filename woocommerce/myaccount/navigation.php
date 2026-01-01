<?php
/**
 * My Account navigation - Luxury Tailwind Style
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/navigation.php.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

do_action( 'woocommerce_before_account_navigation' );
?>

<nav class="woocommerce-MyAccount-navigation" aria-label="<?php esc_html_e( 'Account pages', 'woocommerce' ); ?>">
    <ul class="space-y-2 border-b border-gray-100 pb-6 mb-8">
        <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) : ?>
            <li class="<?php echo wc_get_account_menu_item_classes( $endpoint ); ?>">
                <a 
                    href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>" 
                    class="block py-3 text-[11px] font-bold uppercase tracking-widest text-gray-600 hover:text-black transition-colors <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'text-black border-l-2 border-black pl-4' : 'pl-0'; ?>"
                    <?php echo wc_is_current_account_menu_item( $endpoint ) ? 'aria-current="page"' : ''; ?>
                >
                    <?php echo esc_html( $label ); ?>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action( 'woocommerce_after_account_navigation' ); ?>


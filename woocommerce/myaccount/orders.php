<?php
/**
 * Orders - Luxury Tailwind Style
 *
 * Shows orders on the account page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/orders.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.5.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_account_orders', $has_orders ); ?>

<?php if ( $has_orders ) : ?>

    <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-8 text-gray-900">Đơn hàng của tôi</h2>

    <div class="overflow-x-auto">
        <table class="woocommerce-orders-table woocommerce-MyAccount-orders w-full border-collapse">
            <thead>
                <tr class="border-b border-gray-200">
                    <?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) : ?>
                        <th scope="col" class="text-left py-4 px-4 text-[11px] font-bold uppercase tracking-widest text-gray-400">
                            <?php echo esc_html( $column_name ); ?>
                        </th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php
                foreach ( $customer_orders->orders as $customer_order ) {
                    $order      = wc_get_order( $customer_order );
                    $item_count = $order->get_item_count() - $order->get_item_count_refunded();
                    ?>
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <?php foreach ( wc_get_account_orders_columns() as $column_id => $column_name ) :
                            $is_order_number = 'order-number' === $column_id;
                        ?>
                            <?php if ( $is_order_number ) : ?>
                                <th class="py-4 px-4 text-sm font-medium text-gray-900" data-title="<?php echo esc_attr( $column_name ); ?>" scope="row">
                            <?php else : ?>
                                <td class="py-4 px-4 text-sm font-light text-gray-600" data-title="<?php echo esc_attr( $column_name ); ?>">
                            <?php endif; ?>

                                <?php if ( has_action( 'woocommerce_my_account_my_orders_column_' . $column_id ) ) : ?>
                                    <?php do_action( 'woocommerce_my_account_my_orders_column_' . $column_id, $order ); ?>

                                <?php elseif ( $is_order_number ) : ?>
                                    <a href="<?php echo esc_url( $order->get_view_order_url() ); ?>" class="text-black hover:underline font-medium">
                                        <?php echo esc_html( _x( '#', 'hash before order number', 'woocommerce' ) . $order->get_order_number() ); ?>
                                    </a>

                                <?php elseif ( 'order-date' === $column_id ) : ?>
                                    <time datetime="<?php echo esc_attr( $order->get_date_created()->date( 'c' ) ); ?>">
                                        <?php echo esc_html( wc_format_datetime( $order->get_date_created() ) ); ?>
                                    </time>

                                <?php elseif ( 'order-status' === $column_id ) : ?>
                                    <span class="inline-block px-3 py-1 text-[10px] font-bold uppercase tracking-widest bg-gray-100 text-gray-700">
                                        <?php echo esc_html( wc_get_order_status_name( $order->get_status() ) ); ?>
                                    </span>

                                <?php elseif ( 'order-total' === $column_id ) : ?>
                                    <?php
                                    echo wp_kses_post( sprintf( _n( '%1$s cho %2$s sản phẩm', '%1$s cho %2$s sản phẩm', $item_count, 'woocommerce' ), $order->get_formatted_order_total(), $item_count ) );
                                    ?>

                                <?php elseif ( 'order-actions' === $column_id ) : ?>
                                    <?php
                                    $actions = wc_get_account_orders_actions( $order );

                                    if ( ! empty( $actions ) ) {
                                        foreach ( $actions as $key => $action ) {
                                            if ( empty( $action['aria-label'] ) ) {
                                                $action_aria_label = sprintf( __( '%1$s đơn hàng số %2$s', 'woocommerce' ), $action['name'], $order->get_order_number() );
                                            } else {
                                                $action_aria_label = $action['aria-label'];
                                            }
                                            echo '<a href="' . esc_url( $action['url'] ) . '" class="text-[10px] font-bold uppercase tracking-widest text-black hover:underline" aria-label="' . esc_attr( $action_aria_label ) . '">' . esc_html( $action['name'] ) . '</a>';
                                            unset( $action_aria_label );
                                        }
                                    }
                                    ?>
                                <?php endif; ?>

                            <?php if ( $is_order_number ) : ?>
                                </th>
                            <?php else : ?>
                                </td>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tr>
                    <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <?php do_action( 'woocommerce_before_account_orders_pagination' ); ?>

    <?php if ( 1 < $customer_orders->max_num_pages ) : ?>
        <div class="flex items-center justify-between mt-8 pt-8 border-t border-gray-100">
            <?php if ( 1 !== $current_page ) : ?>
                <a class="text-[11px] font-bold uppercase tracking-widest text-black hover:underline" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page - 1 ) ); ?>">
                    ← <?php esc_html_e( 'Trước', 'woocommerce' ); ?>
                </a>
            <?php else : ?>
                <span></span>
            <?php endif; ?>

            <?php if ( intval( $customer_orders->max_num_pages ) !== $current_page ) : ?>
                <a class="text-[11px] font-bold uppercase tracking-widest text-black hover:underline" href="<?php echo esc_url( wc_get_endpoint_url( 'orders', $current_page + 1 ) ); ?>">
                    <?php esc_html_e( 'Sau', 'woocommerce' ); ?> →
                </a>
            <?php else : ?>
                <span></span>
            <?php endif; ?>
        </div>
    <?php endif; ?>

<?php else : ?>

    <div class="text-center py-16">
        <p class="text-sm font-light text-gray-500 mb-6">
            <?php esc_html_e( 'Bạn chưa có đơn hàng nào.', 'woocommerce' ); ?>
        </p>
        <a class="inline-block bg-black text-white px-8 py-4 text-[11px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 transition-colors" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>">
            <?php esc_html_e( 'Xem sản phẩm', 'woocommerce' ); ?>
        </a>
    </div>

<?php endif; ?>

<?php do_action( 'woocommerce_after_account_orders', $has_orders ); ?>


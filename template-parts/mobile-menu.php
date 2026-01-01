<?php
/**
 * Mobile Menu - Luxury Style Slide-out Drawer
 * 
 * @package HoangPhi_Theme
 */
?>

<div id="mobile-menu" class="fixed inset-0 z-[99] pointer-events-none">
    <div id="mobile-menu-overlay" class="absolute top-20 left-0 right-0 bottom-0 bg-black/40 opacity-0 transition-opacity duration-500 pointer-events-auto"></div>
    
    <div id="mobile-menu-content" class="absolute top-0 right-0 w-[85%] max-w-[400px] h-full bg-white shadow-2xl translate-x-full transition-transform duration-500 ease-in-out pointer-events-auto flex flex-col min-h-[100vh] open-mobile-menu">
        
        <div class="flex-none p-6 flex justify-between items-center border-b border-gray-100">
            <span class="text-[10px] font-bold uppercase tracking-[0.3em] text-gray-400">Hoàng Phi Menu</span>
            <button id="close-mobile-menu" class="p-2 flex items-center gap-2 text-[11px] uppercase tracking-widest font-medium">
                Đóng <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="flex-1 overflow-y-auto overflow-x-hidden px-8 py-10 custom-scrollbar">
            <?php
            // Lấy menu items từ menu location 'primary' (giống header.php)
            $menu_location = 'primary';
            $menu_locations = get_nav_menu_locations();
            $menu_items = array();
            
            if ( isset( $menu_locations[ $menu_location ] ) ) {
                $menu = wp_get_nav_menu_object( $menu_locations[ $menu_location ] );
                if ( $menu ) {
                    $menu_items = wp_get_nav_menu_items( $menu->term_id );
                }
            }

            if ( ! empty( $menu_items ) ) :
                $settings_id = 80; // ID trang cài đặt ACF
                ?>
                <ul class="space-y-2">
                    <?php foreach ( $menu_items as $item ) : 
                        // Chỉ xử lý menu items cấp cao nhất (parent = 0)
                        if ( $item->menu_item_parent != 0 ) {
                            continue;
                        }

                        // Lấy ACF field menu_type cho menu item
                        $menu_type = get_field( 'menu_type', $item->ID );
                        $has_mega = in_array( $menu_type, array( 'mega_text', 'mega_products' ) );
                    ?>
                        <li class="mobile-menu-item border-b border-gray-100">
                            <div class="flex justify-between items-center py-4">
                                <a href="<?php echo esc_url( $item->url ); ?>" class="text-[16px] font-bold uppercase tracking-widest text-gray-900 hover:text-[#7d6349] transition-colors flex-grow">
                                    <?php echo esc_html( $item->title ); ?>
                                </a>
                                <?php if ( $has_mega ) : ?>
                                    <button class="mobile-menu-toggle p-2 text-xl text-gray-400 hover:text-black transition-colors" data-menu-type="<?php echo esc_attr( $menu_type ); ?>">
                                        <span class="toggle-icon">+</span>
                                    </button>
                                <?php endif; ?>
                            </div>

                            <?php if ( $menu_type == 'mega_text' ) : ?>
                                <!-- Mega Menu Text: 3 cột links -->
                                <div class="mobile-mega-content max-h-0 overflow-hidden transition-all duration-300">
                                    <div class="pb-6 space-y-8">
                                        <?php
                                        // Lấy dữ liệu từ 3 field Textarea
                                        $col_1_links = get_field( 'mega_col_1_links', $settings_id );
                                        $col_2_links = get_field( 'mega_col_2_links', $settings_id );
                                        $col_3_links = get_field( 'mega_col_3_links', $settings_id );

                                        // Parse links từ textarea
                                        $col_1_array = ! empty( $col_1_links ) ? array_filter( array_map( 'trim', explode( "\n", $col_1_links ) ) ) : array();
                                        $col_2_array = ! empty( $col_2_links ) ? array_filter( array_map( 'trim', explode( "\n", $col_2_links ) ) ) : array();
                                        $col_3_array = ! empty( $col_3_links ) ? array_filter( array_map( 'trim', explode( "\n", $col_3_links ) ) ) : array();

                                        // Hiển thị 3 cột xếp chồng (mobile)
                                        $all_columns = array(
                                            array( 'title' => 'DANH MỤC SẢN PHẨM', 'links' => $col_1_array ),
                                            array( 'title' => 'GIẢI PHÁP CHO DA', 'links' => $col_2_array ),
                                            array( 'title' => 'NỔI BẬT', 'links' => $col_3_array ),
                                        );
                                        ?>
                                        <?php foreach ( $all_columns as $column ) : 
                                            if ( ! empty( $column['links'] ) ) : ?>
                                                <div>
                                                    <h3 class="text-[11px] font-bold uppercase tracking-widest text-gray-400 pb-2 border-b border-gray-100 mb-4">
                                                        <?php echo esc_html( $column['title'] ); ?>
                                                    </h3>
                                                    <div class="space-y-3">
                                                        <?php foreach ( $column['links'] as $link_text ) : ?>
                                                            <?php
                                                            $link_parts = explode( '|', $link_text );
                                                            $link_text_display = trim( $link_parts[0] );
                                                            $link_url = isset( $link_parts[1] ) ? trim( $link_parts[1] ) : '#';
                                                            ?>
                                                            <a href="<?php echo esc_url( $link_url ); ?>" class="block text-[13px] font-bold uppercase tracking-wider text-gray-900 hover:text-[#7d6349] transition-colors">
                                                                <?php echo esc_html( $link_text_display ); ?>
                                                            </a>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                            <?php endif;
                                        endforeach; ?>
                                    </div>
                                </div>

                            <?php elseif ( $menu_type == 'mega_products' ) : ?>
                                <!-- Mega Menu Products: 5 sản phẩm bán chạy -->
                                <div class="mobile-mega-content max-h-0 overflow-hidden transition-all duration-300">
                                    <div class="pb-6">
                                        <?php
                                        $bestsellers_args = array(
                                            'post_type'      => 'product',
                                            'posts_per_page' => 5,
                                            'meta_key'       => 'total_sales',
                                            'orderby'        => 'meta_value_num',
                                            'order'          => 'DESC',
                                        );
                                        $bestsellers_query = new WP_Query( $bestsellers_args );
                                        
                                        if ( $bestsellers_query->have_posts() ) : ?>
                                            <div class="space-y-4">
                                                <?php while ( $bestsellers_query->have_posts() ) : $bestsellers_query->the_post();
                                                    global $product;
                                                    if ( ! $product ) continue;
                                                ?>
                                                    <a href="<?php the_permalink(); ?>" class="flex items-center gap-4 group">
                                                        <div class="w-16 h-20 bg-gray-100 overflow-hidden flex-shrink-0">
                                                            <?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-cover' ) ); ?>
                                                        </div>
                                                        <div class="flex-grow">
                                                            <h4 class="text-[11px] font-bold uppercase tracking-widest text-gray-900 group-hover:text-[#7d6349] transition-colors mb-1">
                                                                <?php the_title(); ?>
                                                            </h4>
                                                            <p class="text-[10px] text-gray-500">
                                                                <?php echo $product->get_price_html(); ?>
                                                            </p>
                                                        </div>
                                                    </a>
                                                <?php endwhile; ?>
                                            </div>
                                            <?php wp_reset_postdata(); ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else : ?>
                <ul class="space-y-2">
                    <li class="border-b border-gray-100">
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="block py-4 text-[16px] font-bold uppercase tracking-widest text-gray-900">
                            Trang chủ
                        </a>
                    </li>
                </ul>
            <?php endif; ?>
        </div>

        <div class="flex-none p-8 bg-gray-50 border-t border-gray-100">
             <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="text-[11px] font-bold uppercase tracking-[0.2em] block mb-4">Tài khoản cá nhân</a>
             <p class="text-[9px] text-gray-400 tracking-widest uppercase italic">© 2026 HOANG PHI BEAUTY</p>
        </div>
    </div>
</div>

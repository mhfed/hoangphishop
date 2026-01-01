<header class="sticky top-0 z-[100] bg-white/90 backdrop-blur-sm border-b border-gray-100">
    <div class="relative max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <!-- Mobile Menu Button - Bên trái trên mobile -->
        <button id="open-mobile-menu" class="lg:hidden p-2 hover:opacity-60 transition relative z-[9999]">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>

        <!-- Logo - Ẩn trên mobile, hiện trên desktop -->
        <div class="text-2xl font-light tracking-[0.3em] text-gray-900 hidden lg:block">
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>">HOANG PHI</a>
        </div>

        <nav class="hidden lg:flex items-center space-x-10">
            <?php
            // Lấy menu items từ menu location 'primary' (hoặc menu chính)
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
                foreach ( $menu_items as $item ) :
                    // Chỉ xử lý menu items cấp cao nhất (parent = 0)
                    if ( $item->menu_item_parent != 0 ) {
                        continue;
                    }

                    // Lấy ACF field menu_type cho menu item
                    $menu_type = get_field( 'menu_type', $item->ID );
                    
                    // Nếu có mega menu
                    if ( in_array( $menu_type, array( 'mega_text', 'mega_products' ) ) ) :
                        ?>
                        <div class="group py-6 relative">
                            <a href="<?php echo esc_url( $item->url ); ?>" class="text-[12px] font-bold uppercase tracking-widest hover:text-[#7d6349] transition-colors">
                                <?php echo esc_html( $item->title ); ?>
                            </a>
                            
                            <?php if ( $menu_type == 'mega_text' ) : ?>
                                <!-- Mega Menu 3 cột: Lấy dữ liệu từ ACF Textarea -->
                                <div class="fixed left-0 top-20 w-full bg-white border-t border-gray-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[60]">
                                    <?php
                                    // ID của trang cài đặt ACF - Thay số này bằng ID thực tế của trang cài đặt
                                    $settings_id = 80; // TODO: Thay số này bằng ID thực tế của trang cài đặt

                                    // Lấy dữ liệu từ 3 field Textarea
                                    $col_1_links = get_field( 'mega_col_1_links', $settings_id );
                                    $col_2_links = get_field( 'mega_col_2_links', $settings_id );
                                    $col_3_links = get_field( 'mega_col_3_links', $settings_id );

                                    // Sử dụng explode để chia textarea thành mảng các dòng
                                    $col_1_array = ! empty( $col_1_links ) ? explode( "\n", $col_1_links ) : array();
                                    $col_2_array = ! empty( $col_2_links ) ? explode( "\n", $col_2_links ) : array();
                                    $col_3_array = ! empty( $col_3_links ) ? explode( "\n", $col_3_links ) : array();

                                    // Loại bỏ các dòng trống và trim whitespace
                                    $col_1_array = array_filter( array_map( 'trim', $col_1_array ) );
                                    $col_2_array = array_filter( array_map( 'trim', $col_2_array ) );
                                    $col_3_array = array_filter( array_map( 'trim', $col_3_array ) );
                                    ?>
                                    <div class="max-w-[1400px] mx-auto px-6 py-10">
                                        <div class="grid grid-cols-3 gap-12">
                                        
                                        <!-- Cột 1: Shop by Category -->
                                        <div>
                                            <h3 class="text-[11px] font-bold uppercase tracking-widest text-gray-400 pb-2 border-b mb-6">SHOP BY CATEGORY</h3>
                                            <?php if ( ! empty( $col_1_array ) ) : ?>
                                                <?php foreach ( $col_1_array as $link_text ) : ?>
                                                    <?php
                                                    // Parse link: Format có thể là "Text|URL" hoặc chỉ "Text" (dùng #)
                                                    $link_parts = explode( '|', $link_text );
                                                    $link_text_display = trim( $link_parts[0] );
                                                    $link_url = isset( $link_parts[1] ) ? trim( $link_parts[1] ) : '#';
                                                    ?>
                                                    <a href="<?php echo esc_url( $link_url ); ?>" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">
                                                        <?php echo esc_html( $link_text_display ); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <!-- Fallback nếu không có dữ liệu -->
                                                <a href="#" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">Sữa rửa mặt</a>
                                                <a href="#" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">Kem dưỡng</a>
                                                <a href="#" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">Tinh chất</a>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Cột 2: Shop by Concern -->
                                        <div>
                                            <h3 class="text-[11px] font-bold uppercase tracking-widest text-gray-400 pb-2 border-b mb-6">SHOP BY CONCERN</h3>
                                            <?php if ( ! empty( $col_2_array ) ) : ?>
                                                <?php foreach ( $col_2_array as $link_text ) : ?>
                                                    <?php
                                                    $link_parts = explode( '|', $link_text );
                                                    $link_text_display = trim( $link_parts[0] );
                                                    $link_url = isset( $link_parts[1] ) ? trim( $link_parts[1] ) : '#';
                                                    ?>
                                                    <a href="<?php echo esc_url( $link_url ); ?>" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">
                                                        <?php echo esc_html( $link_text_display ); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <!-- Fallback nếu không có dữ liệu -->
                                                <a href="#" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">Trị mụn</a>
                                                <a href="#" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">Chống lão hóa</a>
                                                <a href="#" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">Dưỡng sáng</a>
                                            <?php endif; ?>
                                        </div>

                                        <!-- Cột 3: Featured -->
                                        <div>
                                            <h3 class="text-[11px] font-bold uppercase tracking-widest text-gray-400 pb-2 border-b mb-6">FEATURED</h3>
                                            <?php if ( ! empty( $col_3_array ) ) : ?>
                                                <?php foreach ( $col_3_array as $link_text ) : ?>
                                                    <?php
                                                    $link_parts = explode( '|', $link_text );
                                                    $link_text_display = trim( $link_parts[0] );
                                                    $link_url = isset( $link_parts[1] ) ? trim( $link_parts[1] ) : '#';
                                                    ?>
                                                    <a href="<?php echo esc_url( $link_url ); ?>" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">
                                                        <?php echo esc_html( $link_text_display ); ?>
                                                    </a>
                                                <?php endforeach; ?>
                                            <?php else : ?>
                                                <!-- Fallback nếu không có dữ liệu -->
                                                <a href="#" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">New Arrival</a>
                                                <a href="#" class="text-[13px] font-bold uppercase tracking-wider text-black hover:text-[#7d6349] block mb-4 transition-colors">Best Seller</a>
                                            <?php endif; ?>
                                        </div>
                                        </div>
                                    </div>
                                </div>
                            
                            <?php elseif ( $menu_type == 'mega_products' ) : ?>
                                <!-- Mega Menu 5 cột: Product Cards -->
                                <div class="fixed left-0 top-20 w-full bg-white border-t border-gray-100 shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[60]">
                                    <div class="max-w-[1400px] mx-auto p-10 grid grid-cols-5 gap-6">
                                        <?php
                                        $best_args = array(
                                            'post_type' => 'product',
                                            'posts_per_page' => 5,
                                            'meta_key' => 'total_sales',
                                            'orderby' => 'meta_value_num',
                                            'order' => 'DESC',
                                        );
                                        $best_query = new WP_Query( $best_args );
                                        
                                        if ( $best_query->have_posts() ) :
                                            while ( $best_query->have_posts() ) : $best_query->the_post();
                                                global $product;
                                                ?>
                                                <a href="<?php the_permalink(); ?>" class="group/card text-center">
                                                    <div class="relative border border-gray-100 mb-4 overflow-hidden bg-white">
                                                        <?php 
                                                        the_post_thumbnail( 'medium', array(
                                                            'class' => 'w-full aspect-square object-cover transition-transform duration-500 group-hover/card:scale-105'
                                                        ) ); 
                                                        ?>
                                                        <?php if ( $product->is_on_sale() || $product->get_total_sales() > 0 ) : ?>
                                                            <span class="absolute top-3 left-3 bg-[#7d6349] text-white text-[9px] font-bold py-2 px-2 rounded-full leading-none">
                                                                <?php echo $product->get_total_sales() > 10 ? 'BESTSELLER' : 'NEW'; ?>
                                                            </span>
                                                        <?php endif; ?>
                                                    </div>
                                                    <h4 class="text-[10px] font-bold uppercase tracking-widest leading-tight px-2 text-gray-900">
                                                        <?php the_title(); ?>
                                                    </h4>
                                                </a>
                                                <?php
                                            endwhile;
                                        else :
                                            // Fallback
                                            for ( $i = 1; $i <= 5; $i++ ) :
                                                ?>
                                                <a href="#" class="group/card text-center">
                                                    <div class="relative border border-gray-100 mb-4 overflow-hidden bg-gray-50 aspect-square flex items-center justify-center">
                                                        <span class="text-gray-400 text-xs">Product <?php echo $i; ?></span>
                                                        <span class="absolute top-3 left-3 bg-[#7d6349] text-white text-[9px] font-bold py-2 px-2 rounded-full leading-none">NEW</span>
                                                    </div>
                                                    <h4 class="text-[10px] font-bold uppercase tracking-widest leading-tight px-2 text-gray-900">
                                                        Sample Product <?php echo $i; ?>
                                                    </h4>
                                                </a>
                                                <?php
                                            endfor;
                                        endif;
                                        wp_reset_postdata();
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    
                    <?php else : ?>
                        <!-- Menu item bình thường (không có mega menu) -->
                        <a href="<?php echo esc_url( $item->url ); ?>" class="text-[12px] font-bold uppercase tracking-widest hover:text-[#7d6349] transition-colors">
                            <?php echo esc_html( $item->title ); ?>
                        </a>
                    <?php endif; ?>
                    
                <?php endforeach; ?>
            <?php else : ?>
                <!-- Fallback nếu không có menu -->
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="text-[12px] font-bold uppercase tracking-widest hover:text-[#7d6349] transition-colors">
                    Trang chủ
                </a>
            <?php endif; ?>
        </nav>

        <!-- Icons bên phải (Search, Cart) -->
        <div class="flex items-center space-x-6">
            <button id="open-search" class="hover:opacity-60 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg></button>
            <div class="relative group cursor-pointer">
                <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="Xem giỏ hàng">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span
                        class="cart-contents-count cart-count absolute -top-2 -right-2 bg-black text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <?php wp_head(); ?>
    <?php get_template_part( 'template-parts/search-overlay' ); ?>
    <?php get_template_part( 'template-parts/mobile-menu' ); ?>
</header>

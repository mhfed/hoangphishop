<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-sm border-b border-gray-100">
    <div class="relative max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="text-2xl font-light tracking-[0.3em] text-gray-900">
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
                                <!-- Mega Menu 3 cột: Category, Concern, Featured -->
                                <div class="absolute left-1/2 -translate-x-1/2 top-full w-screen bg-white border-t border-gray-100 shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[60]">
                                    <div class="max-w-[1400px] mx-auto grid grid-cols-12 gap-8 p-12">
                                        
                                        <!-- Cột 1: Shop by Category (Dynamic từ WooCommerce) -->
                                        <div class="col-span-3">
                                            <h3 class="text-[12px] font-bold uppercase tracking-[0.1em] text-gray-900 mb-4 pb-3 border-b border-gray-200">SHOP BY CATEGORY</h3>
                                            <ul class="space-y-4 mt-4">
                                                <?php
                                                $product_categories = get_terms( array(
                                                    'taxonomy'   => 'product_cat',
                                                    'hide_empty' => true,
                                                    'number'     => 8,
                                                    'orderby'    => 'count',
                                                    'order'      => 'DESC',
                                                ) );

                                                if ( ! empty( $product_categories ) && ! is_wp_error( $product_categories ) ) :
                                                    foreach ( $product_categories as $category ) :
                                                        $category_link = get_term_link( $category );
                                                        ?>
                                                        <li>
                                                            <a href="<?php echo esc_url( $category_link ); ?>" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">
                                                                <?php echo esc_html( $category->name ); ?>
                                                            </a>
                                                        </li>
                                                        <?php
                                                    endforeach;
                                                else :
                                                    // Fallback
                                                    ?>
                                                    <li><a href="#" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">Sữa rửa mặt (Cleansers)</a></li>
                                                    <li><a href="#" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">Kem dưỡng (Moisturizers)</a></li>
                                                    <li><a href="#" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">Tinh chất (Serums)</a></li>
                                                    <li><a href="#" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">Kem chống nắng</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>

                                        <!-- Cột 2: Shop by Concern -->
                                        <div class="col-span-3 border-l border-gray-50 pl-8">
                                            <h3 class="text-[12px] font-bold uppercase tracking-[0.1em] text-gray-900 mb-4 pb-3 border-b border-gray-200">SHOP BY CONCERN</h3>
                                            <ul class="space-y-4 mt-4">
                                                <li><a href="<?php echo esc_url( add_query_arg( 'filter', 'acne', wc_get_page_permalink( 'shop' ) ) ); ?>" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">Trị mụn & Lỗ chân lông</a></li>
                                                <li><a href="<?php echo esc_url( add_query_arg( 'filter', 'anti-aging', wc_get_page_permalink( 'shop' ) ) ); ?>" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">Chống lão hóa</a></li>
                                                <li><a href="<?php echo esc_url( add_query_arg( 'filter', 'brightening', wc_get_page_permalink( 'shop' ) ) ); ?>" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">Dưỡng sáng da</a></li>
                                                <li><a href="<?php echo esc_url( add_query_arg( 'filter', 'hydration', wc_get_page_permalink( 'shop' ) ) ); ?>" class="text-[14px] text-gray-700 hover:text-[#7d6349] hover:font-semibold transition-all duration-200">Cấp ẩm sâu</a></li>
                                            </ul>
                                        </div>

                                        <!-- Cột 3: Featured Image (ACF hoặc Fallback) -->
                                        <div class="col-span-6 grid grid-cols-2 gap-4">
                                            <?php
                                            $settings_id = 80; // TODO: Thay bằng ID thực tế

                                            $m_image = get_field( 'mega_menu_featured_image', $settings_id );
                                            $m_title = get_field( 'mega_menu_featured_title', $settings_id );
                                            $m_sub   = get_field( 'mega_menu_featured_subtitle', $settings_id );
                                            $m_link  = get_field( 'mega_menu_featured_link', $settings_id );

                                            $img_url = '';
                                            if ( is_array( $m_image ) && isset( $m_image['url'] ) ) {
                                                $img_url = $m_image['url'];
                                            } elseif ( is_numeric( $m_image ) ) {
                                                $img_url = wp_get_attachment_url( $m_image );
                                            } elseif ( ! empty( $m_image ) ) {
                                                $img_url = $m_image;
                                            }

                                            $mega_menu_image = ! empty( $img_url ) ? $img_url : 'https://images.unsplash.com/photo-1556228578-0d85b1a4d571?w=800&q=80';
                                            $mega_menu_title = ! empty( $m_title ) ? $m_title : 'New Arrival';
                                            $mega_menu_subtitle = ! empty( $m_sub ) ? $m_sub : 'Squalane + Vitamin C Rose Oil';
                                            $mega_menu_link = ! empty( $m_link ) ? $m_link : '#';
                                            ?>
                                            <div class="relative overflow-hidden group/item h-full bg-[#f6f6f6] min-h-[300px]">
                                                <img src="<?php echo esc_url( $mega_menu_image ); ?>" class="w-full h-full object-cover transition-transform duration-700 group-hover/item:scale-105" alt="<?php echo esc_attr( $mega_menu_title ); ?>">
                                                <div class="absolute inset-0 bg-black/10 flex flex-col justify-end p-6">
                                                    <span class="text-white text-[10px] font-bold uppercase tracking-widest mb-2"><?php echo esc_html( $mega_menu_title ); ?></span>
                                                    <h4 class="text-white text-[18px] font-medium mb-4"><?php echo esc_html( $mega_menu_subtitle ); ?></h4>
                                                    <a href="<?php echo esc_url( $mega_menu_link ); ?>" class="text-white text-[11px] font-bold uppercase border-b border-white w-fit hover:opacity-80 transition-opacity">Shop Now</a>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                </div>
                            
                            <?php elseif ( $menu_type == 'mega_products' ) : ?>
                                <!-- Mega Menu 5 cột: Product Cards -->
                                <div class="absolute left-1/2 -translate-x-1/2 top-full w-screen bg-white border-t border-gray-100 shadow-sm opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 z-[60]">
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

        <div class="flex items-center space-x-6">
            <button class="hover:opacity-60 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor"
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
</header>

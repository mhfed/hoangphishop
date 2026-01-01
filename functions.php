<?php
/**
 * Hoang Phi Theme Functions
 * Thiết lập cho Frontend Developer - Tailwind + Swiper + WooCommerce
 */

function hoangphi_assets() {
    // 1. Preload Font quan trọng nhất (Inter 300 - font chính)
    add_action('wp_head', function() {
        echo '<link rel="preload" href="https://fonts.gstatic.com/s/inter/v13/UcCO3FwrK3iLTeHuS_fvQtMwCp50KnMw2boKoduKmMEVuLyfAZ9hiJ-Ek-_EeA.woff2" as="font" type="font/woff2" crossorigin>';
    }, 1);
    
    // 2. Google Fonts - Load trước để tránh nhảy font (FOUT) với font-display: swap
    wp_enqueue_style('hoangphi-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap', array(), null);
    
    // 2. Swiper CSS - Dùng cho phần Product Reels
    wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '11.0.0');
    
    // 3. TAILWIND CSS (File style.css ở root theme)
    // Dùng time() để buộc trình duyệt load bản mới nhất mỗi khi bạn nhấn Save
    wp_enqueue_style('hoangphi-main-style', get_stylesheet_uri(), array(), time());

    // 4. SCRIPTS
    // Swiper JS
    wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array(), '11.0.0', true);
    
    // Custom JS của bạn (Assets/js/main.js)
    // Đảm bảo file này tồn tại để không bị lỗi console
    wp_enqueue_script('hoangphi-main-js', get_template_directory_uri() . '/assets/js/main.js', array('swiper-js'), time(), true);
}
add_action('wp_enqueue_scripts', 'hoangphi_assets');

/**
 * Thiết lập các tính năng hỗ trợ Theme
 */
function hoangphi_theme_setup() {
    // Hỗ trợ WooCommerce
    add_theme_support('woocommerce');
    
    // Hỗ trợ thẻ Title (Tự động tạo tiêu đề trang)
    add_theme_support('title-tag');
    
    // Hỗ trợ ảnh đại diện (Featured Image)
    add_theme_support('post-thumbnails');

    // Đăng ký menu để quản lý trong Admin
    register_nav_menus(array(
        'primary' => 'Main Menu',
        'footer'  => 'Footer Menu',
    ));
}
add_action('after_setup_theme', 'hoangphi_theme_setup');

/**
 * Remove WooCommerce default tabs và related products
 * Chúng ta sẽ tự custom layout trong single-product.php
 */
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15);
remove_action('woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);

/**
 * Transform variation dropdown to buttons
 */
function hoangphi_variation_dropdown_to_buttons($html, $args) {
    // Only transform on single product page
    if (!is_product()) {
        return $html;
    }
    
    // Extract options from select
    preg_match_all('/<option[^>]*value="([^"]*)"[^>]*>([^<]*)<\/option>/', $html, $matches, PREG_SET_ORDER);
    
    if (empty($matches)) {
        return $html;
    }
    
    $attribute_name = $args['attribute'];
    $buttons_html = '<div class="variation-buttons" data-attribute="' . esc_attr($attribute_name) . '">';
    
    foreach ($matches as $match) {
        $value = $match[1];
        $label = trim(strip_tags($match[2]));
        $selected = strpos($match[0], 'selected') !== false ? 'selected' : '';
        
        if ($value === '') {
            continue; // Skip "Choose an option"
        }
        
        $buttons_html .= sprintf(
            '<button type="button" class="variation-button %s" data-value="%s" data-attribute="%s">%s</button>',
            esc_attr($selected),
            esc_attr($value),
            esc_attr($attribute_name),
            esc_html($label)
        );
    }
    
    $buttons_html .= '</div>';
    
    // Keep the original select hidden for WooCommerce functionality
    return $html . $buttons_html;
}
add_filter('woocommerce_dropdown_variation_attribute_options_html', 'hoangphi_variation_dropdown_to_buttons', 10, 2);

/**
 * Debug nhanh cho Dev: Kiểm tra xem Tailwind có đang quét đúng file không
 * Nếu bạn muốn thêm bất kỳ folder nào vào Tailwind config, hãy cập nhật tailwind.config.js
 */

 /**
 * Ép WordPress load script WooCommerce AJAX ở mọi trang
 */
function hoangphi_force_load_wc_scripts() {
    if (function_exists('is_woocommerce')) {
        wp_enqueue_script('wc-add-to-cart-variation');
        wp_enqueue_script('wc-cart-fragments');
    }
}
add_action('wp_enqueue_scripts', 'hoangphi_force_load_wc_scripts');

/**
 * Cập nhật toàn bộ Mini Cart và Số lượng qua AJAX
 */
add_filter( 'woocommerce_add_to_cart_fragments', 'hoangphi_refresh_mini_cart_completely' );
function hoangphi_refresh_mini_cart_completely( $fragments ) {
    // 1. Cập nhật icon số lượng (như đã làm)
    ob_start();
    $count = WC()->cart->get_cart_contents_count();
    ?>
    <div class="header-cart-wrapper">
        <a class="cart-contents relative group" href="<?php echo wc_get_cart_url(); ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
            </svg>
            <span id="cart-count-global" class="cart-contents-count absolute -top-2 -right-2 bg-black text-white text-[10px] w-4 h-4 rounded-full flex items-center justify-center">
                <?php echo $count; ?>
            </span>
        </a>
    </div>
    <?php
    $fragments['div.header-cart-wrapper'] = ob_get_clean();

    // 2. Cập nhật NỘI DUNG bên trong Mini Cart (Danh sách sản phẩm, Số lượng x Giá)
    ob_start();
    ?>
    <div class="widget_shopping_cart_content">
        <?php woocommerce_mini_cart(); ?>
    </div>
    <?php
    $fragments['div.widget_shopping_cart_content'] = ob_get_clean();
    
    // 3. Cập nhật cart total trong side cart
    ob_start();
    ?>
    <span id="cart-total"><?php echo WC()->cart->get_cart_total(); ?></span>
    <?php
    $fragments['#cart-total'] = ob_get_clean();

    return $fragments;
}

// 1. Định nghĩa nội dung bạn muốn chèn (Cái "áo")
function hoangphi_display_video_reel() {
    $video_url = get_field('video_reel'); // Lấy từ ACF
    if ( $video_url ) {
        ?>
        <div class="my-8 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <h4 class="text-[10px] uppercase tracking-widest mb-4 font-bold text-gray-400 text-center">Product in action</h4>
            <div class="aspect-[9/16] max-w-[250px] mx-auto rounded-lg overflow-hidden shadow-2xl">
                <video src="<?php echo $video_url; ?>" autoplay muted loop playsinline class="w-full h-full object-cover" style="aspect-ratio: 9/16; width: 100%; height: 100%;"></video>
            </div>
        </div>
        <?php
    }
}

// 2. "Treo" nó vào một cái móc của WooCommerce
// 'woocommerce_single_product_summary' là cái móc ở cột bên phải thông tin sản phẩm
// Số 35 là thứ tự (ưu tiên hiện sau các thành phần khác)
add_action( 'woocommerce_single_product_summary', 'hoangphi_display_video_reel', 35 );


/**
 * CHÈN VIDEO REEL VÀO DƯỚI NÚT MUA HÀNG (DÙNG HOOK MIỄN PHÍ)
 */
add_action( 'woocommerce_share', 'hoangphi_add_luxury_video_reel', 10 );

function hoangphi_add_luxury_video_reel() {
    // Lấy link video từ ACF
    $video_url = get_field('video_reel'); 
    
    if ( $video_url ) : ?>
        <div class="mt-10 p-6 bg-gray-50 rounded-3xl border border-gray-100">
            <p class="text-[10px] uppercase tracking-[0.2em] font-bold text-gray-400 mb-4 text-center">See it in action</p>
            <div class="max-w-[280px] mx-auto aspect-[9/16] rounded-2xl overflow-hidden shadow-2xl shadow-black/10">
                <video src="<?php echo $video_url; ?>" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
            </div>
        </div>
    <?php endif;
}

/**
 * Component: Hero Banner Section - Responsive với <picture> tag
 * Sử dụng thẻ <picture> cho ảnh (tối ưu performance) và video riêng
 */
function hoangphi_render_hero_banner() {
    $settings_id = 80; // ID trang cài đặt
    $type = get_field('hero_type', $settings_id);
    $headline = get_field('hero_headline', $settings_id);
    $subheadline = get_field('hero_subheadline', $settings_id);
    $btn_text = get_field('hero_button_text', $settings_id);
    $btn_link = get_field('hero_button_link', $settings_id);
    
    // Nếu không có dữ liệu, không hiển thị
    if ( ! $headline && ! $subheadline ) return;
    
    // Lấy URLs cho image
    $img_desktop = get_field('hero_image_desktop', $settings_id);
    $img_mobile = get_field('hero_image_mobile', $settings_id);
    ?>
    <section class="hero-banner-section relative min-h-[80vh] h-[80vh] w-full overflow-hidden bg-[#f9f9f9]">
        
        <?php if ($type == 'video') : ?>
            <!-- Video: Aspect Ratio Box -->
            <div class="absolute inset-0">
                <div class="aspect-[16/9] md:aspect-[16/9] w-full h-full">
                    <video autoplay muted loop playsinline class="hidden md:block w-full h-full object-cover hero-video-desktop">
                        <source src="<?php echo esc_url( get_field('hero_video_desktop', $settings_id) ); ?>" type="video/mp4">
                    </video>
                    <div class="aspect-[9/16] md:hidden w-full h-full">
                        <video autoplay muted loop playsinline class="block md:hidden w-full h-full object-cover hero-video-mobile">
                            <source src="<?php echo esc_url( get_field('hero_video_mobile', $settings_id) ); ?>" type="video/mp4">
                        </video>
                    </div>
                </div>
                <div class="absolute inset-0 bg-black/10"></div>
            </div>
        <?php else : ?>
            <!-- Image: Aspect Ratio Box -->
            <div class="absolute inset-0 aspect-[16/9] w-full h-full">
                <picture class="w-full h-full">
                    <source srcset="<?php echo esc_url( $img_mobile ); ?>" media="(max-width: 767px)">
                    <img src="<?php echo esc_url( $img_desktop ); ?>" 
                         alt="Hero Banner" 
                         class="w-full h-full object-cover object-center"
                         loading="eager" 
                         fetchpriority="high"
                         width="1920"
                         height="1080">
                </picture>
            </div>
        <?php endif; ?>

        <div class="relative z-10 h-full flex flex-col items-center justify-center text-center px-6 bg-black/5">
            <?php if ( $subheadline ) : ?>
                <span class="text-[11px] font-bold uppercase tracking-[0.4em] text-white mb-4 drop-shadow-sm">
                    <?php echo esc_html( $subheadline ); ?>
                </span>
            <?php endif; ?>
            
            <?php if ( $headline ) : ?>
                <h1 class="text-4xl md:text-7xl font-light text-white uppercase tracking-[0.2em] mb-10 leading-tight drop-shadow-md">
                    <?php echo nl2br( esc_html( $headline ) ); ?>
                </h1>
            <?php endif; ?>
            
            <?php if ( $btn_text && $btn_link ) : ?>
                <a href="<?php echo esc_url( $btn_link ); ?>" 
                   class="bg-white text-black px-12 py-4 text-[11px] font-bold uppercase tracking-[0.3em] hover:bg-black hover:text-white transition-all duration-500">
                    <?php echo esc_html( $btn_text ); ?>
                </a>
            <?php endif; ?>
        </div>
    </section>
    <?php
}

// Hook Hero Banner vào đầu trang chủ (priority 10)
add_action( 'hoangphi_homepage_content', 'hoangphi_render_hero_banner', 10 );

/**
 * Component: Section Product Reels
 * Hiển thị các video sản phẩm dạng Reel/Story với Swiper
 */
function hoangphi_render_reels_section() {
    get_template_part( 'template-parts/section', 'reels' );
}
// Hook Reels Section sau Hero Banner (priority 15)
add_action( 'hoangphi_homepage_content', 'hoangphi_render_reels_section', 15 );

/**
 * Component: Section New Arrivals
 */
function hoangphi_render_new_arrivals_section() {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => 4,
        'orderby'        => 'date',
        'order'          => 'DESC',
    );
    $query = new WP_Query($args);

    if ( ! $query->have_posts() ) return;
    ?>
    <section class="py-20 border-t border-gray-100">
        <div class="max-w-[1400px] mx-auto px-6">
            <h2 class="text-[13px] font-bold uppercase tracking-[0.3em] mb-12 text-center">New Arrivals</h2>
            
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
                <?php 
                while ( $query->have_posts() ) : $query->the_post(); 
                    wc_setup_product_data( get_post() );
                    get_template_part('template-parts/content', 'product');
                endwhile; 
                wp_reset_postdata(); 
                ?>
            </div>
        </div>
    </section>
    <?php
}

// TREO COMPONENT VÀO TRANG CHỦ
add_action( 'hoangphi_homepage_content', 'hoangphi_render_new_arrivals_section', 20 );

/**
 * Component: Section Best Sellers
 */
function hoangphi_render_best_sellers() {
    $query = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => 4,
        'meta_key' => 'total_sales',
        'orderby' => 'meta_value_num',
        'order' => 'DESC',
    ]);

    if ( ! $query->have_posts() ) return;
    ?>
    <section class="py-20 border-t border-gray-100">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="flex items-center justify-between mb-10 uppercase tracking-[0.3em] text-[12px] font-bold">
                <h2>Best Sellers</h2>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="border-b border-black hover:opacity-70 transition-opacity">View All</a>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
                <?php 
                while ( $query->have_posts() ) : $query->the_post(); 
                    wc_setup_product_data( get_post() );
                    get_template_part('template-parts/content', 'product');
                endwhile; 
                wp_reset_postdata(); 
                ?>
            </div>
        </div>
    </section>
    <?php
}
add_action('hoangphi_homepage_content', 'hoangphi_render_best_sellers', 30);

/**
 * Output Related Products Section với Swiper
 */
function hoangphi_output_related_products() {
    get_template_part( 'template-parts/single-related' );
}
// Chèn vào cuối trang sản phẩm
add_action( 'woocommerce_after_single_product', 'hoangphi_output_related_products', 20 );

/**
 * Register ACF Options Page cho Mega Menu (Optional)
 * Nếu bạn muốn quản lý Featured Image trong Mega Menu từ Admin:
 * 1. Tạo ACF Field Group với location "Options Page"
 * 2. Thêm các fields: mega_menu_featured_image, mega_menu_featured_title, mega_menu_featured_subtitle, mega_menu_featured_link
 * 3. Uncomment đoạn code dưới đây
 */
if ( function_exists( 'acf_add_options_page' ) ) {
    acf_add_options_page( array(
        'page_title' => 'Theme Options',
        'menu_title' => 'Theme Options',
        'menu_slug'  => 'theme-options',
        'capability' => 'edit_posts',
        'redirect'   => false,
    ) );
}
// Register ACF Options Page
// if( function_exists('acf_add_options_page') ) {
//     acf_add_options_page(array(
//         'page_title'    => 'Theme Options',
//         'menu_title'    => 'Theme Options',
//         'menu_slug'     => 'theme-options',
//         'capability'    => 'edit_posts',
//         'redirect'      => false
//     ));
// }
/**
 * Đăng ký ACF Options Page
 */
add_action('acf/init', 'hoangphi_register_acf_options_pages');
function hoangphi_register_acf_options_pages() {
    if( function_exists('acf_add_options_page') ) {
        
        acf_add_options_page(array(
            'page_title'    => __('Theme General Settings', 'hoangphi'),
            'menu_title'    => __('Theme Options', 'hoangphi'),
            'menu_slug'     => 'theme-general-settings',
            'capability'    => 'edit_posts',
            'redirect'      => false,
            'icon_url'      => 'dashicons-admin-generic', // Icon bánh răng
            'position'      => 60 // Vị trí dưới mục Appearance
        ));
        
    }
}
// Tạo lối tắt cho trang cấu hình ở menu trái
add_action('admin_menu', function (){
    add_menu_page('Mega Menu Settings', 'Mega Menu Settings', 'manage_options', 'post.php?post=75&action=edit', '', 'dashicons-admin-generic', 60);
});

// Tạo lối tắt cho trang cấu hình ở menu trái
add_action('admin_menu', function (){
    add_menu_page('Global Settings', 'Global Settings', 'manage_options', 'post.php?post=80&action=edit', '', 'dashicons-admin-generic', 60);
});

/**
 * Ép WordPress ưu tiên tìm Sản phẩm khi search
 * Đảm bảo khi khách search từ Overlay, WordPress sẽ tập trung tìm trong WooCommerce
 */
function hoangphi_search_filter( $query ) {
    if ( $query->is_search() && ! is_admin() && $query->is_main_query() ) {
        // Chỉ tìm trong 'product'
        $query->set( 'post_type', array( 'product' ) );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'hoangphi_search_filter' );

/**
 * Chặn tính năng tự động redirect đến sản phẩm duy nhất khi tìm kiếm
 * Luôn hiển thị trang danh sách kết quả (search.php) ngay cả khi chỉ có 1 sản phẩm
 */
add_filter( 'woocommerce_redirect_single_search_result', '__return_false' );

/**
 * Ép WordPress sử dụng file search.php thay vì template của WooCommerce
 */
add_filter( 'template_include', function( $template ) {
    if ( is_search() ) {
        $search_template = locate_template( 'search.php' );
        if ( $search_template ) {
            return $search_template;
        }
    }
    return $template;
}, 99 );

/**
 * Tối giản hóa các ô nhập liệu trong Checkout
 * Ẩn các trường không cần thiết cho khách hàng Việt Nam
 * Thêm placeholder thay thế labels
 */
add_filter( 'woocommerce_checkout_fields', 'hoangphi_custom_checkout_fields' );

function hoangphi_custom_checkout_fields( $fields ) {
    // Ẩn các trường thừa
    unset( $fields['billing']['billing_company'] );
    unset( $fields['billing']['billing_postcode'] );
    unset( $fields['billing']['billing_address_2'] );
    unset( $fields['billing']['billing_country'] ); // Mặc định là VN rồi

    // Ẩn labels - dùng placeholder thay thế
    $fields['billing']['billing_first_name']['label'] = '';
    $fields['billing']['billing_phone']['label'] = '';
    $fields['billing']['billing_email']['label'] = '';
    $fields['billing']['billing_address_1']['label'] = '';

    // Đổi placeholder cho thân thiện và sang hơn
    $fields['billing']['billing_first_name']['placeholder'] = 'HỌ VÀ TÊN';
    $fields['billing']['billing_phone']['placeholder'] = 'Số điện thoại để liên hệ giao hàng';
    $fields['billing']['billing_email']['placeholder'] = 'ĐỊA CHỈ EMAIL';
    $fields['billing']['billing_address_1']['placeholder'] = 'Số nhà, tên đường...';

    // Sắp xếp lại thứ tự: Tên -> Số điện thoại -> Email -> Địa chỉ
    $fields['billing']['billing_first_name']['priority'] = 10;
    $fields['billing']['billing_phone']['priority'] = 20;
    $fields['billing']['billing_email']['priority'] = 30;
    $fields['billing']['billing_address_1']['priority'] = 40;

    return $fields;
}


/**
 * Tạo Custom Post Type cho Reels
 */
function hoangphi_register_reels_cpt() {
    $labels = array(
        'name'               => 'Reels',
        'singular_name'      => 'Reel',
        'menu_name'          => 'Quản lý Reels',
        'add_new'            => 'Thêm Reel mới',
        'add_new_item'       => 'Thêm Reel mới',
        'edit_item'          => 'Chỉnh sửa Reel',
        'all_items'          => 'Tất cả Reels',
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'menu_icon'          => 'dashicons-video-alt3', // Icon máy quay phim
        'supports'           => array('title'), // Chỉ cần tiêu đề để quản lý
        'rewrite'            => array('slug' => 'reels'),
        'show_in_rest'       => true,
    );

    register_post_type('reels', $args);
}
add_action('init', 'hoangphi_register_reels_cpt');

/**
 * Dịch các text WooCommerce sang tiếng Việt theo phong cách Luxury
 */
// Dịch nút "Add to cart"
add_filter('woocommerce_product_single_add_to_cart_text', function($text) {
    return 'THÊM VÀO GIỎ HÀNG';
});

add_filter('woocommerce_product_add_to_cart_text', function($text) {
    return 'THÊM VÀO GIỎ HÀNG';
});

// Dịch "View Cart"
add_filter('woocommerce_product_add_to_cart_text', function($text) {
    if (is_cart()) {
        return 'XEM GIỎ HÀNG';
    }
    return $text;
}, 20);

/**
 * Tối ưu CSS cho trang Checkout - Luxury Biossance Style
 */
function hoangphi_checkout_custom_styles() {
    if (is_checkout()) {
        ?>
        <style>
            /* Checkout Luxury Styling - Form Row và Label */
            .woocommerce-checkout .form-row {
                margin-bottom: 20px;
            }
            
            .woocommerce-checkout label {
                display: block;
                font-size: 11px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                margin-bottom: 8px;
                color: #1a1a1a;
            }
            
            /* Input Styling - Bỏ style mặc định của trình duyệt cho Select */
            .woocommerce-checkout select,
            .woocommerce-checkout select#billing_state,
            .woocommerce-checkout select#billing_city,
            .woocommerce-checkout select#billing_district,
            .woocommerce-checkout select#shipping_state,
            .woocommerce-checkout select#shipping_city,
            .woocommerce-checkout select#shipping_district {
                appearance: none;
                -webkit-appearance: none;
                -moz-appearance: none;
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 12 12'%3E%3Cpath fill='%23333' d='M6 9L1 4h10z'/%3E%3C/svg%3E");
                background-repeat: no-repeat;
                background-position: right 12px center;
                background-size: 12px;
                border: 1px solid #e5e7eb;
                border-radius: 0;
                font-size: 13px;
                font-weight: 300;
                padding: 12px 36px 12px 12px;
                width: 100%;
                color: #111;
                background-color: #fff;
                transition: border-color 0.2s ease;
            }
            
            .woocommerce-checkout select:focus {
                outline: none;
                border-color: #7d6349;
            }
            
            /* Gom nhóm Tỉnh/Thành và Quận/Huyện trên cùng 1 hàng - Dùng Grid/Flex */
            @media (min-width: 768px) {
                /* Tạo grid container cho các field Tỉnh/Thành và Quận/Huyện */
                .woocommerce-checkout #billing_state_field.form-row-first,
                .woocommerce-checkout #billing_city_field.form-row-first,
                .woocommerce-checkout #billing_district_field.form-row-first,
                .woocommerce-checkout #shipping_state_field.form-row-first,
                .woocommerce-checkout #shipping_city_field.form-row-first,
                .woocommerce-checkout #shipping_district_field.form-row-first {
                    width: 48% !important;
                    display: inline-block !important;
                    float: left !important;
                    margin-right: 4% !important;
                    clear: none !important;
                    vertical-align: top;
                }
                
                .woocommerce-checkout #billing_state_field.form-row-last,
                .woocommerce-checkout #billing_city_field.form-row-last,
                .woocommerce-checkout #billing_district_field.form-row-last,
                .woocommerce-checkout #shipping_state_field.form-row-last,
                .woocommerce-checkout #shipping_city_field.form-row-last,
                .woocommerce-checkout #shipping_district_field.form-row-last {
                    width: 48% !important;
                    display: inline-block !important;
                    float: right !important;
                    margin-right: 0 !important;
                    clear: none !important;
                    vertical-align: top;
                }
                
                /* Đảm bảo các field sau cặp Tỉnh/Thành - Quận/Huyện xuống dòng mới */
                .woocommerce-checkout #billing_state_field.form-row-first + .form-row:not(.form-row-last),
                .woocommerce-checkout #billing_city_field.form-row-first + .form-row:not(.form-row-last),
                .woocommerce-checkout #billing_district_field.form-row-first + .form-row:not(.form-row-last),
                .woocommerce-checkout #shipping_state_field.form-row-first + .form-row:not(.form-row-last),
                .woocommerce-checkout #shipping_city_field.form-row-first + .form-row:not(.form-row-last),
                .woocommerce-checkout #shipping_district_field.form-row-first + .form-row:not(.form-row-last) {
                    clear: both !important;
                }
                
                /* Ép các address field hiển thị đúng */
                .woocommerce-checkout .address-field.validate-required {
                    display: block !important;
                }
            }
            
            /* Custom nút #place_order - The Final Touch */
            .woocommerce-checkout #place_order {
                background-color: #000 !important;
                color: #fff !important;
                padding: 20px !important;
                text-transform: uppercase !important;
                letter-spacing: 0.2em !important;
                font-weight: 700 !important;
                border-radius: 0px !important;
                width: 100% !important;
                transition: all 0.3s ease !important;
                margin-top: 20px;
                border: none !important;
                font-size: 11px;
                cursor: pointer;
            }
            
            .woocommerce-checkout #place_order:hover {
                background-color: #333 !important;
                letter-spacing: 0.3em !important;
            }
            
            .woocommerce-checkout #place_order:active {
                transform: translateY(1px);
            }
            
            /* Tối ưu cột "Đơn hàng của bạn" (Order Review) */
            .woocommerce-checkout #order_review {
                border: none !important;
            }
            
            .woocommerce-checkout #order_review table {
                border: none !important;
                width: 100%;
            }
            
            .woocommerce-checkout #order_review table th,
            .woocommerce-checkout #order_review table td {
                border: none !important;
                padding: 12px 0;
                vertical-align: top;
            }
            
            .woocommerce-checkout #order_review .product-name {
                font-size: 13px;
                text-transform: uppercase;
                font-weight: 500;
            }
            
            .woocommerce-checkout #order_review .order-total {
                border-top: 1px solid #e5e5e5 !important;
                padding-top: 20px;
                margin-top: 20px;
            }
            
            .woocommerce-checkout #order_review .order-total th,
            .woocommerce-checkout #order_review .order-total td {
                font-size: 16px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
            }
            
            /* Tối ưu Input fields chung - Đồng bộ với Select2 */
            .woocommerce-checkout textarea {
                width: 100% !important;
                padding: 12px 16px !important;
                border: 1px solid #e5e5e5 !important;
                border-radius: 0px !important;
                font-size: 14px;
                font-weight: 300;
                transition: border-color 0.3s ease;
                background-color: #fff;
                min-height: 100px;
            }
            
            .woocommerce-checkout textarea:focus {
                border-color: #000 !important;
                outline: none;
            }
            
            /* Focus state cho tất cả input/select/select2 */
            .woocommerce-checkout input[type="text"]:focus,
            .woocommerce-checkout input[type="email"]:focus,
            .woocommerce-checkout input[type="tel"]:focus,
            .woocommerce-checkout select:focus,
            .woocommerce-checkout .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #000 !important;
                outline: none;
            }
            
            /* Ép chiều cao và style cho cả ô input lẫn dropdown Select2 - Đồng bộ hoàn toàn */
            .woocommerce-checkout .select2-container--default .select2-selection--single,
            .woocommerce-checkout .select2-container .select2-selection--single,
            .woocommerce-checkout input[type="text"],
            .woocommerce-checkout input[type="tel"],
            .woocommerce-checkout input[type="email"],
            .woocommerce-checkout select {
                height: 50px !important;
                line-height: 50px !important;
                border: 1px solid #e5e5e5 !important;
                border-radius: 0px !important;
                padding: 0 15px !important;
                background-color: #fff !important;
                width: 100% !important;
                display: flex !important;
                align-items: center !important;
                box-sizing: border-box !important;
            }
            
            /* Xử lý phần text bên trong của Select2 bị lệch lên trên */
            .woocommerce-checkout .select2-container--default .select2-selection--single .select2-selection__rendered {
                line-height: 50px !important;
                padding-left: 0 !important;
                color: #1a1a1a !important;
                font-size: 14px !important;
                font-weight: 300 !important;
            }
            
            /* Ẩn cái mũi tên mặc định xấu xí của Select2 để làm mũi tên mới sang hơn */
            .woocommerce-checkout .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 50px !important;
                top: 0 !important;
                right: 10px !important;
            }
            
            .woocommerce-checkout .select2-container--default.select2-container--focus .select2-selection--single {
                border-color: #000 !important;
            }
            
            /* Fix lỗi các trường Tỉnh/Huyện bị nhảy hàng không đều */
            .woocommerce-checkout .form-row {
                display: block !important;
                width: 100% !important;
                float: none !important;
                margin-right: 0 !important;
            }
            
            /* Xử lý "Dấu sao" bắt buộc bị lệch */
            .woocommerce-checkout abbr.required {
                text-decoration: none !important;
                color: #ff0000 !important;
                font-size: 16px;
                margin-left: 4px;
            }
            
            /* Clear floats */
            .woocommerce-checkout .form-row::after {
                content: "";
                display: table;
                clear: both;
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'hoangphi_checkout_custom_styles');

/**
 * CSS cho trang Thank You Page - VietQR và ẩn thông tin ngân hàng mặc định
 */
function hoangphi_thankyou_page_styles() {
    if ( is_wc_endpoint_url( 'order-received' ) || is_order_received_page() ) {
        ?>
        <style>
            /* Đồng bộ Font chữ & Màu sắc cho khối QR */
            .vietqr-payment-wrapper {
                font-family: 'Inter', sans-serif; /* Sử dụng font Inter của theme */
            }
            
            .vietqr-payment-wrapper h3.vietqr-title {
                font-family: 'Inter', sans-serif;
                font-weight: 300;
                color: #1a1a1a;
                border-bottom: 1px solid #eee;
                padding-bottom: 15px;
                display: inline-block;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 2px;
                margin-bottom: 20px;
            }
            
            .vietqr-payment-wrapper .qr-container {
                transition: transform 0.3s ease;
            }
            
            .vietqr-payment-wrapper .qr-container:hover {
                transform: translateY(-5px); /* Hiệu ứng nổi nhẹ khi di chuột vào */
            }
            
            /* Nút Zalo hover effect */
            .zalo-confirm-btn:hover {
                background-color: #0052CC !important;
                transform: translateY(-2px);
                box-shadow: 0 4px 12px rgba(0, 104, 255, 0.3);
            }
            
            /* Ẩn phần danh sách chi tiết ngân hàng mặc định của WooCommerce */
            .woocommerce-order ul.wc-bacs-bank-details,
            .woocommerce-order .woocommerce-bacs-bank-details,
            .woocommerce-thankyou-order-details ul.wc-bacs-bank-details {
                display: none !important;
            }
        </style>
        <?php
    }
}
add_action('wp_head', 'hoangphi_thankyou_page_styles');

/**
 * Ép WooCommerce sử dụng template checkout từ theme
 */
add_filter( 'woocommerce_locate_template', 'hoangphi_force_checkout_template', 10, 3 );
function hoangphi_force_checkout_template( $template, $template_name, $template_path ) {
    if ( 'checkout/form-checkout.php' === $template_name ) {
        $theme_file = get_stylesheet_directory() . '/woocommerce/checkout/form-checkout.php';
        if ( file_exists( $theme_file ) ) {
            return $theme_file;
        }
    }
    return $template;
}

/**
 * Việt hóa text đăng ký nhận bản tin và Personal Data - Luxury Style
 */
add_filter( 'gettext', 'hoangphi_luxury_translate_text', 20, 3 );
function hoangphi_luxury_translate_text( $translated_text, $text, $domain ) {
    // Các biến thể text đăng ký newsletter phổ biến
    $newsletter_texts = array(
        'I would like to receive exclusive emails with discounts and product information',
        'Subscribe to our newsletter',
        'Sign up for our newsletter',
        'I want to receive marketing emails',
        'Email me with news and special offers'
    );
    
    if ( in_array( $text, $newsletter_texts ) ) {
        $translated_text = 'Đăng ký nhận ưu đãi đặc quyền và thông tin sản phẩm mới nhất.';
    }
    
    // Việt hóa text Personal Data
    if ( strpos( $text, 'Your personal data will be used' ) !== false ) {
        $translated_text = 'Thông tin cá nhân của bạn sẽ được sử dụng để bảo mật và tối ưu trải nghiệm mua sắm tại Hoang Phi theo chính sách riêng tư của chúng tôi.';
    }
    
    return $translated_text;
}

/**
 * Triển khai VietQR tự động cho trang Thank You Page
 * Tự động lấy thông tin từ WooCommerce Settings (BACS Account Details)
 * Sử dụng hook woocommerce_thankyou_bacs - chỉ hiển thị khi payment method là BACS
 */
add_action( 'woocommerce_thankyou_bacs', 'hoangphi_display_vietqr_from_settings', 20 );
function hoangphi_display_vietqr_from_settings( $order_id ) {
    $order = wc_get_order( $order_id );
    if ( ! $order ) {
        return;
    }
    
    // 1. Lấy danh sách tài khoản ngân hàng bạn đã cài trong Admin
    $bacs_info = get_option( 'woocommerce_bacs_accounts' );
    
    if ( ! empty( $bacs_info ) ) {
        // Lấy tài khoản đầu tiên trong danh sách
        $account = $bacs_info[0];
        
        $bank_id      = $account['bank_name']; // Sẽ lấy "TP BANK"
        $account_no   = $account['account_number']; // Sẽ lấy "62626926789"
        $account_name = $account['account_name']; // Sẽ lấy "NGUYEN MINH HIEU"
        
        // 2. Format lại Bank ID cho đúng chuẩn VietQR (Xóa khoảng trắng)
        // Ví dụ: "TP BANK" -> "TPB" hoặc "TPBank"
        $bank_id_formatted = str_replace(' ', '', $bank_id);
        if ( $bank_id_formatted == 'TPBANK' ) {
            $bank_id_formatted = 'TPB';
        }
        // Thêm các mapping khác nếu cần
        $bank_mapping = array(
            'TPBANK' => 'TPB',
            'VIETCOMBANK' => 'VCB',
            'VPBANK' => 'VPB',
            'TECHCOMBANK' => 'TCB',
            'ACBANK' => 'ACB',
            'VIETINBANK' => 'VTB',
            'BIDV' => 'BID',
        );
        
        $bank_id_upper = strtoupper( $bank_id_formatted );
        if ( isset( $bank_mapping[ $bank_id_upper ] ) ) {
            $bank_id_formatted = $bank_mapping[ $bank_id_upper ];
        }
        
        $amount = $order->get_total();
        $memo   = 'HP' . $order_id;
        
        // 3. Tạo URL VietQR
        $qr_url = sprintf(
            'https://img.vietqr.io/image/%s-%s-compact2.png?amount=%s&addInfo=%s&accountName=%s',
            $bank_id_formatted,
            $account_no,
            intval( $amount ),
            urlencode( $memo ),
            urlencode( $account_name )
        );
        
        // 4. Hiển thị ra giao diện Luxury
        // Lấy số điện thoại Zalo từ settings (hoặc hard-code nếu cần)
        $zalo_phone = '0123456789'; // Thay bằng số điện thoại Zalo của bạn (bỏ số 0 đầu, ví dụ: 123456789)
        $zalo_message = urlencode( 'Xin chào, tôi đã thanh toán đơn hàng #' . $order_id . '. Vui lòng xác nhận.' );
        $zalo_url = 'https://zalo.me/' . $zalo_phone . '?message=' . $zalo_message;
        ?>
        <div class="vietqr-payment-wrapper" style="margin: 40px 0; text-align: center;">
            <div style="background: #fff; border: 1px solid #f2f2f2; padding: 30px; display: inline-block; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.05);">
                <h3 class="vietqr-title">Quét mã để thanh toán</h3>
                <div class="qr-container">
                    <img src="<?php echo esc_url( $qr_url ); ?>" style="max-width: 280px; height: auto; margin-bottom: 15px; border-radius: 8px;" alt="Mã QR thanh toán" />
                </div>
                <p style="font-size: 15px; font-weight: 600; margin: 5px 0;">Nội dung: <span style="color: #000;"><?php echo esc_html( $memo ); ?></span></p>
                <p style="font-size: 12px; color: #999; margin-top: 10px;">Chủ TK: <?php echo esc_html( $account_name ); ?></p>
                
                <!-- Nút Xác nhận qua Zalo -->
                <a href="<?php echo esc_url( $zalo_url ); ?>" 
                   target="_blank" 
                   class="zalo-confirm-btn"
                   style="display: inline-block; margin-top: 20px; padding: 12px 24px; background-color: #0068FF; color: #fff; text-decoration: none; border-radius: 8px; font-size: 13px; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; transition: all 0.3s ease;">
                    <span style="margin-right: 8px;">💬</span>
                    Xác nhận qua Zalo
                </a>
            </div>
        </div>
        <?php
    }
}
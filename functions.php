<?php
/**
 * Hoang Phi Theme Functions
 * Thiết lập cho Frontend Developer - Tailwind + Swiper + WooCommerce
 */

function hoangphi_assets() {
    // 1. Google Fonts - Load trước để tránh nhảy font (FOUT)
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
 * Đảm bảo cart count và cart total được cập nhật trong fragments
 */
function hoangphi_add_cart_count_to_fragments($fragments) {
    // Cập nhật cart count
    $fragments['.cart-contents-count'] = '<span class="cart-contents-count cart-count absolute -top-2 -right-2 bg-black text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center">' . WC()->cart->get_cart_contents_count() . '</span>';
    
    // Cập nhật cart total trong side cart
    $fragments['#cart-total'] = WC()->cart->get_cart_total();
    
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'hoangphi_add_cart_count_to_fragments');

// 1. Định nghĩa nội dung bạn muốn chèn (Cái "áo")
function hoangphi_display_video_reel() {
    $video_url = get_field('video_reel'); // Lấy từ ACF
    if ( $video_url ) {
        ?>
        <div class="my-8 p-4 bg-gray-50 rounded-2xl border border-dashed border-gray-200">
            <h4 class="text-[10px] uppercase tracking-widest mb-4 font-bold text-gray-400 text-center">Product in action</h4>
            <div class="aspect-[9/16] max-w-[250px] mx-auto rounded-lg overflow-hidden shadow-2xl">
                <video src="<?php echo $video_url; ?>" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
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
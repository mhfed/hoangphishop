<?php
/**
 * The Template for displaying all single products
 * 
 * @package HoangPhi_Theme
 */

defined( 'ABSPATH' ) || exit;

get_header();

/**
 * Hook: woocommerce_before_single_product.
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form();
    return;
}
?>

<?php while ( have_posts() ) : the_post(); 
    global $product;
    
    // Ensure product object is set up
    if ( ! $product ) {
        $product = wc_get_product( get_the_ID() );
    }
    
    if ( ! $product ) {
        return;
    }
?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
    
    <?php
        // Get product images
        $image_ids = array();
        if ( $product->get_image_id() ) {
            $image_ids[] = $product->get_image_id();
        }
        $gallery_ids = $product->get_gallery_image_ids();
        $image_ids = array_merge( $image_ids, $gallery_ids );
        
        // Get variation data for image switching
        $variations = $product->is_type( 'variable' ) ? $product->get_available_variations() : array();
    ?>
    
    <main class="bg-white min-h-screen pt-10 pb-20">
        <div class="max-w-[1400px] mx-auto px-6">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-20">
                
                <!-- Left Column: Sticky Gallery -->
                <div class="sticky top-24 h-fit">
                    <div id="product-gallery" class="space-y-6">
                        <!-- Main Image Display -->
                        <div class="aspect-[4/5] rounded-sm overflow-hidden bg-gray-50 mb-4">
                            <?php 
                            if ( !empty( $image_ids ) ) {
                                $main_image_id = $image_ids[0];
                                echo wp_get_attachment_image( $main_image_id, 'full', false, [
                                    'class' => 'w-full h-full object-cover',
                                    'id' => 'main-product-image'
                                ] );
                            } else {
                                echo wc_placeholder_img( 'full' );
                            }
                            ?>
                        </div>
                        
                        <!-- Thumbnail Gallery -->
                        <?php if ( count( $image_ids ) > 1 ) : ?>
                            <div class="grid grid-cols-4 gap-3">
                                <?php foreach ( $image_ids as $index => $img_id ) : 
                                    $img_url = wp_get_attachment_image_url( $img_id, 'woocommerce_thumbnail' );
                                    $full_url = wp_get_attachment_image_url( $img_id, 'full' );
                                ?>
                                    <button 
                                        class="product-thumbnail aspect-square rounded-sm overflow-hidden border-2 border-transparent hover:border-black transition-colors <?php echo $index === 0 ? 'border-black' : ''; ?>"
                                        data-image-id="<?php echo $img_id; ?>"
                                        data-full-url="<?php echo esc_url( $full_url ); ?>"
                                        aria-label="<?php echo esc_attr( get_the_title( $img_id ) ); ?>">
                                        
                                        <?php echo wp_get_attachment_image( $img_id, 'woocommerce_thumbnail', false, [
                                            'class' => 'w-full h-full object-cover'
                                        ] ); ?>
                                    </button>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                        
                        <!-- Video Reel (if available) -->
                        <?php 
                        $video_url = get_field('video_reel'); 
                        if ( $video_url ) : 
                        ?>
                            <div class="relative aspect-[9/16] max-w-[300px] mx-auto rounded-xl overflow-hidden shadow-lg group mt-6">
                                <video src="<?php echo esc_url( $video_url ); ?>" autoplay muted loop playsinline class="w-full h-full object-cover"></video>
                                <div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-[10px] uppercase tracking-widest font-bold">Product Reel</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                
                <!-- Right Column: Product Info -->
                <div class="flex flex-col space-y-8">
                    <!-- Breadcrumb -->
                    <nav class="text-[10px] uppercase tracking-widest text-gray-400 mb-4">
                        <?php woocommerce_breadcrumb(); ?>
                    </nav>
                    
                    <!-- Title -->
                    <h1 class="text-3xl md:text-4xl font-light text-gray-900 leading-tight mb-2">
                        <?php the_title(); ?>
                    </h1>
                    
                    <!-- Price -->
                    <p class="text-xl font-medium text-green-900">
                        <?php echo $product->get_price_html(); ?>
                    </p>
                    
                    <!-- Short Description -->
                    <div class="text-gray-600 text-sm leading-relaxed font-light">
                        <?php the_excerpt(); ?>
                    </div>
                    
                    <!-- Form thêm vào giỏ hàng -->
                    <div class="product-action-area pt-6 border-t border-gray-100">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>
                    
                    <!-- Ingredients Accordion -->
                    <div class="space-y-4 pt-10">
                        <!-- Ingredients -->
                        <?php 
                        $ingredients = get_field('ingredients');
                        if ( $ingredients ) : 
                        ?>
                            <details class="group border-b border-gray-100 pb-4 cursor-pointer" open>
                                <summary class="flex justify-between items-center list-none text-[11px] uppercase tracking-[0.2em] font-bold cursor-pointer">
                                    THÀNH PHẦN
                                    <span class="group-open:rotate-180 transition-transform duration-300">↓</span>
                                </summary>
                                <div class="pt-4 text-sm text-gray-500 font-light leading-relaxed">
                                    <?php echo wp_kses_post( $ingredients ); ?>
                                </div>
                            </details>
                        <?php endif; ?>
                        
                        <!-- Usage Instructions -->
                        <?php 
                        $usage = get_field('usage_instructions');
                        if ( $usage ) : 
                        ?>
                            <details class="group border-b border-gray-100 pb-4 cursor-pointer">
                                <summary class="flex justify-between items-center list-none text-[11px] uppercase tracking-[0.2em] font-bold cursor-pointer">
                                    HƯỚNG DẪN SỬ DỤNG
                                    <span class="group-open:rotate-180 transition-transform duration-300">↓</span>
                                </summary>
                                <div class="pt-4 text-sm text-gray-500 font-light leading-relaxed">
                                    <?php echo wp_kses_post( $usage ); ?>
                                </div>
                            </details>
                        <?php endif; ?>
                        
                        <!-- Clinical Results -->
                        <?php 
                        $clinical = get_field('clinical_results');
                        if ( $clinical ) : 
                        ?>
                            <details class="group border-b border-gray-100 pb-4 cursor-pointer">
                                <summary class="flex justify-between items-center list-none text-[11px] uppercase tracking-[0.2em] font-bold cursor-pointer">
                                    KẾT QUẢ LÂM SÀNG
                                    <span class="group-open:rotate-180 transition-transform duration-300">↓</span>
                                </summary>
                                <div class="pt-4 text-sm text-gray-500 font-light leading-relaxed">
                                    <?php echo wp_kses_post( $clinical ); ?>
                                </div>
                            </details>
                        <?php endif; ?>
                    </div>
                </div>
                
            </div>
        </div>
    </main>
    
    <?php endwhile; ?>
    
</div>

<?php
/**
 * Hook: woocommerce_after_single_product.
 */
do_action( 'woocommerce_after_single_product' );

get_footer();


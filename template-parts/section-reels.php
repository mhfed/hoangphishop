<?php
/**
 * Section: Product Reels
 * Hiển thị các video sản phẩm dạng Reel/Story với Swiper
 * Lấy dữ liệu từ custom post type 'reels'
 * 
 * @package HoangPhi_Theme
 */
?>

<section class="reels-section py-20 bg-white overflow-hidden h-fit">
    <div class="px-6 mb-12 text-center">
        <h2 class="text-2xl md:text-3xl font-light tracking-[0.3em] uppercase text-gray-900">The Reel Experience</h2>
    </div>

    <div class="relative">
        <div class="swiper reelsSwiper px-6 overflow-hidden">
            <div class="swiper-wrapper h-fit">
                <?php
                // Lấy các bài viết từ custom post type 'reels'
                $args = array(
                    'post_type'      => 'reels', // Lấy từ Menu Reels mới
                    'posts_per_page' => 10,
                    'orderby'        => 'menu_order',
                    'order'          => 'ASC'
                );
                $reels_query = new WP_Query($args);

                if ($reels_query->have_posts()) :
                    while ($reels_query->have_posts()) : $reels_query->the_post();
                        $video_url = get_field('reel_video');
                        $product_obj = get_field('reel_product');
                        $label = get_field('reel_label');
                        
                        if ($product_obj && $video_url) :
                            $product_permalink = get_permalink($product_obj->ID);
                            $product_title = get_the_title($product_obj->ID);
                            ?>
                            <div class="swiper-slide">
                                <a href="<?php echo esc_url( $product_permalink ); ?>" 
                                   class="block relative rounded-[24px] overflow-hidden bg-gray-100 group shadow-sm hover:shadow-xl transition-all duration-500">
                                    <!-- Aspect Ratio Box -->
                                    <div class="aspect-[9/16] w-full relative">
                                        <video autoplay muted loop playsinline 
                                               class="reel-video w-full h-full object-cover scale-100 group-hover:scale-110 transition-transform duration-700"
                                               loading="lazy">
                                            <source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
                                        </video>
                                        
                                        <!-- Overlay Gradient -->
                                        <div class="absolute inset-x-0 bottom-0 h-1/3 bg-gradient-to-t from-black/60 to-transparent"></div>
                                        
                                        <!-- Info -->
                                        <div class="absolute inset-x-0 bottom-0 p-5 bg-gradient-to-t from-black/80 pt-10">
                                            <?php if ( $label ) : ?>
                                                <p class="text-[9px] text-white/70 uppercase tracking-widest mb-1">
                                                    <?php echo esc_html( $label ); ?>
                                                </p>
                                            <?php endif; ?>
                                            <h3 class="text-[11px] font-bold text-white tracking-widest uppercase leading-tight">
                                                <?php echo esc_html( $product_title ); ?>
                                            </h3>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endif;
                    endwhile;
                    wp_reset_postdata();
                endif; ?>
            </div>
        </div>
        
        <!-- Navigation Buttons -->
        <div class="flex items-center justify-center gap-4 mt-8">
            <button class="reels-prev text-gray-400 hover:text-black transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button class="reels-next text-gray-400 hover:text-black transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </div>
</section>


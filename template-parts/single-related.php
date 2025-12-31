<?php
if ( ! defined( 'ABSPATH' ) ) exit;

global $product;

if ( ! $product ) return;

// Lấy ID của các sản phẩm liên quan (Giới hạn 8 sản phẩm)
$related_ids = wc_get_related_products( $product->get_id(), 8 );

if ( empty( $related_ids ) ) return;

$args = array(
    'post_type'            => 'product',
    'ignore_sticky_posts'  => 1,
    'no_found_rows'        => 1,
    'posts_per_page'       => 8,
    'post__in'             => $related_ids,
    'orderby'              => 'post__in',
);

$related_products = new WP_Query( $args );

if ( $related_products->have_posts() ) : ?>

<section class="related-products py-20 border-t border-gray-100 bg-[#fff]">
    <div class="max-w-[1400px] mx-auto px-6">
        <div class="flex items-center justify-between mb-10">
            <h2 class="uppercase tracking-[0.3em] text-[13px] font-bold text-gray-900">
                Bạn cũng sẽ thích (Related)
            </h2>
            <div class="swiper-nav-buttons flex gap-4">
                <button class="related-prev text-gray-400 hover:text-black transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
                <button class="related-next text-gray-400 hover:text-black transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5l7 7-7 7" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </button>
            </div>
        </div>

        <div class="swiper related-swiper">
            <div class="swiper-wrapper">
                <?php while ( $related_products->have_posts() ) : $related_products->the_post(); ?>
                    <div class="swiper-slide">
                        <?php 
                        wc_setup_product_data( get_post() );
                        get_template_part( 'template-parts/content', 'product' ); 
                        ?>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</section>

<?php endif;
wp_reset_postdata(); ?>


<?php get_header(); ?>

<section class="search-results py-20 bg-white min-h-screen">
    <div class="max-w-[1400px] mx-auto px-6">
        
        <header class="mb-16 border-b border-gray-100 pb-10">
            <span class="text-[11px] font-bold uppercase tracking-[0.3em] text-gray-400 block mb-2">Kết quả tìm kiếm cho</span>
            <h1 class="text-4xl font-light uppercase tracking-widest text-black">
                "<?php echo get_search_query(); ?>"
            </h1>
        </header>

        <?php if ( have_posts() ) : ?>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-x-6 gap-y-12">
                <?php 
                while ( have_posts() ) : the_post();
                    if ( get_post_type() === 'product' ) {
                        // Thiết lập dữ liệu sản phẩm cho WooCommerce đúng cách
                        wc_setup_product_data( get_post() );
                        ?>
                        <div class="product-item-wrap">
                            <?php get_template_part( 'template-parts/content', 'product' ); ?>
                        </div>
                        <?php
                    }
                endwhile;
                ?>
            </div>

            <div class="mt-20 flex justify-center border-t border-gray-100 pt-10">
                <?php the_posts_pagination(array(
                    'prev_text' => '<span class="uppercase tracking-widest text-[11px]">Â« Trước</span>',
                    'next_text' => '<span class="uppercase tracking-widest text-[11px]">Sau Â»</span>',
                )); ?>
            </div>

        <?php else : ?>
            <div class="text-center py-20">
                <p class="text-gray-500 uppercase tracking-widest text-sm mb-8">Không tìm thấy sản phẩm nào khớp với lựa chọn của bạn.</p>
                <a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>" class="mt-8 inline-block border-b-2 border-black pb-1 font-bold uppercase text-[11px] tracking-widest hover:opacity-70 transition-opacity">
                    Tiếp tục mua sắm
                </a>
            </div>
        <?php endif; ?>

    </div>
</section>

<?php get_footer(); ?>
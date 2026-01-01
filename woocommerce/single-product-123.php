<?php get_header(); ?>

<main class="bg-white min-h-screen pt-10 pb-20">
    <div class="max-w-[1300px] mx-auto px-6">
        
        <?php while ( have_posts() ) : the_post(); global $product; ?>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-20">
                
                <div class="space-y-6">
                    <div class="sticky top-24">
                        <div class="aspect-[4/5] rounded-2xl overflow-hidden bg-gray-50 mb-4">
                            <?php the_post_thumbnail('full', ['class' => 'w-full h-full object-cover']); ?>
                        </div>

                        <?php $video_url = get_field('video_reel'); ?>
                        <?php if($video_url): ?>
                            <div class="relative aspect-[9/16] max-w-[300px] mx-auto rounded-xl overflow-hidden shadow-lg group">
                                <video src="<?php echo $video_url; ?>" autoplay muted loop playsinline class="w-full h-full object-cover" style="aspect-ratio: 9/16; width: 100%; height: 100%;"></video>
                                <div class="absolute inset-0 bg-black/10 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <span class="text-white text-[10px] uppercase tracking-widest font-bold">Product Reel</span>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="flex flex-col space-y-8">
                    <div>
                        <nav class="text-[10px] uppercase tracking-widest text-gray-400 mb-4">
                            <?php woocommerce_breadcrumb(); ?>
                        </nav>
                        <h1 class="text-3xl md:text-4xl font-light text-gray-900 leading-tight mb-2">
                            <?php the_title(); ?>
                        </h1>
                        <p class="text-xl font-medium text-green-900">
                            <?php echo $product->get_price_html(); ?>
                        </p>
                    </div>

                    <div class="text-gray-600 text-sm leading-relaxed font-light">
                        <?php the_excerpt(); ?>
                    </div>

                    <div class="product-action-area pt-6 border-t border-gray-100">
                        <?php woocommerce_template_single_add_to_cart(); ?>
                    </div>

                    <div class="space-y-4 pt-10">
                        <details class="group border-b border-gray-100 pb-4 cursor-pointer" open>
                            <summary class="flex justify-between items-center list-none text-[11px] uppercase tracking-[0.2em] font-bold">
                                Thành phần chính
                                <span class="group-open:rotate-180 transition-transform">↓</span>
                            </summary>
                            <div class="pt-4 text-sm text-gray-500 font-light leading-relaxed">
                                <?php echo get_field('ingredients') ?: 'Squalane cao cấp, Vitamin C, và các dưỡng chất tự nhiên.'; ?>
                            </div>
                        </details>
                        
                        <details class="group border-b border-gray-100 pb-4 cursor-pointer">
                            <summary class="flex justify-between items-center list-none text-[11px] uppercase tracking-[0.2em] font-bold">
                                Cách sử dụng
                                <span class="group-open:rotate-180 transition-transform">↓</span>
                            </summary>
                            <div class="pt-4 text-sm text-gray-500 font-light">
                                Thoa đều lên vùng da mặt và cổ sau khi làm sạch vào mỗi sáng và tối.
                            </div>
                        </details>
                    </div>
                </div>

            </div>

        <?php endwhile; ?>
        
    </div>
</main>

<?php get_header(); ?>
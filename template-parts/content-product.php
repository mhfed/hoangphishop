<?php
/**
 * Template part for displaying product items in listings
 * 
 * Used in: Homepage sections, Shop page, Related products, Search results
 * 
 * @package HoangPhi_Theme
 */

global $product;
if ( empty( $product ) || ! $product->is_visible() ) return;
?>

<div class="group relative flex flex-col bg-white">
    <div class="relative aspect-[3/4] overflow-hidden rounded-sm bg-[#f6f6f6] mb-4">
        <a href="<?php the_permalink(); ?>" class="block h-full w-full">
            <?php 
            // Ảnh chính
            the_post_thumbnail('woocommerce_thumbnail', [
                'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105'
            ]); 
            ?>
        </a>

        <?php if ( $product->is_on_sale() ) : ?>
            <span class="absolute top-3 left-3 bg-white px-2 py-1 text-[9px] font-bold uppercase tracking-widest shadow-sm">Sale</span>
        <?php endif; ?>

        <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out hidden md:block">
            <button 
                data-product_id="<?php echo get_the_ID(); ?>"
                class="ajax_add_to_cart w-full bg-black text-white py-3 text-[10px] font-bold uppercase tracking-[0.2em] hover:bg-gray-800 shadow-xl">
                Quick Add
            </button>
        </div>
    </div>

    <div class="flex flex-col flex-1">
        <h3 class="text-[13px] font-medium tracking-tight text-gray-900 mb-1">
            <a href="<?php the_permalink(); ?>" class="hover:text-green-800 transition-colors">
                <?php the_title(); ?>
            </a>
        </h3>
        
        <p class="text-[11px] text-gray-500 mb-2 font-light line-clamp-1">
            <?php echo get_field('short_benefit') ?: 'Sáng da & Cấp ẩm tức thì'; ?>
        </p>

        <div class="mt-auto pt-2 flex items-center justify-between border-t border-gray-100">
            <span class="text-sm font-semibold text-gray-900">
                <?php echo $product->get_price_html(); ?>
            </span>
            
            <button data-product_id="<?php echo get_the_ID(); ?>" class="ajax_add_to_cart md:hidden text-gray-900">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v16m8-8H4"></path></svg>
            </button>
        </div>
    </div>
</div>


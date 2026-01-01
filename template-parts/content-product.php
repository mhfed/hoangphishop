<?php
/**
 * Template part for displaying product items in listings
 * 
 * Used in: Homepage sections, Shop page, Related products, Search results, Bestsellers Menu
 * Style: Luxury Biossance-inspired
 * 
 * @package HoangPhi_Theme
 */

global $product;
if ( empty( $product ) || ! $product->is_visible() ) return;
?>

<div class="product-card group relative">
    <!-- Aspect Ratio Box -->
    <div class="relative aspect-[3/4] overflow-hidden bg-gray-50 border border-gray-100">
        <div class="w-full h-full">
            <a href="<?php the_permalink(); ?>" class="block w-full h-full">
                <?php echo $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-105' ) ); ?>
            </a>
        </div>
        
        <?php if ( $product->is_on_sale() ) : ?>
            <span class="absolute top-3 left-3 bg-white text-black text-[10px] font-bold py-1 px-3 uppercase tracking-widest shadow-sm">Sale</span>
        <?php endif; ?>

        <div class="absolute inset-x-0 bottom-0 p-4 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
            <a href="?add-to-cart=<?php echo esc_attr( $product->get_id() ); ?>" 
               data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
               class="ajax_add_to_cart w-full bg-black text-white text-[11px] font-bold py-3 flex items-center justify-center uppercase tracking-[0.2em] hover:bg-[#7d6349] transition-colors">
                Thêm vào giỏ +
            </a>
        </div>
    </div>

    <div class="mt-4 text-center">
        <h3 class="text-[12px] font-bold uppercase tracking-widest leading-tight min-h-[32px]">
            <a href="<?php the_permalink(); ?>" class="hover:text-[#7d6349] transition-colors">
                <?php the_title(); ?>
            </a>
        </h3>
        <div class="mt-2 text-[13px] text-gray-600 tracking-wider">
            <?php echo $product->get_price_html(); ?>
        </div>
    </div>
</div>


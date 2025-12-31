<?php
/**
 * Search Overlay Template
 * Full-screen search overlay với animation mượt mà
 */
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div id="search-overlay" class="fixed inset-0 bg-white z-[100] transition-all duration-500 opacity-0 invisible pointer-events-none">
    <button id="close-search" class="absolute top-10 right-10 text-gray-400 hover:text-black transition-colors">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path d="M6 18L18 6M6 6l12 12" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    </button>

    <div class="max-w-4xl mx-auto mt-40 px-6">
        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>" class="relative border-b-2 border-black pb-4">
            <input type="text" 
                   name="s" 
                   id="search-input"
                   placeholder="TÌM KIẾM SẢN PHẨM..." 
                   class="w-full text-4xl font-light uppercase tracking-widest outline-none border-none focus:ring-0 placeholder:text-gray-200"
                   autocomplete="off">
            <input type="hidden" name="post_type" value="product">
            <button type="submit" class="absolute right-0 top-2">
                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" stroke-width="1.5"/>
                </svg>
            </button>
        </form>

        <div id="search-results" class="mt-12 grid grid-cols-4 gap-8">
            <!-- Search results will be populated here via AJAX if needed -->
        </div>
    </div>
</div>


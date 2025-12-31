<header class="sticky top-0 z-50 bg-white/90 backdrop-blur-sm border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
        <div class="text-2xl font-light tracking-[0.3em] text-gray-900">
            HOANG PHI
        </div>

        <nav class="hidden lg:flex space-x-10 text-[11px] font-medium uppercase tracking-[0.2em] text-gray-600">
            <a href="#" class="hover:text-black transition-colors">Trang chủ</a>
            <a href="#" class="hover:text-black transition-colors">Sản phẩm</a>
            <a href="#" class="hover:text-black transition-colors">Quà tặng</a>
            <a href="#" class="hover:text-black transition-colors">Blog</a>
        </nav>

        <div class="flex items-center space-x-6">
            <button class="hover:opacity-60 transition"><svg class="w-5 h-5" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg></button>
            <div class="relative group cursor-pointer">
                <a class="cart-contents" href="<?php echo wc_get_cart_url(); ?>" title="Xem giỏ hàng">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path>
                    </svg>
                    <span
                        class="cart-contents-count cart-count absolute -top-2 -right-2 bg-black text-white text-[9px] w-4 h-4 rounded-full flex items-center justify-center">
                        <?php echo WC()->cart->get_cart_contents_count(); ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
    <?php wp_head(); ?>
</header>

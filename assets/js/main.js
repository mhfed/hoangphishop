// ============================================
// Font Face Observer - Tránh FOUT
// ============================================
(function () {
  // Chỉ hiện trang khi font đã load hoặc sau tối đa 500ms
  const fontLoaded = new Promise((resolve) => {
    if (document.fonts && document.fonts.ready) {
      document.fonts.ready.then(() => {
        resolve();
      });
    } else {
      // Fallback cho trình duyệt cũ
      setTimeout(resolve, 500);
    }
  });

  fontLoaded.then(() => {
    document.documentElement.classList.add('fonts-loaded');
  });

  // Fallback: Hiện trang sau 500ms nếu font chưa load
  setTimeout(() => {
    document.documentElement.classList.add('fonts-loaded');
  }, 500);
})();

// ============================================
// NProgress - Top Loading Bar
// ============================================
if (typeof NProgress !== 'undefined') {
  // Cấu hình NProgress
  NProgress.configure({
    showSpinner: false,
    trickleSpeed: 200,
    minimum: 0.08,
  });

  // Bắt đầu khi trang đang load
  NProgress.start();

  // Kết thúc khi trang load xong
  window.addEventListener('load', function () {
    NProgress.done();

    // Hiệu ứng Reveal cho nội dung
    const mainContent =
      document.getElementById('primary') ||
      document.querySelector('main') ||
      document.body;
    if (mainContent) {
      mainContent.style.opacity = '0';
      mainContent.style.transform = 'translateY(20px)';
      mainContent.style.transition =
        'opacity 1s ease-out, transform 1s ease-out';

      setTimeout(() => {
        mainContent.style.opacity = '1';
        mainContent.style.transform = 'translateY(0)';
      }, 100);
    }
  });

  // Xử lý khi click vào link (chuyển trang)
  document.addEventListener('click', function (e) {
    const link = e.target.closest('a');
    if (
      link &&
      link.href &&
      !link.target &&
      link.hostname === window.location.hostname
    ) {
      NProgress.start();
    }
  });
}

document.addEventListener('DOMContentLoaded', function () {
  // ============================================
  // Skeleton Loading cho Videos
  // ============================================
  // Hero Banner Videos
  const heroVideos = document.querySelectorAll(
    '.hero-video-desktop, .hero-video-mobile'
  );
  heroVideos.forEach((video) => {
    const skeleton = document.querySelector('.hero-video-skeleton');
    if (skeleton) {
      video.addEventListener('loadeddata', () => {
        skeleton.style.opacity = '0';
        setTimeout(() => {
          skeleton.style.display = 'none';
        }, 300);
      });

      // Fallback: Ẩn skeleton sau 5 giây nếu video không load
      setTimeout(() => {
        if (skeleton) {
          skeleton.style.opacity = '0';
          setTimeout(() => {
            skeleton.style.display = 'none';
          }, 300);
        }
      }, 5000);
    }
  });

  // Reels Videos
  const reelVideos = document.querySelectorAll('.reel-video');
  reelVideos.forEach((video) => {
    const skeleton = video.parentElement.querySelector('.reel-video-skeleton');
    if (skeleton) {
      video.addEventListener('loadeddata', () => {
        skeleton.style.opacity = '0';
        setTimeout(() => {
          skeleton.style.display = 'none';
        }, 300);
      });

      // Fallback: Ẩn skeleton sau 5 giây
      setTimeout(() => {
        if (skeleton) {
          skeleton.style.opacity = '0';
          setTimeout(() => {
            skeleton.style.display = 'none';
          }, 300);
        }
      }, 5000);
    }
  });

  // Single Product Video
  const productVideos = document.querySelectorAll('.product-video');
  productVideos.forEach((video) => {
    const skeleton = video.parentElement.querySelector(
      '.product-video-skeleton'
    );
    if (skeleton) {
      video.addEventListener('loadeddata', () => {
        skeleton.style.opacity = '0';
        setTimeout(() => {
          skeleton.style.display = 'none';
        }, 300);
      });

      // Fallback: Ẩn skeleton sau 5 giây
      setTimeout(() => {
        if (skeleton) {
          skeleton.style.opacity = '0';
          setTimeout(() => {
            skeleton.style.display = 'none';
          }, 300);
        }
      }, 5000);
    }
  });

  // 1. Khởi tạo Swiper
  const swiper = new Swiper('.myReelSwiper', {
    slidesPerView: 1.3, // Trên mobile thấy một phần slide sau để kích thích vuốt
    spaceBetween: 16,
    grabCursor: true,
    breakpoints: {
      640: {
        slidesPerView: 2.5,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 4.5, // Đúng chuẩn Biossance
        spaceBetween: 25,
      },
    },
    // Nếu bạn muốn thêm nút bấm
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });

  // 2. Related Products Swiper
  const relatedSwiper = new Swiper('.related-swiper', {
    slidesPerView: 2,
    spaceBetween: 20,
    navigation: {
      nextEl: '.related-next',
      prevEl: '.related-prev',
    },
    breakpoints: {
      // Desktop
      1024: {
        slidesPerView: 4,
        spaceBetween: 30,
      },
      // Tablet
      768: {
        slidesPerView: 3,
        spaceBetween: 20,
      },
    },
  });

  // 3. Product Reels Swiper
  if (document.querySelector('.reelsSwiper')) {
    const reelsSwiper = new Swiper('.reelsSwiper', {
      slidesPerView: 2.2, // Mobile: Một nửa slide tiếp theo lộ ra để kích thích vuốt
      spaceBetween: 16,
      freeMode: true,
      loop: true,
      grabCursor: true, // Hiệu ứng con trỏ kéo trên desktop
      centeredSlides: false, // Không căn giữa slides
      navigation: {
        nextEl: '.reels-next',
        prevEl: '.reels-prev',
      },
      breakpoints: {
        768: {
          slidesPerView: 4.2,
          spaceBetween: 20,
          centeredSlides: false,
        },
        1024: {
          slidesPerView: 6.2,
          spaceBetween: 24,
          centeredSlides: false,
        },
      },
    });
  }

  // 2. Logic điều khiển Video
  const slides = document.querySelectorAll('.swiper-slide');

  slides.forEach((slide) => {
    const video = slide.querySelector('video');
    if (!video) return;

    // Desktop: Hover để chạy, bỏ hover thì dừng và reset
    slide.addEventListener('mouseenter', () => {
      video.play().catch((error) => console.log('Auto-play prevented'));
    });

    slide.addEventListener('mouseleave', () => {
      video.pause();
      video.currentTime = 0;
    });

    // Mobile: Hỗ trợ chạm để play/pause nếu cần
    slide.addEventListener('touchstart', () => {
      if (video.paused) video.play();
      else video.pause();
    });
  });
});

jQuery(document).ready(function ($) {
  $('.ajax_add_to_cart').on('click', function (e) {
    // Chặn tuyệt đối hành vi load trang
    e.preventDefault();

    const $thisbutton = $(this);
    const product_id = $thisbutton.data('product_id');
    const quantity = $thisbutton.data('quantity') || 1;

    // Hiệu ứng loading cho nút
    $thisbutton.addClass('loading opacity-50');

    const data = {
      action: 'woocommerce_add_to_cart',
      product_id: product_id,
      quantity: quantity,
    };

    // Gửi AJAX bằng tay nếu script mặc định của WC bị kẹt
    $.post(wc_add_to_cart_params.ajax_url, data, function (response) {
      if (!response) return;

      if (response.error && response.product_url) {
        window.location = response.product_url;
        return;
      }

      // Áp dụng cart fragments để cập nhật cart count và side cart
      if (response.fragments) {
        $.each(response.fragments, function (key, value) {
          $(key).replaceWith(value);
        });
      }

      // Cập nhật cart total nếu có trong response
      if (response.cart_total) {
        jQuery('#cart-total').html(response.cart_total);
      }

      // Kích hoạt sự kiện để các script khác có thể lắng nghe
      $(document.body).trigger('added_to_cart', [
        response.fragments,
        response.cart_hash,
        $thisbutton,
      ]);

      // Trigger WooCommerce fragments refresh event
      $(document.body).trigger('wc_fragments_refreshed');

      // Mở Side Cart sau khi cập nhật fragments
      setTimeout(function () {
        openSideCart();
      }, 100);

      $thisbutton.removeClass('loading opacity-50').addClass('added');
    });
  });

  function openSideCart() {
    jQuery('#side-cart').removeClass('translate-x-full');
    jQuery('#side-cart-overlay')
      .removeClass('opacity-0 invisible')
      .addClass('opacity-100 visible');
    jQuery('body').addClass('overflow-hidden'); // Chặn scroll web
  }

  function closeSideCart() {
    jQuery('#side-cart').addClass('translate-x-full');
    jQuery('#side-cart-overlay')
      .removeClass('opacity-100 visible')
      .addClass('opacity-0 invisible');
    jQuery('body').removeClass('overflow-hidden');
  }

  jQuery(document).ready(function ($) {
    // Mở khi click icon giỏ hàng ở Header
    $('.cart-contents').on('click', function (e) {
      e.preventDefault();
      openSideCart();
    });

    // Đóng khi click nút X hoặc click ra ngoài overlay
    $('#close-side-cart, #side-cart-overlay').on('click', closeSideCart);

    // TỰ ĐỘNG MỞ KHI THÊM SẢN PHẨM THÀNH CÔNG
    $(document.body).on(
      'added_to_cart',
      function (event, fragments, cart_hash, $button) {
        // Fragments đã được cập nhật trong AJAX handler
        // Chỉ cần mở side cart sau một chút delay để đảm bảo fragments đã render
        setTimeout(function () {
          openSideCart();
        }, 100);
      }
    );
  });

  // 3. Product Gallery Thumbnail Click Handler
  $(document).on('click', '.product-thumbnail', function (e) {
    e.preventDefault();
    const $thumb = $(this);
    const fullUrl = $thumb.data('full-url');
    const imageId = $thumb.data('image-id');

    // Update main image
    const $mainImage = $('#main-product-image');
    if ($mainImage.length && fullUrl) {
      $mainImage.attr('src', fullUrl);
      $mainImage.attr('srcset', '');
    }

    // Update active thumbnail
    $('.product-thumbnail')
      .removeClass('border-black')
      .addClass('border-transparent');
    $thumb.removeClass('border-transparent').addClass('border-black');
  });

  // 4. Variation Buttons Handler
  $(document).on('click', '.variation-button', function (e) {
    e.preventDefault();
    const $button = $(this);
    const value = $button.data('value');
    const attribute = $button.data('attribute');

    // Find corresponding select and update
    const $select = $('select[name="attribute_' + attribute + '"]');
    if ($select.length) {
      $select.val(value).trigger('change');
    }

    // Update button states
    $button.siblings('.variation-button').removeClass('selected');
    $button.addClass('selected');
  });

  // 5. Variation Image Switching
  $(document.body).on('found_variation', function (event, variation) {
    // Check if variation has an image
    if (variation && variation.image && variation.image.src) {
      const $mainImage = $('#main-product-image');
      if ($mainImage.length) {
        // Update main image with variation image
        $mainImage.attr('src', variation.image.src);
        $mainImage.attr('srcset', variation.image.srcset || '');
        $mainImage.attr('sizes', variation.image.sizes || '');

        // Update thumbnail active state if variation image matches a thumbnail
        $('.product-thumbnail').each(function () {
          const $thumb = $(this);
          const thumbImageId = $thumb.data('image-id');
          if (variation.image.id && thumbImageId == variation.image.id) {
            $('.product-thumbnail')
              .removeClass('border-black')
              .addClass('border-transparent');
            $thumb.removeClass('border-transparent').addClass('border-black');
          }
        });
      }
    }
  });

  // 6. Reset variation images when cleared
  $(document.body).on('reset_data', function () {
    // Reset to first image when variation is cleared
    const $firstThumb = $('.product-thumbnail').first();
    if ($firstThumb.length) {
      $firstThumb.trigger('click');
    }
  });

  jQuery(document).ready(function ($) {
    // Xử lý khi click vào các nút biến thể (Chips)
    $(document).on('click', '.variation-chip', function (e) {
      e.preventDefault();
      const $button = $(this);
      const value = $button.data('value');
      const $parent = $button.closest('.variation-row');
      const $realSelect = $parent.find('select');

      // 1. Cập nhật giá trị cho Select ẩn
      $realSelect.val(value).trigger('change');

      // 2. UI: Cập nhật class active
      $button
        .siblings()
        .removeClass('bg-black text-white border-black')
        .addClass('border-gray-200 text-black');
      $button
        .addClass('bg-black text-white border-black')
        .removeClass('border-gray-200');

      // Hiện nút Reset nếu cần
      $('.reset_variations').css('visibility', 'visible');
    });

    // Reset UI khi nhấn nút Clear
    $(document).on('click', '.reset_variations', function () {
      $('.variation-chip')
        .removeClass('bg-black text-white border-black')
        .addClass('border-gray-200 text-black');
      $(this).css('visibility', 'hidden');
    });

    // Lắng nghe sự kiện WooCommerce update biến thể (để handle ảnh/giá)
    $('.variations_form').on('found_variation', function (event, variation) {
      console.log('Biến thể được chọn:', variation);
      // Ở đây WooCommerce tự động xử lý đổi giá và đổi ảnh nhờ các hook mặc định
    });
  });
});

// Search Overlay Handler
document.addEventListener('DOMContentLoaded', function () {
  const searchOverlay = document.getElementById('search-overlay');
  const openSearchBtn = document.getElementById('open-search');
  const closeSearchBtn = document.getElementById('close-search');
  const searchInput = document.getElementById('search-input');

  if (openSearchBtn && searchOverlay) {
    // Mở tìm kiếm
    openSearchBtn.addEventListener('click', function (e) {
      e.preventDefault();
      searchOverlay.classList.remove(
        'opacity-0',
        'invisible',
        'pointer-events-none'
      );
      // Tự động focus chuột vào ô nhập
      setTimeout(() => {
        if (searchInput) {
          searchInput.focus();
        }
      }, 300);
      // Chặn cuộn trang web bên dưới
      document.body.style.overflow = 'hidden';
    });

    // Đóng tìm kiếm
    if (closeSearchBtn) {
      closeSearchBtn.addEventListener('click', function () {
        searchOverlay.classList.add(
          'opacity-0',
          'invisible',
          'pointer-events-none'
        );
        document.body.style.overflow = 'auto';
      });
    }

    // Nhấn phím ESC để đóng
    document.addEventListener('keydown', function (e) {
      if (
        e.key === 'Escape' &&
        !searchOverlay.classList.contains('invisible')
      ) {
        if (closeSearchBtn) {
          closeSearchBtn.click();
        }
      }
    });
  }

  // ============================================
  // Mobile Menu Handler - Slide-out Drawer
  // ============================================
  // Sử dụng event delegation để đảm bảo luôn bắt được click
  document.addEventListener('click', function (e) {
    // Kiểm tra nếu click vào nút mở menu
    const openBtn = e.target.closest('#open-mobile-menu');
    if (openBtn) {
      e.preventDefault();
      e.stopPropagation();
      console.log('Mobile menu open button clicked');

      const menu = document.getElementById('mobile-menu');
      const overlay = document.getElementById('mobile-menu-overlay');
      const content = document.getElementById('mobile-menu-content');

      console.log('Menu elements:', { menu, overlay, content });

      if (menu && content) {
        // Loại bỏ pointer-events-none khỏi #mobile-menu
        menu.classList.remove('pointer-events-none');
        // Xóa translate-x-full khỏi #mobile-menu-content
        content.classList.remove('translate-x-full');
        // Thêm opacity-100 cho overlay
        if (overlay) {
          overlay.classList.add('opacity-100');
        }
        // Ẩn nút mở menu
        openBtn.classList.add('hidden');
        // Chặn cuộn trang web
        document.body.style.overflow = 'hidden';
        console.log('Menu opened successfully');
      } else {
        console.error('Menu elements not found');
      }
    }
  });

  // Hàm đóng menu
  function hideMenu() {
    const menu = document.getElementById('mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');
    const content = document.getElementById('mobile-menu-content');
    const openBtn = document.getElementById('open-mobile-menu');

    if (!menu || !overlay || !content) return;

    // Làm ngược lại: thêm pointer-events-none, translate-x-full, và xóa opacity-100
    overlay.classList.remove('opacity-100');
    content.classList.add('translate-x-full');
    document.body.style.overflow = '';

    // Hiện lại nút mở menu
    if (openBtn) {
      openBtn.classList.remove('hidden');
    }

    setTimeout(() => {
      menu.classList.add('pointer-events-none');
    }, 500);
  }

  // Đóng menu khi click nút đóng hoặc overlay
  document.addEventListener('click', function (e) {
    const closeBtn = document.getElementById('close-mobile-menu');
    const overlay = document.getElementById('mobile-menu-overlay');

    if (e.target === closeBtn || (overlay && e.target === overlay)) {
      hideMenu();
    }
  });

  // Đóng menu khi nhấn ESC
  document.addEventListener('keydown', function (e) {
    if (e.key === 'Escape') {
      const menu = document.getElementById('mobile-menu');
      if (menu && !menu.classList.contains('pointer-events-none')) {
        hideMenu();
      }
    }
  });

  // Logic Accordion cho Sub-menu
  const toggles = document.querySelectorAll('.mobile-menu-toggle');

  // Khởi tạo: Đảm bảo tất cả mega content đều đóng ban đầu
  document.querySelectorAll('.mobile-mega-content').forEach((content) => {
    content.style.maxHeight = '0px';
  });

  toggles.forEach((toggle) => {
    toggle.addEventListener('click', function (e) {
      e.preventDefault();
      e.stopPropagation();

      const parent = this.closest('.mobile-menu-item');
      const megaContent = parent
        ? parent.querySelector('.mobile-mega-content')
        : null;
      const icon = this.querySelector('.toggle-icon');

      if (megaContent) {
        // Đóng tất cả mega menu khác
        document
          .querySelectorAll('.mobile-mega-content')
          .forEach((otherContent) => {
            if (otherContent !== megaContent) {
              otherContent.style.maxHeight = '0px';
              const otherButton = otherContent
                .closest('.mobile-menu-item')
                ?.querySelector('.mobile-menu-toggle');
              if (otherButton) {
                const otherIcon = otherButton.querySelector('.toggle-icon');
                if (otherIcon) {
                  otherIcon.textContent = '+';
                }
              }
            }
          });

        // Toggle mega menu hiện tại: thay đổi max-height từ 0 sang fit-content
        if (
          !megaContent.style.maxHeight ||
          megaContent.style.maxHeight === '0px'
        ) {
          // Mở ra: dùng fit-content
          megaContent.style.maxHeight = megaContent.scrollHeight + 'px';
          if (icon) icon.textContent = '−';
        } else {
          // Đóng lại
          megaContent.style.maxHeight = '0px';
          if (icon) icon.textContent = '+';
        }
      }
    });
  });
});

// (function ($) {
// $(document).ready(function () {
// // Sử dụng delegated event để chắc chắn bắt được nút trong Swiper
// $(document).on('click', '.ajax_add_to_cart', function (e) {
// e.preventDefault(); // Chặn load trang ngay lập tức
// e.stopImmediatePropagation(); // Chặn các script khác xen vào

// const $button = $(this);
// const product_id = $button.data('product_id');

// if (!product_id) return;

// $button.addClass('loading opacity-50');

// // Gửi dữ liệu AJAX
// const data = {
// action: 'woocommerce_add_to_cart',
// product_id: product_id,
// quantity: 1,
// };

// $.post(wc_add_to_cart_params.ajax_url, data, function (response) {
// $(document.body).trigger('added_to_cart', [
// response.fragments,
// response.cart_hash,
// $button,
// ]);
// $button.removeClass('loading opacity-50');
// });

// return false; // Chặn lần cuối
// });
// });
// })(jQuery);

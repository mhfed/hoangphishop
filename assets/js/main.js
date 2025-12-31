document.addEventListener('DOMContentLoaded', function () {
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

      // Kích hoạt sự kiện để các script khác có thể lắng nghe
      $(document.body).trigger('added_to_cart', [
        response.fragments,
        response.cart_hash,
        $thisbutton,
      ]);

      // Trigger WooCommerce fragments refresh event
      $(document.body).trigger('wc_fragments_refreshed');

      // Mở Side Cart
      openSideCart();

      $thisbutton.removeClass('loading opacity-50').addClass('added');
    });
  });

  function openSideCart() {
    jQuery('#side-cart').removeClass('translate-x-full');
    jQuery('#side-cart-overlay').removeClass('hidden').addClass('opacity-100');
    jQuery('body').addClass('overflow-hidden'); // Chặn scroll web
  }

  function closeSideCart() {
    jQuery('#side-cart').addClass('translate-x-full');
    jQuery('#side-cart-overlay').addClass('opacity-0').addClass('hidden');
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
    $(document.body).on('added_to_cart', function () {
      openSideCart();
    });
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

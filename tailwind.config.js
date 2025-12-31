/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './**/*.php', // Quét tất cả file PHP trong theme
    './inc/**/*.php', // Quét thư mục inc (nếu có)
    './assets/**/*.js', // Quét JS
    './woocommerce/**/*.php', // THÊM DÒNG NÀY ĐỂ QUÉT FOLDER WOOCOMMERCE
    './template-parts/**/*.php', // Quét template parts cho product listings
  ],
  theme: {
    extend: {
      colors: {
        // Design tokens - Hệ màu mới
        primary: '#6B9E83', // Xanh lá nhạt, tươi mát
        secondary: '#F8F4F0', // Be/nude ấm áp
        'text-primary': '#333333', // Đen mềm mại cho văn bản chính
        'text-secondary': '#666666', // Xám đậm cho văn bản phụ
        border: '#D1D5DB', // Xám nhạt cho đường viền
        'background-light': '#FFFFFF', // Trắng tinh khiết
        'background-gradient-start': '#FFF9F5', // Hồng đào nhạt cho gradient
        'background-gradient-end': '#FFFDEB', // Be/vàng kem nhạt cho gradient
      },
      fontFamily: {
        heading: ['Playfair Display', 'serif'], // Font cho tiêu đề
        body: ['Inter', 'sans-serif'], // Font cho nội dung
        sans: ['Inter', 'sans-serif'], // Giữ lại cho compatibility
      },
      spacing: {
        // Extend spacing cho các giá trị lớn hơn
        '6xl': '8rem', // 128px - cho khoảng cách lớn giữa các phần
      },
      letterSpacing: {
        luxury: '0.15em',
      },
    },
  },
  plugins: [],
};

(function ($) {
  document.addEventListener('DOMContentLoaded', () => {
    if ($('.d-slider1').length > 0) {
      const options = {
        lazy: true,
        centeredSlides: false,
        loop: false,
        slidesPerView: 1,
        autoplay: false,
        spaceBetween: 32,
        breakpoints: {
          320: { slidesPerView: 1 },
          550: { slidesPerView: 2 },
          991: { slidesPerView: 3 },
          1400: { slidesPerView: 3 },
          1500: { slidesPerView: 4 },
          1920: { slidesPerView: 6 },
          2040: { slidesPerView: 7 },
          2440: { slidesPerView: 8 }
        },
        pagination: {
          el: '.swiper-pagination'
        },
        navigation: {
          nextEl: '.swiper-button-next',
          prevEl: '.swiper-button-prev'
        },
        scrollbar: {
          el: '.swiper-scrollbar'
        }
      };

      // Solo inicializamos si no existe uno ya
      if (!$('.d-slider1')[0].swiper) {
        const swiper = new Swiper('.d-slider1', options);
        swiper.update();
        console.log('Swiper initialized correctamente');
      }
    }
  });
})(jQuery);

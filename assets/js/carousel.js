(function ($) {
  "use strict";

  function safeParseJSON(str) {
    try {
      return str ? JSON.parse(str) : {};
    } catch (e) {
      console.warn("SEIC: Invalid options JSON", e);
      return {};
    }
  }

  function initSeicCarousel($scope) {
    var $root = $scope.find(".seic-carousel").first();
    if (!$root.length) return;

    // Prevent duplicate initialization
    if ($root.data("seic-initialized")) {
      return;
    }

    // Strict single-instance check
    var existingInstance = $root.data("seic-swiper");
    if (existingInstance) {
      if (typeof existingInstance.destroy === 'function') {
        existingInstance.destroy(true, true);
      }
      $root.removeData("seic-swiper");
      $root.removeData("seic-initialized");
    }

    $root.data("seic-initialized", true);

    var opts = safeParseJSON($root.attr("data-seic-options"));
    var swiperEl = $root.find(".seic-swiper, .swiper-container").get(0);
    var rootId = $root.attr('id');

    if (!swiperEl || !rootId) {
      return;
    }

    console.log("SEIC: Initializing carousel", rootId, opts);

    var config = {
      slidesPerView: opts.slidesPerView || 3,
      slidesPerGroup: opts.slidesPerGroup || 1,
      spaceBetween: opts.spaceBetween || 12,
      loop: !!opts.loop,
      loopedSlides: null, // Let Swiper auto-calculate
      speed: Math.max(150, Math.min(600, opts.speed || 500)),
      centeredSlides: false,
      grabCursor: true,
      observer: true,
      observeParents: true,
      breakpoints: opts.breakpoints || {},
      watchOverflow: false,
      roundLengths: true,
      resistanceRatio: 0.85
    };

    // Lazy load configuration
    if (opts.lazy) {
      config.preloadImages = false;
      config.lazy = {
        loadPrevNext: true,
        loadPrevNextAmount: 2
      };
    } else {
      config.preloadImages = true;
    }

    // Navigation configuration - use direct DOM element references
    if (opts.navigation) {
      var $next = $root.find('.seic-next');
      var $prev = $root.find('.seic-prev');
      
      if ($next.length && $prev.length) {
        config.navigation = {
          nextEl: $next[0],
          prevEl: $prev[0],
          disabledClass: 'swiper-button-disabled',
          hiddenClass: 'swiper-button-hidden'
        };
      }
    }

    // Pagination configuration
    if (opts.pagination) {
      var $pag = $root.find('.seic-pagination, .swiper-pagination');
      if ($pag.length) {
        config.pagination = {
          el: $pag[0],
          clickable: true,
          dynamicBullets: false,
          type: 'bullets'
        };
      }
    }

    // Autoplay configuration
    if (opts.autoplay) {
      config.autoplay = {
        delay: opts.autoplayDelay || 5000,
        disableOnInteraction: !!opts.pauseOnInteraction,
        pauseOnMouseEnter: !!opts.pauseOnHover
      };
    }

    // Initialize Swiper
    try {
      if (typeof Swiper === 'undefined') {
        return;
      }

      var swiperInstance = new Swiper(swiperEl, config);
      
      if (swiperInstance) {
        $root.data("seic-swiper", swiperInstance);

        // Force update after a short delay to ensure proper rendering
        setTimeout(function () {
          if (swiperInstance && swiperInstance.update) {
            swiperInstance.update();
          }
        }, 100);

        // Manual fallback for navigation clicks
        var $next = $root.find('.seic-next');
        var $prev = $root.find('.seic-prev');
        
        if ($next.length) {
          $next.off('.seicforce').on('click.seicforce', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (swiperInstance && typeof swiperInstance.slideNext === 'function') {
              swiperInstance.slideNext();
            }
          });
        }
        
        if ($prev.length) {
          $prev.off('.seicforce').on('click.seicforce', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (swiperInstance && typeof swiperInstance.slidePrev === 'function') {
              swiperInstance.slidePrev();
            }
          });
        }
      }

    } catch (err) {
      // Error handling
    }
  }

  // Initialize on Elementor frontend ready
  $(window).on("elementor/frontend/init", function () {
    if (typeof elementorFrontend === "undefined") {
      console.warn("SEIC: Elementor frontend not available");
      return;
    }
    
    console.log("SEIC: Registering widget handler");
    elementorFrontend.hooks.addAction(
      "frontend/element_ready/seic_image_carousel.default",
      initSeicCarousel
    );
  });

  // Fallback for non-Elementor contexts
  $(document).ready(function() {
    if (typeof elementorFrontend === "undefined") {
      console.log("SEIC: Initializing outside Elementor context");
      $('.seic-carousel').each(function() {
        initSeicCarousel($(this).parent());
      });
    }
  });

})(jQuery);
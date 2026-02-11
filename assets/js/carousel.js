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
      console.log("SEIC: Already initialized, skipping");
      return;
    }

    // Strict single-instance check
    var existingInstance = $root.data("seic-swiper");
    if (existingInstance) {
      if (typeof existingInstance.destroy === 'function') {
        console.log("SEIC: Destroying existing instance");
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
      console.warn("SEIC: Swiper element or root ID not found");
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
        console.log("SEIC: Navigation elements found");
        config.navigation = {
          nextEl: $next[0],
          prevEl: $prev[0],
          disabledClass: 'swiper-button-disabled',
          hiddenClass: 'swiper-button-hidden'
        };
      } else {
        console.warn("SEIC: Navigation elements not found in DOM");
      }
    }

    // Pagination configuration
    if (opts.pagination) {
      var $pag = $root.find('.seic-pagination, .swiper-pagination');
      if ($pag.length) {
        console.log("SEIC: Pagination element found:", $pag.length, "elements");
        config.pagination = {
          el: $pag[0],
          clickable: true,
          dynamicBullets: false,
          type: 'bullets'
        };
      } else {
        console.warn("SEIC: Pagination enabled but element not found in DOM");
      }
    }

    // Autoplay configuration
    if (opts.autoplay) {
      config.autoplay = {
        delay: opts.autoplayDelay || 5000,
        disableOnInteraction: !!opts.pauseOnInteraction,
        pauseOnMouseEnter: !!opts.pauseOnHover
      };
      console.log("SEIC: Autoplay enabled with delay:", config.autoplay.delay);
    }

    // Event handlers
    config.on = {
      init: function () {
        console.log("SEIC: Swiper initialized successfully");
        console.log("SEIC: Total slides:", this.slides.length);
        console.log("SEIC: Slides per view:", this.params.slidesPerView);
        console.log("SEIC: Loop mode:", this.params.loop);
        console.log("SEIC: Pagination enabled:", !!this.pagination);
        if (this.pagination && this.pagination.el) {
          console.log("SEIC: Pagination bullets:", this.pagination.bullets ? this.pagination.bullets.length : 0);
        }
      },
      slideChange: function () {
        console.log("SEIC: Slide changed to index:", this.realIndex);
      },
      slideChangeTransitionStart: function () {
        console.log("SEIC: Transition started");
      },
      slideChangeTransitionEnd: function () {
        console.log("SEIC: Transition ended");
      },
      reachEnd: function() {
        console.log("SEIC: Reached end of carousel");
      },
      reachBeginning: function() {
        console.log("SEIC: Reached beginning of carousel");
      },
      navigationNext: function() {
        console.log("SEIC: Next button clicked");
      },
      navigationPrev: function() {
        console.log("SEIC: Previous button clicked");
      }
    };

    // Initialize Swiper
    try {
      if (typeof Swiper === 'undefined') {
        console.error("SEIC: Swiper library not loaded!");
        return;
      }

      console.log("SEIC: Creating Swiper instance with config:", config);
      var swiperInstance = new Swiper(swiperEl, config);
      
      if (swiperInstance) {
        $root.data("seic-swiper", swiperInstance);
        console.log("SEIC: Swiper instance created and stored");

        // Force update after a short delay to ensure proper rendering
        setTimeout(function () {
          if (swiperInstance && swiperInstance.update) {
            swiperInstance.update();
            console.log("SEIC: Updated swiper layout");
          }
        }, 100);

        // Manual fallback for navigation clicks
        var $next = $root.find('.seic-next');
        var $prev = $root.find('.seic-prev');
        
        if ($next.length) {
          $next.off('.seicforce').on('click.seicforce', function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("SEIC: Manual next button click handler");
            if (swiperInstance && typeof swiperInstance.slideNext === 'function') {
              swiperInstance.slideNext();
            }
          });
        }
        
        if ($prev.length) {
          $prev.off('.seicforce').on('click.seicforce', function (e) {
            e.preventDefault();
            e.stopPropagation();
            console.log("SEIC: Manual prev button click handler");
            if (swiperInstance && typeof swiperInstance.slidePrev === 'function') {
              swiperInstance.slidePrev();
            }
          });
        }
      }

    } catch (err) {
      console.error("SEIC: Initialization error:", err);
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
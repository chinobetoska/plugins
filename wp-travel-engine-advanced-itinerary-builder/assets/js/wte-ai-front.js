jQuery(function ($) {
  /**
   * show hide Button triger for itinerary
   */
  $(document).on("click", ".advanced-itinerary-row .accordion-tabs-toggle", function () {
    var $this = $(this);

    if ($('style#itinerary-content-show').length) {
      $('style#itinerary-content-show').remove();
    }

    $iteneraryContent = $($this.closest('.advanced-itinerary-row').find('.itinerary-content'));
    $iteneraryContent.slideToggle(350);
    $iteneraryContent.toggleClass("show"), $this.toggleClass("active");
    $this.find(".dashicons.dashicons-arrow-down.custom-toggle-tabs").toggleClass("open");
    $this.closest('.advanced-itinerary-row').toggleClass("active");
  });

  $(".aib-button-toggle input.checkbox").on(
    "change",
    function () {
      if ($(this).is(":checked")) {
        $(this)
          .closest(".wte-itinerary-header-wrapper")
          .siblings(".post-data")
          .children()
          .addClass("row-active");
        $(".row-active")
          .find(".accordion-tabs-toggle .rotator")
          .addClass("open");
        $(".row-active").find(".accordion-tabs-toggle").addClass("active");
        $(".row-active").find(".itinerary-content").slideDown();
        $(".row-active").find(".itinerary-content").addClass("show");
      } else {
        $(this)
          .closest(".wte-itinerary-header-wrapper")
          .siblings(".post-data")
          .children()
          .removeClass("row-active");
        $(".itinerary-row")
          .find(".accordion-tabs-toggle .rotator")
          .removeClass("open");
        $(".itinerary-row")
          .find(".accordion-tabs-toggle")
          .removeClass("active");
        $(".itinerary-row").find(".itinerary-content").slideUp();
        $(".itinerary-row").find(".itinerary-content").removeClass("show");
      }
    }
  );

  $(document).on("click", ".itinerary-sleep-mode a", function (e) {
    e.preventDefault();
    $(this).parents('.advanced-itinerary-row').find('.content-additional-sleep-mode').show();
    $(this).parents('.advanced-itinerary-row').find('.wte-ai-close-button').show();
    $(this).parents('.itinerary').find('.wte-ai-overlay').fadeIn(300);
  });
  /**
   * Additional Detail "Close" for itinerary mode
   */
  $(document).on("click", ".wte-ai-overlay, .wte-ai-close-button", function () {
    $(this).closest('.content-additional-sleep-mode').fadeOut(300);
    $(this).parents('.itinerary').find('.wte-ai-overlay').fadeOut(300);
  });

  jQuery(document).on('click', '.content-additional-sleep-mode', function (event) {
    if ( event.target.matches('.content-additional-sleep-mode') ) {
      $(this).fadeOut(300)
    }
  });

  if ($('.advanced-itinerary-row .itenary-detail-gallery').length) {
    $('.advanced-itinerary-row .itenary-detail-gallery').each(function (index) {
      var mp_this = $(this);
      // mp_this.magnificPopup({
      //   delegate: 'a.itinerary-gallery-link',
      //   type: 'image',
      //   gallery: {
      //     enabled: true
      //   }
      // });
    });
  } //custom scrollbar

  $(".itenary-detail-gallery").mCustomScrollbar({
    axis: "x",
    setLeft: 0,
    advanced: {
      autoExpandHorizontalScroll: true
    }
  });

  $(".content-additional-sleep-mode .advanced-sleep-mode-content").mCustomScrollbar();
});

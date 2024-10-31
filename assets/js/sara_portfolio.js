(function($) {
  $(".sara-container").isotope({
    itemSelector: ".mix",
    layoutMode: "fitRows"
  });
  $(".sara-btn-wraper button").click(function() {
    $(".sara-btn-wraper button").removeClass("active");
    $(this).addClass("active");

    var selector = $(this).attr("data-filter");
    $(".sara-container").isotope({
      filter: selector
    });
    return false;
  });
})(jQuery);

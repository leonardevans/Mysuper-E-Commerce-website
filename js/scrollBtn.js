$("#scrollTop").click(function() {
  $("html, body").animate(
    {
      scrollTop: $("#toTop").offset().top,
    },
    1000
  );
});

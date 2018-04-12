
$(window).scroll(function(){
    $(".scroll").css("opacity", 1 - $(window).scrollTop() / 10);
  });

/*win.scroll(function(){
  scrollPosition = win.scrollTop();
  scrollRatio = 1 - scrollPosition / 300;
  $(".top").css("opacity", scrollRatio);
*/
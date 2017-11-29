jQuery(document).scroll(function(){
  jQuery('.header-nav-wrapper').toggleClass('scrolled', jQuery(this).scrollTop() > 50);
});

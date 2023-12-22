const search = document.getElementById('search');
const dropdownContent = document.getElementById('dropdownContent');
//dropdown menu logic
search.addEventListener('click', () =>{
    dropdownContent.style.display = dropdownContent.style.display == 'block' ? 'none' : 'block';
});

// Scrolling navbar animation logic , makes it go away when user scrolls down and appear when user scrolls up
$(function(){
  var lastScrollTop = 0;
  var navHeight = $('.nav-bar').outerHeight();
  var mobileNavHeight = $('.nav-bar-mobile').outerHeight();

  $(window).scroll(function(){
    var scrolled = $(document).scrollTop();

    if(scrolled > navHeight || scrolled > mobileNavHeight){
      if (scrolled > lastScrollTop){
        $('.nav-bar').removeClass('sticky').addClass('animate');
        $('.nav-bar-mobile').removeClass('sticky').addClass('animate');
      } else {
        $('.nav-bar').removeClass('animate').addClass('sticky');
        $('.nav-bar-mobile').removeClass('animate').addClass('sticky');
      }
    }
    else{
      $('.nav-bar').removeClass('animate sticky');
      $('.nav-bar-mobile').removeClass('animate sticky');
    }

    lastScrollTop = scrolled;
  });
});
// get the nodes from the DOM, (or to the DOM? or into the DOM?)
const search = document.getElementById('search');
const dropdownContent = document.getElementById('dropdownContent');
//dropdown menu logic for the mobile hamburger menu
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

    if(scrolled > navHeight || scrolled > mobileNavHeight){ //if the user has scrolled more than the height of the navbar or mobile navbar
      if (scrolled > lastScrollTop){ //if the user is scrolling down, since last scroll pos is saved in lastScrollTop we can always calc if user scrolls down again, and add the correct class, likewise if the user scrolls up we can remove class and add
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

    lastScrollTop = scrolled; //save the last scroll position
  });
});
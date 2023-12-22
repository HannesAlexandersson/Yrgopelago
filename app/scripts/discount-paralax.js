document.addEventListener('scroll', function() {
  let scrollPosition = window.scrollY;
  document.querySelector('.discount-bg-beach').style.backgroundPositionY = -scrollPosition * 0.2 + 'px';
  document.querySelector('.discount-bg-palms').style.backgroundPositionY = scrollPosition * 0.05 + 'px';
  document.querySelector('.discount-bg-clouds').style.backgroundPositionY = -scrollPosition * 0.001 + 'px';


});
// parallax logic
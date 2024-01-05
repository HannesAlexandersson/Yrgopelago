document.addEventListener('scroll', function() {
  let scrollPosition = window.scrollY;
  document.querySelector('.discount-bg-beach').style.backgroundPositionY = -scrollPosition * 0.15 + 'px';
  document.querySelector('.discount-bg-palms').style.backgroundPositionY = scrollPosition * 0.05 + 'px';
  document.querySelector('.discount-bg-clouds').style.backgroundPositionY = -scrollPosition * 0.005 + 'px';


});
// parallax logic for the discount page
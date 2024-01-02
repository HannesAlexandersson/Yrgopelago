// Logic for the hero sections parallax effect The first block is the base paralax effect, it sets the different
// background images to scroll at different speeds relative to their starting positions in the viewport.
//The second block is the parallax effect for the "floating" element in the hero section. It sets the element
//to scroll at a different speed than the background images
document.addEventListener('scroll', function() {
  let scrollPosition = window.scrollY;
 
  document.querySelector('.pool').style.backgroundPositionY = scrollPosition * 0.1 + 'px';
  document.querySelector('.nightsky').style.backgroundPositionY = -scrollPosition * 0.1 + 'px';
});


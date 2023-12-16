// Logic for the hero sections parallax effect The first block is the base paralax effect, it sets the different
// background images to scroll at different speeds relative to their starting positions in the viewport.
//The second block is the parallax effect for the "floating" element in the hero section. It sets the element
//to scroll at a different speed than the background images
document.addEventListener('scroll', function() {
  let scrollPosition = window.scrollY;
  document.querySelector('.hero-content').style.backgroundPositionY = -scrollPosition * 1.5 + 'px';
  document.querySelector('.pool').style.backgroundPositionY = scrollPosition * 0.2 + 'px';
  document.querySelector('.nightsky').style.backgroundPositionY = -scrollPosition * 0.1 + 'px';
});

// callback function for the scroll event listener
function parallax() {
	var s = document.getElementById("floater");
  var yPos = 0 - window.scrollY/2;
  s.style.top = 50 + yPos + "%";
}
// eventlistener for the scroll event on the window object IE the whole page
window.addEventListener("scroll", function(){
	parallax();
});
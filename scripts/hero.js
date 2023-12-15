document.addEventListener('scroll', function() {
  let scrollPosition = window.scrollY;
  document.querySelector('.hero-content').style.backgroundPositionY = -scrollPosition * 1.5 + 'px';
  document.querySelector('.pool').style.backgroundPositionY = scrollPosition * 0.2 + 'px';
  document.querySelector('.nightsky').style.backgroundPositionY = -scrollPosition * 0.1 + 'px';


});


function parallax() {
	var s = document.getElementById("floater");
  var yPos = 0 - window.scrollY/4;
  s.style.top = 50 + yPos + "%";


}

window.addEventListener("scroll", function(){
	parallax();
});
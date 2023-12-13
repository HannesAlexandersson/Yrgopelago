document.addEventListener('scroll', function() {
  let scrollPositionY = window.scrollY;

  document.querySelector('.rooms-bg-dark').style.backgroundPositionY = scrollPositionY * 0.05 + 'px';
  document.querySelector('.rooms-bg-pool').style.backgroundPositionY = -scrollPositionY * 0.3 + 'px';
  document.querySelector('.rooms-content').style.backgroundPositionY = -scrollPositionY * 0.2 + 'px';
});

document.addEventListener('click', function(event) {
  document.querySelector('.room').classList.toggle('expanded');

});


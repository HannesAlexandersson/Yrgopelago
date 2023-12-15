
document.addEventListener('click', function(event) {
  var clickedRoom = event.target.closest('.room');

  if (clickedRoom) {
    clickedRoom.classList.toggle('expanded');
    clickedRoom.querySelector('.expandable').classList.toggle('hidden');
  }
});

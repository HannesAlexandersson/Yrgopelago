const track = document.getElementById("image-track");//get the image track i.e the div containing the images

const handleOnDown = e => track.dataset.mouseDownAt = e.clientX;//get the mouse position on the x axis when the mouse is clicked

const handleOnUp = () => { //reset the mouse position on the x axis when the mouse is released
  track.dataset.mouseDownAt = "0";
  track.dataset.prevPercentage = track.dataset.percentage;
}

const handleOnMove = e => { //get the mouse position on the x axis when the mouse is moved and calculate the percentage of the movement to adjust the slider
  if(track.dataset.mouseDownAt === "0") return; //if the mouse is not clicked, do nothing, or if the mouse is not moved, return

  const mouseDelta = parseFloat(track.dataset.mouseDownAt) - e.clientX, //get the mouse position on the x axis when the mouse is moved
        maxDelta = window.innerWidth / 2;

  const percentage = (mouseDelta / maxDelta) * -100, //calculate the percentage of the movement to adjust the slider
        nextPercentageUnconstrained = parseFloat(track.dataset.prevPercentage) + percentage, //get the percentage of the movement to adjust the slider
        nextPercentage = Math.max(Math.min(nextPercentageUnconstrained, 0), -100); // set the max and min values so the slider doesnt gp on for infinity

  track.dataset.percentage = nextPercentage; // set the percentage of the movement to adjust the images in the slider
//animations instead of transitions for smoother performance
  track.animate({
    transform: `translate(${nextPercentage}%, -50%)`
  }, { duration: 1200, fill: "forwards" });

  for(const image of track.getElementsByClassName("image")) { // set the object position of the images in the slider so its slightly offset from center to create the "parrallax" effect
    image.animate({
      objectPosition: `${100 + nextPercentage}% center`
    }, { duration: 1200, fill: "forwards" });
  }
}



window.onmousedown = e => handleOnDown(e);

window.ontouchstart = e => handleOnDown(e.touches[0]);

window.onmouseup = e => handleOnUp(e);

window.ontouchend = e => handleOnUp(e.touches[0]);

window.onmousemove = e => handleOnMove(e);

window.ontouchmove = e => handleOnMove(e.touches[0]);
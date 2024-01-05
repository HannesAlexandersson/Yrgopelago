<div class="preloader-container">
  <h1 class="preloader">
      <span class="letterL preloader-span">l</span>
      <span class="letterO preloader-span">o</span>
      <span class="letterA preloader-span">a</span>
      <span class="letterD preloader-span">d</span>
      <span class="letterI preloader-span">i</span>
      <span class="letterN preloader-span">n</span>
      <span class="letterG preloader-span">g</span>
    </h1>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function () {// When the DOM is loaded
      // and when the window is loaded all assets(for some reason I need both for the animation to work properly)
      window.onload = function () {
        // Hide the loading animation-container when all assets are loaded
      document.querySelector('.preloader-container').style.display = 'none';
      document.body.style.overflowY = 'auto'; // Enable vertical-scrolling after loading is completed

    };
  });
</script>
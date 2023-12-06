<div class="booking-bg">
  <div class="booking-header secondary-font">
    <h1>Booking</h1>
  </div>
  <div class="booking-left">
    <div class="booking-form-wrapper">
      <form action="/scripts/booking-form.php" method="post">

      </form>
    </div>
  </div>
  <div class="booking-right">
    <div class="calender-container">
      <?php require 'scripts/Calendar.php';
      include 'Calendar.php';
      $calendar = new Calendar('2024-01-12');
      echo $calendar;
      ?>
    </div>
  </div>
</div>
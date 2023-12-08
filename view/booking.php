<div class="booking-bg">
  <div class="booking-header secondary-font">
    <h1>Booking</h1>
  </div>
  <div class="booking-left secondary-font">
    <div class="booking-form-wrapper">
      <form action="/scripts/handle-booking.php" method="post" id="bookingForm" class="booking-form">
        <label for="room">Choose a Room:</label>
        <select id="room" name="room">
          <option value="the_gaze">The Gaze</option>
          <option value="the_tranquility">The Tranquility</option>
          <option value="the_presidential">The Presidential</option>
        </select>

        <label for="arrivalDate">Arrival Date:</label>
        <input type="text" id="arrivalDate" name="arrivalDate" />
        <button type="button" id="lockArrivalDate" onclick="toggleLockArrivalDate()">Lock Arrival Date</button>

        <label for="departureDate">Departure Date:</label>
        <input type="text" id="departureDate" name="departureDate" />

        <button type="button" id="clearForm" onclick="clearBookingForm()">Clear Form</button>
        <button type="submit">Submit Booking</button>
      </form>
    </div>
  </div>
  <div class="booking-right">
    <div class="calender-container" id="calendar"></div>
    <script src="/scripts/booking-form.js"></script>
  </div>
</div>

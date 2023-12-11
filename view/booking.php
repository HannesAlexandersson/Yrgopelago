<div class="booking-bg">
  <div class="booking-header secondary-font">
    <h1>Booking</h1>
  </div>
  <div class="booking-left secondary-font">
    <div class="booking-form-wrapper">
      <form action="/scripts/handle-booking.php" method="post" id="bookingForm" class="booking-form">
        <!-- uncommeting transfercode when live -->
        <label for="user_id" placeholder-text="">Enter your transfer-code:</label>
        <input type="text" id="transfercode" name="transfercode" required="required" />

        <label for="room">Choose a Room:</label>
        <select id="room" name="room">
          <option value="1">The Gaze</option>
          <option value="2">The Tranquility</option>
          <option value="3">The Presidential</option>
        </select>

        <label for="arrivalDate">Arrival Date:</label>
        <input type="date" id="arrivalDate" name="arrivalDate" />
        <button type="button" id="lockArrivalDate" onclick="toggleLockArrivalDate()">Lock Arrival Date</button>

        <label for="departureDate">Departure Date:</label>
        <input type="date" id="departureDate" name="departureDate" />

        <!-- Checkboxes for additional features -->
        <div class="features-checkbox">

          <input type="checkbox" id="bedtimeStoryteller" name="features[]" value="2">
          <label for="bedtimeStoryteller">Bedtime Storyteller</label>

          <input type="checkbox" id="undergroundHotsprings" name="features[]" value="3">
          <label for="undergroundHotsprings">Underground Hotsprings</label>

          <input type="checkbox" id="massageTherapy" name="features[]" value="1">
          <label for="massageTherapy">Massage Therapy</label>
        </div>

        <button type="button" id="clearForm" onclick="clearBookingForm()">Clear Form</button>
        <button type="submit">Submit Booking</button>
      </form>
    </div>
  </div>

  <div class="booking-right secondary-font">
    <div class="calendar-container" id="calendar">

    </div>
  </div>


<script src="/scripts/booking-form.js"></script>
</div>


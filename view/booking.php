<div class="booking-bg">
  <div class="booking-header secondary-font">
    <h1>Booking</h1>
  </div>
  <div class="booking-left secondary-font">
    <div class="booking-form-wrapper">
      <form id="bookingForm" class="booking-form">

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
        <button class="date-btn btn" type="button" id="lockArrivalDate" onclick="toggleLockArrivalDate()">Lock Arrival Date</button>

        <label for="departureDate">Departure Date:</label>
        <input type="date" id="departureDate" name="departureDate" />
        <button class="date-btn btn" type="button" id="lockDepartureDate" onclick="toggleLockDepartureDate()">Lock Departure Date</button>

        <div class="features-checkbox">

          <label for="bedtimeStoryteller">Bedtime Storyteller</label>
          <input type="checkbox" class="feature-checkbox" id="bedtimeStoryteller" name="features[]" value="2">

          <label for="undergroundHotsprings">Underground Hotsprings</label>
          <input type="checkbox" class="feature-checkbox" id="undergroundHotsprings" name="features[]" value="3">

          <label for="massageTherapy">Massage Therapy</label>
          <input type="checkbox" class="feature-checkbox" id="massageTherapy" name="features[]" value="1">
        </div>

        <button class="clr-btn btn" type="button" id="clearForm" onclick="clearBookingForm()">Clear Form</button>
        <button class="submit-btn btn" type="submit">Submit Booking</button>
        <button class="btn reload-btn" type="button" id="reloadButton">Show booking</button>
      </form>
    </div>
    <div class="display-totalcost default-font">
      <p><span id="totalCost"></span></p>
    </div>
  </div>

  <div class="booking-right secondary-font">
    <div class="calendar-container" id="calendar">

    </div>
  </div>



<script src="/scripts/booking-form.js"></script>
</div>


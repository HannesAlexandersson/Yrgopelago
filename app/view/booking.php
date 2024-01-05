<div class="booking-bg">
  <div class="booking-header secondary-font">
    <h1>Booking</h1>
  </div>

  <label for="popup-toggle" class="btn help-btn">HOW TO BOOK</label>

  <input type="checkbox" id="popup-toggle" class="hidden-checkbox">
  <div class="overlay"></div>

  <div class="pop-up">
    <label for="popup-toggle" class="close-btn">Close</label>
    <p>
      1. Choose an arrival date from the calendar, then click the lock button to lock the date.<br>
      2. Choose a departure date from the calendar, then click the lock button to lock the date.<br>
      3. IMPORTANT! If you want to change dates, you MUST press the lock buttons again or else an incorrect cost will display!<br>
      4. Choose a room from the dropdown menu.<br>
      5. Choose the features you want to add to your room.<br>
      6. The total cost will display under the buttons.<br>
      7. Enter a transfercode with the same amount as the totalcost<br>
      8. Press the submit button to book your room.<br>
      9. Press the show booking button to see your booking in the calender.<br>
    </p>
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
        <input type="date" required id="arrivalDate" name="arrivalDate" min="2024-01-01" max="2024-01-31" />
        <button class="date-btn btn" type="button" id="lockArrivalDate" onclick="toggleLockArrivalDate()">Lock Arrival Date</button>

        <label for="departureDate">Departure Date:</label>
        <input type="date" required id="departureDate" name="departureDate" min="2024-01-01" max="2024-01-31" />
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

  <script src="/app/scripts/calculations.js"></script>
  <script src="/app/scripts/calendar.js"></script>
  <script src="/app/scripts/booking-form.js"></script>
</div>


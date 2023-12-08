<div class="booking-bg">
  <div class="booking-header secondary-font">
    <h1>Booking</h1>
  </div>
  <div class="booking-left secondary-font">
    <div class="booking-form-wrapper">
      <form id="bookingForm" class="booking-form">
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
    <script>
      let isArrivalDateLocked = false;

      function toggleLockArrivalDate() {
        isArrivalDateLocked = !isArrivalDateLocked;

        var lockArrivalDateButton = document.getElementById('lockArrivalDate');
        lockArrivalDateButton.innerText = isArrivalDateLocked ? 'Unlock Arrival Date' : 'Lock Arrival Date';

        if (isArrivalDateLocked) {
          // Disable the "Lock Arrival Date" button once the arrival date is confirmed
          document.getElementById('lockArrivalDate').disabled = true;
        }
      }

      function clearBookingForm() {
        // Clear the form fields and reset the "Lock Arrival Date" button
        document.getElementById('bookingForm').reset();
        isArrivalDateLocked = false;
        document.getElementById('lockArrivalDate').disabled = false;
        document.getElementById('lockArrivalDate').innerText = 'Lock Arrival Date';
      }

      document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth',
          events: [],
          dateClick: function (info) {
            if (isArrivalDateLocked) {
              // If arrival date is locked, set the clicked date as the departure date
              document.getElementById('departureDate').value = info.dateStr;
            } else {
              // If arrival date is not locked, set the clicked date as the arrival date
              document.getElementById('arrivalDate').value = info.dateStr;
            }
          },
        });

        calendar.render();

        var bookingForm = document.getElementById('bookingForm');
        bookingForm.addEventListener('submit', function (event) {
          event.preventDefault();

          var room = document.getElementById('room').value;
          var arrivalDate = document.getElementById('arrivalDate').value;
          var departureDate = document.getElementById('departureDate').value;

          // Validate and process the form data
          if (room && arrivalDate && departureDate) {
            // Add the booking to the calendar
            calendar.addEvent({
              title: room + ' Booking',
              start: arrivalDate,
              end: departureDate,
            });

            // Clear the form and enable the "Lock Arrival Date" button
            bookingForm.reset();
            isArrivalDateLocked = false;
            document.getElementById('lockArrivalDate').disabled = false;
            document.getElementById('lockArrivalDate').innerText = 'Lock Arrival Date';
          } else {
            alert('Please fill in all fields.');
          }
        });
      });
    </script>
  </div>
</div>

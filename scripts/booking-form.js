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
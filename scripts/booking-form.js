document.addEventListener('DOMContentLoaded', function () {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'dayGridMonth',
    events: [],
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

      // Clear the form
      bookingForm.reset();
    } else {
      alert('Please fill in all fields.');
    }
  });
});
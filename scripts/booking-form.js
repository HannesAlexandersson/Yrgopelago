let isArrivalDateLocked = false;
let bookedDates = {
  'The Gaze': [],
  'The Tranquility': [],
  'The Presidential': []
};

console.log(bookedDates);
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
    },// uncomment this code to disable dates before d day
    /* validRange: {
      start: '2024-01-01',
      end: '2024-02-01'
    } */
  });
  //initialise the calendar
  calendar.render();


  // Add a listener to the booking form to be able to submit bookings
  var bookingForm = document.getElementById('bookingForm');
  bookingForm.addEventListener('submit', function (event) {
    event.preventDefault();

    var user_id = document.getElementById('transfercode').value;
    var room_id = document.getElementById('room').value;
    var arrivalDate = document.getElementById('arrivalDate').value;
    var departureDate = document.getElementById('departureDate').value;
    var room;
    if(room_id == 1){
      var room = 'The Gaze';
    }else if(room_id == 2){
      var room = 'The Tranquility';
    }else if(room_id == 3){
      var room = 'The Presidential';
    }
    console.log(user_id, room_id, arrivalDate, departureDate, room);
    // Validate and process the form data
    if (room_id && arrivalDate && departureDate) {
      // Check room availability with a server request
      checkRoomAvailability(user_id, room_id, arrivalDate, departureDate)
        .then(function (isAvailable) {
          if (isAvailable) {
            // Room is available, proceed with the booking

            calendar.addEvent({
              title: room + ' Booking',
              start: arrivalDate,
              end: departureDate,
            });

            // Update the booked dates list (you may also want to update the server-side database)
           /*  updateBookedDates(room, arrivalDate, departureDate); */

            // Clear the form and enable the "Lock Arrival Date" button
            bookingForm.reset();
            isArrivalDateLocked = false;
            document.getElementById('lockArrivalDate').disabled = false;
            document.getElementById('lockArrivalDate').innerText = 'Lock Arrival Date';
          } else {
            alert('This room is already booked for the selected dates. Please choose different dates.');
          }
        })
        .catch(function (error) {
          console.error('Error checking room availability:', error);
          alert('An error occurred while checking room availability. Please try again.');
        });
    } else {
      alert('Please fill in all fields.');
    }
  });

  // Function to check room availability with a server request
  function checkRoomAvailability(user_id, room, arrivalDate, departureDate) {
    return fetch('/scripts/handle-booking.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        user_id: user_id,
        room: room,
        arrivalDate: arrivalDate,
        departureDate: departureDate,
      }),
    })
      .then(function (response) {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
       return response.json(); // Change to response.text() to get the raw response text//return response.json();
      })
      /* .then(function (data) {
        return data.isAvailable;
      }) */
      /* .then(function (responseText) {
        console.log('Server Response:', responseText); // Log the entire response
        return JSON.parse(responseText); // Parse the response text as JSON
      }) */
      .then(function (jsonData) {
        return jsonData.isAvailable;
      })
      .catch(function (error) {
        console.error('Error checking room availability:', error.message); // Log the specific error message
        throw error; // Re-throw the error to propagate it to the next catch block
      });
  }
});
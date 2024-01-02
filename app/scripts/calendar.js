
let isArrivalDateLocked = false;
var eventsArray = [];
let bookedDates = {
  'The Gaze': [],
  'The Tranquility': [],
  'The Presidential': []
};

// fetch data from the database about the booked dates and populate the array, we use the array to populate the calendar
async function fetchDataAndPopulateArray(eventsArray) {
  const response = await fetch('/app/database/database-communications.php?calendar=true');
  const data = await response.json();
  eventsArray.push(...data);
}


//toogle the lock arrival date button, this is the main trigger for the calendar
function toggleLockArrivalDate() {
  isArrivalDateLocked = !isArrivalDateLocked;

  var lockArrivalDateButton = document.getElementById('lockArrivalDate');
  lockArrivalDateButton.innerText = isArrivalDateLocked ? 'Unlock Arrival Date' : 'Lock Arrival Date';

  if (isArrivalDateLocked) {
    // Disable the "Lock Arrival Date" button once the arrival date is confirmed
    document.getElementById('lockArrivalDate').disabled = true;
  }
}

// Clear the form fields and reset the "Lock Arrival Date" button If user wanna change arrivaldate
function clearBookingForm() {
  document.getElementById('bookingForm').reset();
  isArrivalDateLocked = false;
  document.getElementById('lockArrivalDate').disabled = false;
  document.getElementById('lockArrivalDate').innerText = 'Lock Arrival Date';
}

//populate and render the calendar with data from DB
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
    validRange: {
      start: '2024-01-01',
      end: '2024-02-01'
    } //this is the range of the calendar, required by Hans to be from jan 1 to jan 31
  });
  fetchDataAndPopulateArray(eventsArray)
  .then(function () {
    eventsArray.forEach(eventData => {
    calendar.addEvent(eventData);
  });
  });
  //initialise the calendar
  calendar.render();
});




// to be able to show the booking to the user in the calender tha page needs to reload, so when user press the btn "show booking" the page reloads
document.getElementById('reloadButton').addEventListener('click', function() {
  // Add a delay of 2000 milliseconds (2 seconds) before reloading the page, to give time for the booking to be processed
  setTimeout(function() {
      location.reload();
  }, 2000);
});
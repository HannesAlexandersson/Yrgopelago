let isArrivalDateLocked = false;
let roomPrice = 0;
let roomPricePerNight = 0;
let featuresPrice = 0;
let discount = 0;
let totalPrice = 0;
var eventsArray = [];
let bookedDates = {
  'The Gaze': [],
  'The Tranquility': [],
  'The Presidential': []
};

// fetch data from the database about the booked dates and populate the array, we use the array to populate the calendar
async function fetchDataAndPopulateArray(eventsArray) {
  /* const response = await fetch('/scripts/handle-booking.php?calendar=true'); */
  const response = await fetch('/scripts/database-communications.php?calendar=true');
  const data = await response.json();
  eventsArray.push(...data);
}


//this function doesnt work, the arrivaldate button isnot toggleable NEEDS ATTENTION!!!
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
    // uncomment this code (validRange) to disable dates before d day and put it here

  });
  fetchDataAndPopulateArray(eventsArray)
  .then(function () {
    eventsArray.forEach(eventData => {
    calendar.addEvent(eventData);
  });
  //initialise the calendar
  calendar.render();
})
/* validRange: {
      start: '2024-01-01',
      end: '2024-02-01'
    } */

// a listener for the booking form to be able to submit bookings to the php script
var bookingForm = document.getElementById('bookingForm');
bookingForm.addEventListener('submit', function (event) {
event.preventDefault();
//extract the form data
var user_id = document.getElementById('transfercode').value;
var room_id = document.getElementById('room').value;
var room;// I need a variable to hold the actual room name for the calendar and for visual purposes
if(room_id == 1){
  var room = 'The Gaze';
  roomPricePerNight = 5;
}else if(room_id == 2){
  var room = 'The Tranquility';
  roomPricePerNight = 10;
}else if(room_id == 3){
  var room = 'The Presidential';
  roomPricePerNight = 25;
}
var arrivalDate = document.getElementById('arrivalDate').value;
var departureDate = document.getElementById('departureDate').value;

// Extract selected features from the form checkboxes
var features = [];
var featureCheckboxes = document.querySelectorAll('.feature-checkbox:checked');
featureCheckboxes.forEach(function (checkbox) {
  features.push(checkbox.value);
  featuresPrice + 5;
});


// Validate and process the form data
if (room_id && arrivalDate && departureDate) {

  // Check room availability with a server request
  checkRoomAvailability(user_id, room_id, arrivalDate, departureDate, features)
    .then(function (isAvailable) {
      if (isAvailable) {
        // Room is available, proceed with adding the booking to the calendar
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
          alert('This room is already booked for the selected dates. Please choose different dates.');
          exit();
        }
      })
      .catch(function (error) {
        console.error('Error checking room availability:', error);
        alert('An error occurred while checking room availability. Please try again.');
        exit();
      });
  } else {
    alert('Please fill in all fields.');
    exit();
  }
});

  // Function to check room availability with a server request I:E send the data to the php file
  function checkRoomAvailability(user_id, room, arrivalDate, departureDate, features) {
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
        features: features,
      }),
    })

      .then(function (response) {
        if (!response.ok) {//error handling
          throw new Error('Network response was not ok');
        }

       return response.json();
      })

      .then(function (jsonData) {
        return jsonData.isAvailable;
      })

      .catch(function (error) {
        console.error('Error checking room availability:', error.message); // Log the specific error message
        throw error; // Re-throw the error to the next catch block

      });
  }
});


  function scrollToBookingForm() {
    // Get the element reference for the booking form
    var bookingForm = document.querySelector('.booking-header');

    // Scroll to the booking form
    bookingForm.scrollIntoView({ behavior: 'smooth' });
}



// -------------------cost calculation---------------------//
// Function to calculate the cost based on room type, duration, and discount
function calculateCost(room_id, selectedFeatureIDs, numberOfDays) {
  var roomPricePerNight;
  // Set room price per night based on room_id
  if (room_id == 1) {
    roomPricePerNight = 5;
  } else if (room_id == 2) {
    roomPricePerNight = 10;
  } else if (room_id == 3) {
    roomPricePerNight = 25;
  } else {
    console.error('ERROR - Room not found');
    return 0; // Return 0 if room not found
  }

  // Calculate total room cost without discount
  var totalRoomCost = roomPricePerNight * numberOfDays;

  // Calculate discount
  var discountPercentage = 0.1; // 10% discount per day
  var maxDiscountPercentage = 0.5; // Maximum 50% discount
  var discountDaysThreshold = 3; // Apply discount for bookings longer than 3 days
  var discount = 0;

  if (numberOfDays > discountDaysThreshold) {
    var eligibleDays = numberOfDays - discountDaysThreshold;
    discount = Math.min(eligibleDays * discountPercentage, maxDiscountPercentage);
  }

  // Calculate discounted room cost by applying discount
  var discountedRoomCost = totalRoomCost - (discount * totalRoomCost);

  // Calculate feature cost
  var featuresCost = calculateCostOfFeatures(selectedFeatureIDs);

  // Calculate total cost by adding discounted room cost and feature cost
  var totalCost = discountedRoomCost + featuresCost;

  return totalCost;
}

// Function to calculate the cost of selected features
function calculateCostOfFeatures(selectedFeatureIDs) {
  var featureCost = 0;
  selectedFeatureIDs.forEach(function (feature) {
    featureCost += 5; // Each feature costs $5
  });
  return featureCost;
}


// Function to update the total cost based on form inputs
function updateTotalCost() {
  var room_id = document.getElementById('room').value;
  var selectedFeatureIDs = Array.from(document.querySelectorAll('.feature-checkbox:checked')).map(function (checkbox) {
    return parseInt(checkbox.value);
  });
  var arrivalDate = document.getElementById('arrivalDate').value;
  var departureDate = document.getElementById('departureDate').value;
  var numberOfDays = calculateDays(arrivalDate, departureDate);

  // Calculate total cost
  var totalCost = calculateCost(room_id, selectedFeatureIDs, numberOfDays);

  // Display the total cost to the user
  document.getElementById('totalCost').innerText = 'Total Cost: $' + totalCost.toFixed(2);
}

function toggleLockDepartureDate() {
  var departureDate = document.getElementById('departureDate').value;

  // Update the total cost when the departure date is selected
  if (departureDate) {
    updateTotalCost();
  }
}

// Attach event listener to the departure date lock button, this is the main trigger for the cost calculation
document.getElementById('lockDepartureDate').addEventListener('click', toggleLockDepartureDate);


// Attach event listeners to form inputs, will get the userinput and use it for calculations
document.getElementById('room').addEventListener('change', updateTotalCost);
document.getElementById('arrivalDate').addEventListener('change', updateTotalCost);
document.getElementById('departureDate').addEventListener('change', updateTotalCost);
document.querySelectorAll('.feature-checkbox').forEach(function (checkbox) {
checkbox.addEventListener('change', updateTotalCost);
});

// Function to calculate the number of days between arrival and departure dates
function calculateDays(arrivalDate, departureDate) {
  var arrivalDateObj = new Date(arrivalDate);
  var departureDateObj = new Date(departureDate);

  // validating date format, Since I have had so much trouble with getting JS date format to acutally see 1 day as 1 full day
  if (isNaN(arrivalDateObj) || isNaN(departureDateObj)) {
    console.error('Invalid date format');
    return 0;
  }

  // Set hours, minutes, seconds, and milliseconds to zero for accurate day calculation, Same reasoning here as above
  arrivalDateObj.setUTCHours(0, 0, 0, 0);
  departureDateObj.setUTCHours(0, 0, 0, 0);

  var timeDiff = departureDateObj - arrivalDateObj;
  var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // and in the end I need to add 1 day to the total to get the correct amount of days anyway
  return daysDiff;
}

// -------------------cost calculation---------------------//

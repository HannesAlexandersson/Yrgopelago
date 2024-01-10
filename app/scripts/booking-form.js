
// a listener for the booking form to be able to submit bookings to the php script
document.getElementById('bookingForm').addEventListener('submit', async function (event) {
  event.preventDefault();
//extract the form data from the html form
var user_id = document.getElementById('transfercode').value;
var room_id = document.getElementById('room').value;
var room;// I need a variable to hold the actual room name for the calendar and for visual purposes
if(room_id == 1){
  var room = 'The Gaze';

}else if(room_id == 2){
  var room = 'The Tranquility';

}else if(room_id == 3){
  room = 'The Presidential';

}
//extract the user-choosen dates from the form
var arrivalDate = document.getElementById('arrivalDate').value;
var departureDate = document.getElementById('departureDate').value;

// Extract selected features from the form checkboxes
var features = [];
var featureCheckboxes = document.querySelectorAll('.feature-checkbox:checked');
featureCheckboxes.forEach(function (checkbox) {
  features.push(checkbox.value);

});


// Validate and process the form data
if (user_id && room_id && arrivalDate && departureDate) {
  try {
    // Check room availability
    const isAvailable = await checkRoomAvailability(room_id, arrivalDate, departureDate);
    if (isAvailable) {
      // Room is available, proceed with booking (send the data to the php file that handles the booking and inserts it into the database)
      const bookingResponse = await bookRoom(user_id, room_id, arrivalDate, departureDate, features);
      if (bookingResponse['additional_info']['booking-result']) {
        console.log('Booking successful!');
        console.log(bookingResponse);

      alert(bookingResponse['additional_info']['greeting']);// Display the booking result to the user
      }else if(booking-result['error']){ // when the php script returns error, could be error with room avability or with the payments logic (wrong amount on transfercode etc)
        alert(booking-result['error']);// display the error as alert for the user
        clearBookingForm();
      } else {
        alert('Booking failed. Please try again.');
        clearBookingForm();
      }
    } else { // when the choosen dates is already booked
      alert('This room is already booked for the selected dates. Please choose different dates.');
      clearBookingForm();
    }
  } catch (error) {// after testing I found out that this error is thrown when the user has not enough money on his account or if the amounts didnt match, so I added an alert to tell the user to make sure he has enough money on his account
    console.log('error in validation of transfercode, pls make sure you have enought money on your account');
    alert('An error occurred while processing the booking. Please try again.');
    clearBookingForm();
  }
} else {
  alert('Please fill in all fields.');
}
});

// Function to check room availability with a server request I:E send the data to the php file wich checks the db for the room id and dates that already booked. returns true if the room is available
async function checkRoomAvailability(room_id, arrivalDate, departureDate) {
  try {
    const response = await fetch('app/scripts/checkRoomAvailability.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        room: room_id,
        arrivalDate: arrivalDate,
        departureDate: departureDate,
      }),
    });

    if (!response.ok) {
      throw new Error('Network response was not ok');
    }
    const jsonData = await response.json();
    return jsonData.isAvailable;
  } catch (error) {
    console.error('Error checking room availability:', error.message);
    throw error;
  }
}

//function to book the room, send the data to the php file that handles the booking and inserts it into the database
async function bookRoom(user_id, room_id, arrivalDate, departureDate, features) {
  try {
    const response = await fetch('app/scripts/handle-booking.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        user_id: user_id,
        room_id: room_id,
        arrivalDate: arrivalDate,
        departureDate: departureDate,
        features: features,
      }),
    });

    if (!response.ok) {
      throw new Error('Booking failed. Server response was not ok');
    }

    const jsonData = await response.json(); // Use await to get the JSON data

    return jsonData;
  } catch (error) {
    console.error('Error processing booking:', error.message);
    throw error;
  }
}






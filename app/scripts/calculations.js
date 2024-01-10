
// -------------------cost calculations---------------------//
// Function to calculate the cost based on room type, duration, and discount
function calculateCost(room_id, selectedFeatureIDs, numberOfDays) {
  var roomPricePerNight;
  // Set room price per night based on room_id
  if (room_id == 1) {
    roomPricePerNight = parseFloat(document.getElementById('gaze').innerText); // we get the value from the html element, wich in its turn gets the value from the DB. So if the hotelmanager changes the price from the admin page the calculations will still be correct
  } else if (room_id == 2) {
    roomPricePerNight = parseFloat(document.getElementById('tranq').innerText);
  } else if (room_id == 3) {
    roomPricePerNight = parseFloat(document.getElementById('president').innerText);
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
  var discount = 0; //discount starts at zero
  // only apply discount to bookings LONGER than the treshold
  if (numberOfDays > discountDaysThreshold) {
    var eligibleDays = numberOfDays - discountDaysThreshold; // gets the number of days thats eligible for discounts
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
    if (feature == 1) featureCost += parseInt(document.getElementById('massage').innerText);
    if (feature == 2) featureCost += parseInt(document.getElementById('storyteller').innerText);
    if (feature == 3) featureCost += parseInt(document.getElementById('hotsprings').innerText);
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
  departureDateObj.setUTCHours(24, 0, 0, 0);

  var timeDiff = departureDateObj - arrivalDateObj;
  var daysDiff = Math.ceil(timeDiff / (1000 * 3600 * 24));
  return daysDiff;
}

// -------------------cost calculation ends---------------------//
<div class="rooms-bg">
  <div class="left-rooms"></div>
  <div class="center-rooms">
    <div class="rooms-text-container">
      <div class="rooms-header default-font">
        <h1>Our rooms</h1>
      </div>
      <div class="rooms-header-subtext default-font">
        <p>
          Here on hotel Avalon we have 3 amazing rooms,each offers an extravaganza in luxury and comfort.
          One could of course argue that one is more beautiful than the other, but we leave that up to you to
          decied since each one offers its own unique flavor.
        </p>
        <p>
          <strong>So check out our rooms and see which one suits you best.</strong>
        </p>
      </div>
    </div>
    <div class="room-container">
    <?php require __DIR__ . '/../database/database-communications.php'; ?>
    <?php $rooms = connectToRooms('avalon.db');?>
      <div class="room" id="room1">
        <div class="room-inner-header secondary-font">
          <h2><?= $rooms[0]['name']?></h2>
          <p>The room with unbeatable view</p>
          <p class="hidden expandable">
            Specs: Amazing view in 360 degrees wich includes the ocean aswell as historical monuments as
            Fort Benning and the statue of freedom.
            The room also includes a private balcony and a private bathroom. The bathroom is equipped
            with a bathtub and a shower. The room is located on the 5th floor wich contributes to the
            amazing view. The room is also equipped with a 55" TV and a minibar.
          </p>
        </div>
        <div class="room-inner-img-wrapper secondary-font">
          <p>Price: From <span id="gaze"><?= $rooms[0]['price']?></span></p>
          <img class="room-inner-img" src="/assets/images/rooms/one-star/markus-spiske-g5ZIXjzRGds-unsplash.png" alt="room1">
        </div>
      </div>
      <div class="room" id="room2">
        <div class="room-inner-header secondary-font">
          <h2><?= $rooms[1]['name']?></h2>
          <p>The room for the veary</p>
          <p class="hidden expandable">
            Specs: The room is located on the 3rd floor and is the most quiet room in the hotel.
            The room includes a private bathroom with a shower.
            Its soundproof walls and windows ensures that you will get a good nights sleep while it
            keeps the rich nightlife echoes out from your room.
            or perhaps your goal is to keep your own noice in the room, then this is the room for you.
          </p>
        </div>
        <div class="room-inner-img-wrapper secondary-font">
          <p>Price: From <span id="tranq"><?= $rooms[1]['price']?></span></p>
          <img class="room-inner-img" src="/assets/images/rooms/two-star/dad-hotel-P6B7y6Gnyzw-unsplash.png" alt="room1">
        </div>
      </div>
      <div class="room" id="room3">
        <div class="room-inner-header secondary-font">
          <h2><?= $rooms[2]['name']?></h2>
          <p>Only the best</p>
          <p class="hidden expandable">
            Specs: our presidential suite offers an fully stocked on suit bar, aswell as an kitchen with
            the top of the line equipment. A spacius living room with a 65" TV and a private balcony.
            Plenty of space with a master bedroom complete with a king size bed and a walk in closet.
            The room also includes a private bathroom with a bathtub and a shower.
          </p>
        </div>
        <div class="room-inner-img-wrapper secondary-font">
          <p>Price: From <span id="president"><?= $rooms[2]['price']?></span></p>
          <img class="room-inner-img" src="/assets/images/rooms/three-star/upgraded-points-c8UktkMDrbc-unsplash.png" alt="room1">
        </div>
      </div>
    </div>
  </div>
  <div class="right-rooms"></div>
</div>
<script src="app/scripts/rooms-paralax.js"></script>
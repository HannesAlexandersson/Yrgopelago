<div class="features-bg">
  <div class="features-header">
    <h1>Features</h1>
    <h4>Choose from our sortiment of fantastic features</h4>
  </div>
  <div class="features-wrapper">
    <?php require '/database/dbLoadFeatures.php'; ?>
    <?php foreach($features as $feature){ ?>
    <div class="feature-text-header">
      <h4>
        <?= $feature['name']?>
      </h4>
    </div>
    <div class="feature-price">
      <?= $feature['price']?>
      <button class="feature-btn" id="feature-btn">Add</button>
    <?php } ?>
  </div>
  <div class="feature-desc-text">
    <p>
      Here on Avalon we offer a wide range of features to make your stay as comfortable as possible.
      For example we can offer you a personal bedtime-storyteller that makes your transition to sleep as smooth as possible.
      Or perhaps your muscle is sore from all the swimming in our pool? No worries, we have a masseuse that can help you with that.
      And the underground hotsprings are an world famous attraction that you can't miss out on. The water is
      heated by the earths core and is said to have healing properties that works wonders on whatever it is that is ailing.
    </p>
  </div>
</div>
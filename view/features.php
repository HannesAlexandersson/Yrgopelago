<div class="features-bg">
  <div class="feat-text-wrapper default-font">
    <div class="features-header">
      <h1>Features</h1>
      <h4>Choose from our sortiment of fantastic features</h4>
    </div>
    <div class="feature-desc-text ">
      <p>
        Here on Avalon we offer a wide range of features to make your stay as comfortable as possible.
        For example we can offer you a personal bedtime-storyteller that makes your transition to sleep as smooth as possible.
        Or perhaps your muscle is sore from all the swimming in our pool? No worries, we have a masseuse that can help you with that.
        And the underground hotsprings are an world famous attraction that you can't miss out on. The water is
        heated by the earths core and is said to have healing properties that works wonders on whatever it is that is ailing.
      </p>
    </div>
  </div>
  <div class="features-wrapper">
    <?php require __DIR__ . '/../database/dbLoadFeatures.php'; ?>
    <?php
    $features = connectToFeatures('../database/avalon.db');

    foreach($features as $feature){ ?>
    <div class="feat-item">
      <div class="feature-text-header secondary-font">
        <h4>
          <?= $feature['name']?>
        </h4>
      </div>
      <div class="feature-price secondary-font">

        <?php if($feature['name'] == 'underground hotsprings'){ ?>

          <p>Price: <span id="hotsprings"><?=$feature['price']?></span></p>

        <?php } else if($feature['name'] == 'massage therapy'){ ?>

        <p>Price: <span id="massage"><?=$feature['price']?></span></p>

        <?php } else if($feature['name'] == 'bedtime storyteller'){ ?>

          <p>Price: <span id="storyteller"><?=$feature['price']?></span></p>
        <?php } ?>
        
      </div>
      <div class="feature-img-container">

        <?php if($feature['name'] == 'underground hotsprings'){ ?>

        <img class="feature-img" src="/assets/images/features/UG-HS.png" alt="Hotsprings">

        <?php } else if($feature['name'] == 'massage therapy'){ ?>

        <img class="feature-img" src="/assets/images/features/Massage-rygg.png" alt="Massage">

        <?php } else if($feature['name'] == 'bedtime storyteller'){ ?>

        <img class="feature-img" src="/assets/images/features/story.png" alt="storyteller bedtime">

        <?php } ?>

      </div>
    </div>
    <?php }; ?>
  </div>
</div>
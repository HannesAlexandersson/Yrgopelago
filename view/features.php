<div class="features-bg">
  <div class="feat-text-wrapper secondary-font">
    <div class="features-header default-font">
      <h1>Features</h1>
      <h4>Choose from our sortiment of fantastic features</h4>
    </div>
    <div class="feature-desc-text secondary-font">
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
    $features = connect('../database/avalon.db');

    foreach($features as $feature){ ?>
    <div class="feat-item">
      <div class="feature-text-header secondary-font">
        <h4>
          <?= $feature['name']?>
        </h4>
      </div>
      <div class="feature-img-container">

        <?php if($feature['name'] == 'underground hotsprings'){ ?>

        <img class="feature-img" src="/assets/images/features/UG-HS.jpg" alt="Hotsprings">

        <?php } else if($feature['name'] == 'massage therapy'){ ?>

        <img class="feature-img" src="/assets/images/features/Massage-rygg.jpg" alt="Massage">

        <?php } else if($feature['name'] == 'bedtime storyteller'){ ?>

        <img class="feature-img" src="/assets/images/features/story.jpg" alt="storyteller bedtime">

        <?php } ?>

      </div>

      <div class="feature-price secondary-font">
        <?='Price: ' .$feature['price']?>
      </div>
    </div><?php }; ?>

  </div>
</div>
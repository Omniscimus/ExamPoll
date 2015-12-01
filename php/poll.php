<?php
require_once 'Vote_Validator.php';

$validator = new Vote_Validator();
$mode = $validator->get_response_mode();

if ($mode === 3) {
    $mySQL_manager = $validator->get_mySQL_manager()->getExamPoll_SQL()->
            add_vote($_POST["code"], $_POST["vote"]);
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Thema laatste schooldag</title>
    <?php if ($mode === 0): ?>
      <script src='https://www.google.com/recaptcha/api.js'></script>
    <?php endif; ?>
    <link type="text/css" rel="stylesheet" href="/foundation/css/foundation.css">
    <link type="text/css" rel="stylesheet" href="/foundation/css/exampoll.css">
  </head>
  <body>
    <?php if ($mode === 0): ?>
      <!-- Vote page -->

      <!-- Header, title -->
      <div class="row">
        <div class="medium-10 medium-centered columns">
          <h3>Stem op een thema</h3>
        </div>
      </div>


      <div class="row">
        <div class="medium-10 medium-centered columns">
          <!-- Explanation -->
          <div class="row">
            <div class="small-12 columns">
              <p>
              <h5>Werking</h5>
              Voor het eind examen van het schooljaar 2015-2016 kunt u uw keuze
              aangeven voor een mogelijke eindexamenstunt. Hieronder ziet u de
              keuzes. U kunt er maximaal 1 aanvinken.
              </p>
            </div>
          </div>
          <form method="post">
          <!-- The options -->
          <?php
            $options = include "options.php";
            for ($i = 0; $i < count($options); $i++):
              ?>
            <div class="row">
              <div class="small-12 columns votingItemHeader">
                <input required type="radio" name="vote" value="<?php echo
                $i;
                ?>">
                <label><h4><?php echo $options[$i]['name']; ?></h4></label>
              </div>
              <div class="medium-12 columns votingItemDescription"
                   style="padding-left: 2em;">
                <p><?php echo $options[$i]['description']; ?></p>
              </div>
            </div>
          <?php endfor; ?>
           <div class="row">
             <div class="small-12 columns"
                  style="margin-top: 1em">
               <input type="hidden" name="code" value="<?php echo $_GET["code"]; ?>" />
               <?php $config = include 'config.php'; ?>
               <label>
                 <h5>Captcha Code</h5>
                 <div class="g-recaptcha" data-sitekey="<?php echo $config["site-key"] ?>"></div>
               </label>
               <p style="margin-top: 1em; margin-bottom: 0.5em;">
                 Als u zeker weet dat u de juiste keuze heeft gemaakt en
                 als u de captcha code correct hebt ingevoerd kunt u op de
                 stem knop klikken.
               </p>
               <input type="submit" value="Stem" class="button"
                      style="padding: 1em 4em; margin-top: 0.2em;">

             </div>
           </div>
        </form>
        </div>
      </div>
    <?php elseif ($mode === 1): ?>
      <div>
        <h1>Stemcode ongeldig</h1>
        <p>Je stemcode is ongeldig. Dit kan betekenen dat je al gestemd
            hebt, of dat je een verkeerde code hebt ingevuld.</p>
      </div>
    <?php elseif ($mode === 2): ?>
      <!-- Enter code page -->

      <div class="loginBackground">
        <div class="row">
          <div class="medium-7 large-5 medium-centered columns">
            <div class="login">

              <form method="get" action="poll.php">
                <h3>Stemcode invullen</h3>

                <label style="color:white;">
                  Stemcode:
                  <input type="text" name="code" />
                </label>

                <input type="submit" value="Zoek" class="button"/>
              </form>

            </div>
          </div>
        </div>
      </div>
    <?php elseif ($mode === 3): ?>
      <div>
        <h1>Stem ontvangen</h1>
        <p>Bedankt voor je stem!</p>
      </div>
    <?php elseif ($mode === 4): ?>
      <div>
        <h1>Wow.</h1>
        <p>Volgens mij bent u een 1337 h4xx0r. Probeer het nog eens.</p>
        <img src="http://stream1.gifsoup.com/view3/1752198/never-gonna-give-you-up-o.gif"
             alt="WOW" />
      </div>
    <?php endif; ?>
  </body>
</html>

<?php
$mySQL_manager = $validator->get_mySQL_manager();
if (isset($mySQL_manager)) {
    $mySQL_manager->closeConnection();
}

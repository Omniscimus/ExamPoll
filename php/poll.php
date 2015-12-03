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
    <link type="text/css" rel="stylesheet" href="foundation/css/foundation.css">
    <link type="text/css" rel="stylesheet" href="foundation/css/exampoll.css">
  </head>
  <body>
    <?php if ($mode === 0): ?>
      <!-- Vote page -->
      
      <!-- Header, title -->
      <div class="row" style="margin-top: 1em;">
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
              Als je eindexamenkandidaat bent, kun je hier je voorkeur opgeven
              voor het thema van de examenstunt van het schooljaar 2015-2016. Je
              kunt maximaal 1 keuze aanvinken.<br /><br />
              Groetjes ~De eindexamencommissie.
              </p>
            </div>
          </div>
          <form method="post">
          <!-- The options -->
            <?php
              $options = include "options.php";
              for ($i = 0; $i < count($options); $i++):
                ?>
              <div class="medium-6 medium-offset-0 columns">
                <div class="columns votingItemHeader">
                  <input required type="radio" name="vote" value="<?php echo
                  $i;
                  ?>">
                  <label><h5><?php echo $options[$i]['name']; ?></h5></label>
                </div>
                <div class="columns votingItemDescription"
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
                  <h5>Captcha</h5>
                  <div class="g-recaptcha" data-sitekey="<?php echo $config["site-key"] ?>"></div>
                </label>
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
        <p>Gebruik de link uit het bericht dat je als eindexamenkandidaat hebt
            ontvangen via <a href="https://nehalennia.itslearning.com/index.aspx">Itslearning</a>, 
            of <a href="poll.php">vul je code correct in</a>.</p>
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

                <input type="submit" value="Verder" class="button"/>
                <p>Als je eindexamenkandidaat bent, heb je een stemcode
                    ontvangen via <a href="https://nehalennia.itslearning.com/index.aspx" target="_blank">Itslearning</a>.</p>
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
      <div style="text-align: center;">
        <h1>Wow.</h1>
        <p>Volgens mij bent u een 1337 h4xx0r.<br />
            <a href="poll.php">Probeer het nog eens</a>.</p>
        <img src="http://stream1.gifsoup.com/view3/1752198/never-gonna-give-you-up-o.gif"
             alt="WOW" />
      </div>
    <?php endif; ?>
    <div style="height: 100px;">
      <!-- Om te voorkomen dat de (fixed) footer de Stem-knop verbergt op kleine schermen -->
    </div>
    <footer>
      <script>
          var charstring = "abcdefghijklmnopqrstuvwxyz1234567890§±!@#$%^&*()_-+=[]\';/?.>,<|:~`";
          var chars = charstring.split('');
          function hack() {
              window.setTimeout(hack,50);
              document.getElementById('hax').innerHTML = chars[Math.floor(Math.random() * (chars.length - 1))];
          }
          while (true) {
            hack();
          }
        </script>
        Gemaakt met <hack style="color: green;font-family: Courier" id="hax"></hack> 
        door <a href="http://www.mateybyrd.net/" target="_blank">Nick</a> en
        <a href="http://www.omniscimus.net/" target="_blank">Reinier</a>. 
        Vind je dit systeem spooky? 
        <a href="https://github.com/Omniscimus/ExamPoll" target="_blank">Bekijk de broncode op GitHub!</a>
    </footer>
  </body>
</html>

<?php
$mySQL_manager = $validator->get_mySQL_manager();
if (isset($mySQL_manager)) {
    $mySQL_manager->closeConnection();
}

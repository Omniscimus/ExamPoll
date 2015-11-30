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
    </head>
    <body>
        <?php if ($mode === 0): ?>
            <div>
                <h1>Stem op een thema:</h1>
                <form method="post"
                      action="poll.php">
                    <input type="radio" name="vote" value="1" />The Fellowship of the Examen<br />
                    <input type="radio" name="vote" value="2" />The Examen Awakens<br />
                    <input type="radio" name="vote" value="3" />Fifty Shades of Examens<br />
                    <input type="radio" name="vote" value="4" />The Dark Examen Rises<br />
                    <input type="radio" name="vote" value="5" />Exception<br />
                    <input type="hidden" name="code" value="<?php echo $_GET["code"]; ?>" />
                    <?php $config = include 'config.php'; ?>
                    <div class="g-recaptcha" data-sitekey="<?php echo $config["site-key"] ?>"></div>
                    <input type="submit" value="Stem" />
                </form>
            </div>
        <?php elseif ($mode === 1): ?>
            <div>
                <h1>Stemcode ongeldig</h1>
                <p>Je stemcode is ongeldig. Dit kan betekenen dat je al gestemd 
                    hebt, of dat je een verkeerde code hebt ingevuld.</p>
            </div>
        <?php elseif ($mode === 2): ?>
            <div>
                <h1>Stemcode invullen</h1>
                <form method="get" action="poll.php">
                    Stemcode: <input type="text" name="code" />
                    <input type="submit" value="Zoek" />
                </form>
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

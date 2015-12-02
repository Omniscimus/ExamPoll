<?php
if (is_string($_POST["password"])) {
  $config = include 'config.php';
  if ($_POST["password"] == $config["results-password"]) {
    $mode = 0; // Correct password
    require_once 'sql/MySQL_Manager.php';
    $mySQL = new MySQL_Manager();
    $results = $mySQL->getExamPoll_SQL()->get_results();
    $mySQL->closeConnection();
    $vote_options = include 'options.php';
  } else {
    $mode = 1; // Wrong password
  }
} else {
  $mode = 2; // No password yet
}
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Poll resultaten</title>
    <link type="text/css" rel="stylesheet" href="foundation/css/foundation.css">
    <link type="text/css" rel="stylesheet" href="foundation/css/exampoll.css">
    <script src="chartjs/Chart.js"></script>
  </head>
    <body>
    <?php if ($mode !== 0): ?>
      <div class="loginBackground">
        <div class="row">
          <div class="medium-7 large-5 medium-centered columns">
            <div class="login">

              <h3>Resultaten</h3>
              <form action="results.php" method="post">
                <?php if ($mode === 1): ?>
                  <img src="http://cdn.meme.li/instances/500x/44412533.jpg"
                       alt="Rekt" /><br />
                <?php endif; ?>
                Wachtwoord: <input type="password" name="password" />
                <input class="button" type="submit" />
              </form>

            </div>
          </div>
        </div>
      </div>

      <?php else: ?>
      <div class="row">
        <div class="medium-10 medium-centered columns">

          <div class="row" style="margin-top: 1em; margin-bottom: 1em;">
            <div class="small-12 columns">
              <h3>Resultaten</h3>
            </div>
          </div>

          <div class="row">
            <!-- A chart that indicates the votes -->
            <div class="small-12 columns">
              <canvas id="votes" height="100" style="margin-bottom: 1em;"></canvas>
              <script>
                var ctx = document.getElementById("votes");
                var myChart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                    labels: [<?php foreach($results as $option) echo "'".($vote_options[$option[0]]["name"]."',"); ?>],
                    datasets: [{
                      label: 'Amount of Votes',
//                      data: [10, 5, 19, 10, 5], // Test Variables to test the chart.
                      data: [<?php foreach($results as $option) echo $option[1].","; ?>],
                      backgroundColor: "rgba(47,102,138,255)",
                      hoverBackgroundColor: "rgba(216,149,65,255)"
                    }]
                  },
                  options: {
                    scales: {
                      xAxes: [{
                        gridLines: {
                          color: "rgba(0,0,0,0)"
                        }
                      }]
                    }
                  }
                });
              </script>
              <hr />
            </div>

            <div class="medium-6 columns">
              <p>
                Hierbij de resultaten, hierboven zie je de resultaten weer gegeven in een grafiek. Hiernaast zie je de resultaten in cijfers.
              </p>
              <p>
                Iedereen krijgt via Itslearning een stemcode toegestuurd. Deze kunnen ze invullen of ze kunnen klikken op de link in het bericht,
                om te stemmen. Deze codes staan ook in de database (NIET gekoppeld aan de naam van de leerlingen). Wanneer een leerling stemt, 
                wordt zijn/haar code verwijderd uit de database en wordt de stem toegevoegd. Op deze manier kan niemand dubbel stemmen.<br />
                Er zijn zoveel mogelijke codes, dat de kans ongeveer 0 is dat je er zomaar een raadt.
              </p>
            </div>

            <div class="medium-5 medium-offset-1 columns">
              <h5>Stemming in cijfers</h5>
              <table>
                <tr>
                  <th>Optie</th>
                  <th>Stemmen</th>
                </tr>
                <?php
                foreach ($results as $option) {
                  echo "<tr>";
                  for ($i = 0; $i < count($option); $i++) {
                    echo "<td>";
                    if ($i === 0) {
                      echo $vote_options[$option[0]]["name"];
                    } else {
                      echo $option[$i];
                    }
                    echo "</td>";
                  }
                  echo "</tr>";
                }
                ?>
              </table>
            </div>


          </div>

        </div>
      </div>


        <?php endif; ?>
    </body>
</html>

<?php
if (is_string($_POST["password"])) {
    $config = include 'config.php';
    if ($_POST["password"] == $config["results-password"]) {
        $mode = 0; // Correct password
        require_once 'sql/MySQL_Manager.php';
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
    </head>
    <body>
        <h1>Resultaten</h1>
        <?php if ($mode !== 0): ?>
            <form action="results.php" method="post">
                <?php if ($mode === 1): ?>
                    <img src="http://cdn.meme.li/instances/500x/44412533.jpg"
                         alt="Rekt" /><br />
                <?php endif; ?>
                Wachtwoord: <input type="password" name="password" />
                <input type="submit" />
            </form>
        <?php else: ?>
            <table>
                <tr>
                    <th>Optie</th>
                    <th>Stemmen</th>
                </tr>
                <?php
                $mySQL = new MySQL_Manager();
                $results = $mySQL->getExamPoll_SQL()->get_results();
                $mySQL->closeConnection();

                $vote_options = include 'options.php';
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
        <?php endif; ?>
    </body>
</html>

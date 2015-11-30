<!DOCTYPE html>
<html>
    <head>
        <title>Poll resultaten</title>
    </head>
    <body>
        <h1>Resultaten</h1>
        <?php if ($mode === 0): ?>
            <form action="results.php" method="post">
                Wachtwoord: <input type="password" name="password" />
                <input type="submit" />
            </form>
        <?php elseif ($mode === 1): ?>
            <table>
                <?php
                $mySQL = new MySQL_Manager();
                $results = $mySQL->getExamPoll_SQL()->get_results();
                $mySQL->closeConnection();
                foreach ($results as $option) {
                    echo "<tr>";
                    foreach ($option as $column) {
                        echo "<td>$column</td>";
                    }
                    echo "</tr>";
                }
                ?>
            </table>
        <?php endif; ?>
    </body>
</html>

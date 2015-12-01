<?php

/**
 * Verzorgt de queries naar de database van deze app.
 *
 * @author omniscimus
 */
class ExamPoll_SQL {

    private $mySQL_manager;

    /**
     * Maakt een nieuwe ExamPoll_SQL.
     * 
     * @param MySQL_Manager $mySQL_manager de manager van deze instance
     */
    function __construct($mySQL_manager) {
        $this->mySQL_manager = $mySQL_manager;
        $this->setup_tables();
    }

    /**
     * Maakt de tabel met keuzes als hij nog niet bestaat, en voegt rijen toe
     * voor (eventuele) nieuwe opties.
     */
    function setup_tables() {
        $vote_options = include 'options.php';
        
        $queries = "CREATE TABLE IF NOT EXISTS votes (options TINYINT UNSIGNED UNIQUE, counter INT UNSIGNED);";
        for($i = 0; $i < count($vote_options); $i++) {
            $queries = $queries . "INSERT IGNORE INTO votes (options, counter) VALUES ($i, 0);";
        }
        $this->mySQL_manager->getConnection()->multi_query($queries);
        // Gebruik niet 'option' als column name omdat dat een MySQL keyword is
        // -> syntax error.
    }

    /**
     * Voegt een stem toe aan de database.
     * 
     * @param string $code de stemcode van de persoon die gestemd heeft
     * @param int $vote het nummer van het item waarop gestemd is
     */
    function add_vote($code, $vote) {
        $delete_statement = $this->mySQL_manager->getConnection()->
                prepare("DELETE FROM codes WHERE code = ?;");
        $delete_statement->bind_param("s", $code);
        $success = $delete_statement->execute();
        $delete_statement->close();

        if ($success) {
            $increment_statement = $this->mySQL_manager->getConnection()->
                    prepare("UPDATE votes SET counter=counter+1 WHERE options=?;");
            $increment_statement->bind_param("i", $vote);
            $increment_statement->execute();
            $increment_statement->close();
        }
    }

    /**
     * Geeft of het gegeven IP-adres al gestemd heeft.
     * 
     * @param string $code de te controleren stemcode
     * @return boolean true als het gegeven IP-adres nog niet gestemd heeft;
     * anders false
     */
    function is_valid($code) {
        if (!isset($code)) {
            return false;
        }
        $statement = $this->mySQL_manager->getConnection()->
                prepare("SELECT COUNT(*) FROM codes WHERE code = ?;");
        $statement->bind_param("s", $code);
        $statement->execute();

        $statement->bind_result($matches);
        $statement->fetch();
        $statement->close();
        return ($matches > 0);
    }

    /**
     * Geeft de resultaten van de poll tot nu toe.
     * 
     * @return array een lijst met arrays waarin de optie en het aantal votes
     * staan
     */
    function get_results() {
        $results = $this->mySQL_manager->getConnection()->
                query("SELECT * FROM votes;");
        $rows = $results->fetch_all();
        $results->free();
        return $rows;
    }

}

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
    }

    /**
     * Voegt een stem toe aan de database.
     * 
     * @param int $vote het nummer van het item waarop gestemd is
     */
    function add_vote($code, $vote) {
        // INSERT INTO votes (ip, vote) VALUES ($ip, $vote), prepared statement
    }

    /**
     * Geeft of het gegeven IP-adres al gestemd heeft.
     * 
     * @param string $ip het te controleren IP-adres
     * @return boolean true als het gegeven IP-adres al gestemd heeft; anders
     * false
     */
    function has_voted($code) {
        $result = mysql_query("SELECT COUNT(*) FROM votes WHERE ip = '" . $code . "';");
        // get result
        //return (result > 0);
    }

    /**
     * Geeft de resultaten van de poll tot nu toe.
     * 
     * @return array een lijst met de resultaten van de poll
     */
    function get_results() {
        // SELECT COUNT(*) FROM (SELECT * FROM votes WHERE vote = 1) AS 1, COUNT(*) FROM (SELECT * FROM votes WHERE vote = 2) AS 2, etc.
    }

}

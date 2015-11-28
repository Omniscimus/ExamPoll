<?php

/**
 * Verzorgt de communicatie met de MySQL-server.
 *
 * @author omniscimus
 */
class MySQL_Manager {

    private $connection;
    private $examPoll_SQL;

    /**
     * Maakt een nieuwe MySQL_Manager.
     */
    function __construct() {
        $this->examPoll_SQL = new ExamPoll_SQL($this);
    }

    function getExamPoll_SQL() {
        return $this->examPoll_SQL;
    }

    /**
     * Geeft de MySQL-verbinding.
     * 
     * @return mysqli de verbinding die gebruikt kan worden voor queries
     */
    function getConnection() {
        if (!isset($this->connection)) {
            $this->connect();
        }
        return $this->connection;
    }

    /**
     * Sluit de MySQL verbinding (als die bestaat).
     */
    function closeConnection() {
        if (isset($this->connection)) {
            $this->connection->close();
        }
    }

    /**
     * Verbindt met de MySQL server.
     * 
     * @throws Exception als het verbinden is mislukt
     */
    function connect() {
        $config = include 'config.php';
        $connection = new mysqli(
                $config["mysql_hostname"], $config["mysql_username"],
                $config["mysql_password"], NULL, $config["mysql_port"]);
        if ($connection->connect_error) {
            throw new Exception("Verbinden met de database is mislukt.");
        } else {
            $this->connection = $connection;
        }
    }

}

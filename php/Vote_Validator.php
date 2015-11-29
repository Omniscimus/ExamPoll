<?php

require_once 'sql/MySQL_Manager.php';

/**
 * Valideert de stem.
 *
 * @author omniscimus
 */
class Vote_Validator {

    private $mySQL_Manager;

    /**
     * Maakt een nieuwe Vote_Validator.
     */
    function __construct() {
        $this->mySQL_Manager = new MySQL_Manager();
    }

    /**
     * Geeft de MySQL-manager die gebruikt kan worden voor toegang tot de
     * database.
     * 
     * @return MySQL_Manager de te gebruiken MySQL_Manager
     */
    function get_mySQL_manager() {
        return $this->mySQL_Manager;
    }

    /**
     * Geeft de modus waarin de poll pagina weergegeven moet worden.
     * 
     * @return int
     * 0 als het stemformulier moet worden weergegeven
     * 1 als de error moet worden weergegeven dat de code niet klopt, of er al gestemd is met de code
     * 2 als de gebruiker zijn/haar stemcode moet invullen
     * 3 als er een geldige stem is uitgebracht
     * 4 als er een ongeldige stem is uitgebracht, of als de Google reCAPTCHA niet klopt
     */
    function get_response_mode() {
        if ($_SERVER["REQUEST_METHOD"] === "GET") {
            return $this->get_GET_mode();
        } else if ($_SERVER["REQUEST_METHOD"] === "POST") {
            return $this->get_POST_mode();
        } else {
            return 2;
        }
    }

    /**
     * Geeft de modus waarin de pagina weergegeven moet worden als het om een
     * HTTP GET-request gaat
     * 
     * @return int
     * 0 als het stemformulier moet worden weergegeven omdat de gebruiker een stemcode heeft ingevuld
     * 2 als de gebruiker zijn/haar stemcode nog moet invullen
     */
    private function get_GET_mode() {
        // Heeft op link in e-mail geklikt
        $vote_code = $_GET["code"];
        if (!isset($vote_code)) {
            return 2;
        } else {
            return 0;
        }
    }

    /**
     * Geeft de modus waarin de pagina weergegeven moet worden als het om een
     * HTTP POST-request gaat
     *
     * @return int
     * 0 als het stemformulier weergegeven moet worden
     * 1 als de stemcode niet klopt, of er al gestemd is met de code
     * 3 als er een geldige stem is uitgebracht
     * 4 als er een ongeldige stem is uitgebracht, of als de Google reCAPTCHA niet klopt
     */
    private function get_POST_mode() {
        // Heeft een code ingevuld op code-invoerscherm
        // of heeft een vote, een code en een captcha gestuurd
        $vote_code_legit = $this->mySQL_Manager->getExamPoll_SQL()->is_valid($_POST["code"]);
        $vote_legit = $this->vote_is_valid($_POST["vote"]);
        $captcha_legit = $this->captcha_is_valid($_POST["g-recaptcha-response"]);
        if ($vote_code_legit && $vote_legit && $captcha_legit) {
            return 3; // Geldige stem uitgebracht
        } else if (!$vote_legit) {
            return 0; // Ofwel iemand probeert te hacken door op iets niet-bestaands te stemmen,
                      // ofwel iemand heeft zojuist zijn stemcode ingevuld en moet nu stemmen
        } else if (!$captcha_legit) {
            return 4; // Captcha was fout bij stemformulier
        } else if (!$vote_code_legit) {
            return 1; // Stemcode waarmee geprobeerd is te stemmen, is al gebruikt of heeft nooit bestaan
        } else {
            return 4;
        }
    }

    /**
     * Geeft of er een correcte stem is uitgebracht (zodat niet bijv. optie 6
     * gekozen wordt als er maar 5 opties zijn).
     * 
     * @param int $vote het nummer van de uitgebrachte stem
     * @return boolean true als de stem geldig is; anders false
     */
    private function vote_is_valid($vote) {
        if (!is_numeric($vote)) {
            return false;
        }
        $int_vote = (int) $vote;
        return ($int_vote >= 0 && $int_vote <= 5);
    }

    /**
     * Geeft of de ingevulde Google reCAPTCHA geldig is.
     * 
     * @param string $g_recaptcha_response de POST resultaten van de reCAPTCHA
     * @return boolean true als de reCAPTCHA geldig is; anders false
     */
    private function captcha_is_valid($g_recaptcha_response) {
        if (!isset($g_recaptcha_response)) {
            return false;
        }
        $config = include 'config.php';
        $data = array('secret' => $config["secret-key"], 'response' => $g_recaptcha_response);
        $url = "https://www.google.com/recaptcha/api/siteverify";
        $options = array(
            'http' => array(
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data),
            ),
        );
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $decoded_response = json_decode($response, true);
        return $decoded_response["success"];
    }

}

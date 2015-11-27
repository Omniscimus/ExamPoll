<?php

/**
 * Valideert de stem.
 *
 * @author omniscimus
 */
class Vote_Validator {

    /**
     * Geeft of de uitgebrachte stem geldig is.
     * 
     * @return true als de stem geldig is; anders false
     */
    function vote_is_valid($g_recaptcha_response) {
        // Check if it isn't a proxy or Tor
        if (!$this->is_detectable_proxy() && !$this->is_tor_exit_node()) {
            // Check captcha
            if ($this->captcha_is_valid($g_recaptcha_response)) {
                // Check if the IP has already voted
                // Check if they don't have a cookie
            }
        }
    }

    /**
     * Geeft of de ingevulde Google reCAPTCHA geldig is.
     * 
     * @param string $g_recaptcha_response de POST resultaten van de reCAPTCHA
     * @return boolean true als de reCAPTCHA geldig is; anders false
     */
    private function captcha_is_valid($g_recaptcha_response) {
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

    /**
     * Geeft of de gebruiker verbonden is met een proxy.
     * 
     * @return boolean true als het redelijk zeker is dat er een proxy gebruikt
     * wordt; anders false
     */
    private function is_detectable_proxy() {
        return array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER);
    }

    /**
     * Controleert of de gebruiker vanuit het Tor-netwerk komt.
     * Credit: http://www.irongeek.com
     * 
     * @return boolean true als de gebruiker Tor gebruikt; anders false
     */
    private function is_tor_exit_node() {
        return (gethostbyname($this->reverse_IP_octets($_SERVER['REMOTE_ADDR']) . "." . $_SERVER['SERVER_PORT'] . "." . $this->reverse_IP_octets($_SERVER['SERVER_ADDR']) . ".ip-port.exitlist.torproject.org") == "127.0.0.2");
    }

    private function reverse_IP_octets($inputip) {
        $ipoc = explode(".", $inputip);
        return $ipoc[3] . "." . $ipoc[2] . "." . $ipoc[1] . "." . $ipoc[0];
    }

}

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
    function vote_is_valid() {
        if (!is_detectable_proxy() && !is_tor_exit_node()) {
            
        }
        // Check if it isn't a proxy or Tor
        // Check if the IP has already voted
        // Check if they don't have a cookie
    }

    /**
     * Geeft of de gebruiker verbonden is met een proxy.
     * 
     * @return boolean true als het redelijk zeker is dat er een proxy gebruikt
     * wordt; anders false
     */
    function is_detectable_proxy() {
        return array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER);
    }

    /**
     * Controleert of de gebruiker vanuit het Tor-netwerk komt.
     * Credit: http://www.irongeek.com
     * 
     * @return boolean true als de gebruiker Tor gebruikt; anders false
     */
    function is_tor_exit_node() {
        if (gethostbyname(reverse_IP_octets($_SERVER['REMOTE_ADDR']) . "." . $_SERVER['SERVER_PORT'] . "." . reverse_IP_octets($_SERVER['SERVER_ADDR']) . ".ip-port.exitlist.torproject.org") == "127.0.0.2") {
            return true;
        } else {
            return false;
        }
    }

    function reverse_IP_octets($inputip) {
        $ipoc = explode(".", $inputip);
        return $ipoc[3] . "." . $ipoc[2] . "." . $ipoc[1] . "." . $ipoc[0];
    }

}

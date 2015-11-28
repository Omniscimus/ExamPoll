<?php

/**
 * Valideert de stem.
 *
 * @author omniscimus
 */
class Vote_Validator {

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

}

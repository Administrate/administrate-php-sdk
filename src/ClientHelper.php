<?php
namespace Administrate\PhpSdk;

class ClientHelper {
    /**
    * Function to build the API call headers
    * @return $headers, array API Call Header configuration.
    */
    public static function setHeaders($accessToken, $params = array()) {
        $headers = array(
            'Authorization' => 'Bearer ' . $accessToken,
        );

        foreach ($params as $key => $value) {
            if ('portal' === $key) {
                $headers['weblink-portal'] = $value;
            }
        }

        return $headers;
    }

    /**
    * Function to build the API call args
    * @return $args, array API Call args configuration.
    */
    public static function setArgs() {
        $args = array(
            'allow_redirects' => array(
                'max'             => 5,         // allow at most 10 redirects.
                'strict'          => true,      // use "strict" RFC compliant redirects.
                'referer'         => true,      // add a Referer header
                'protocols'       => array('http', 'https'), // only allow https URLs
                'track_redirects' => true
            ),
            'blocking' => true,
        );
        return $args;
    }
}

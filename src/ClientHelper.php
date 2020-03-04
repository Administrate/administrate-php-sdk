<?php
namespace Administrate\PhpSdk;

use GraphQL\Client;

class ClientHelper {
    /**
    * Function to build the API call headers
    * @return $headers, array API Call Header configuration.
    */
    public static function setHeaders($params = array()) {

        if (isset($params['accessToken']) && !empty($params['accessToken'])) {
            $headers = array(
                'Authorization' => 'Bearer ' . $params['accessToken'],
            );
        }

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

    public static function sendSecureCall($class, $query, $variables=[]){
        $authorizationHeaders = self::setHeaders($class::$weblinkParams);
        $httpOptions = self::setArgs();
        $client = new Client($class::$weblinkParams['uri'], $authorizationHeaders);
        $results = $client->runQuery($query, true, $variables);
        return $results->getData();
    }

    public static function sendSecureCallJson($class, $query, $variables=[]){
        return json_encode(
            self::sendSecureCall($class, $query, $variables)
        );
    }
}

<?php

namespace Administrate\PhpSdk\Oauth;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;

if (!class_exists('Activate')) {

    /**
     * This class is responsible for activating the plugin through oAuth
     * with the main Bookwitty Accounts app.
     *
     * @package default
     *
     */
    class Activate
    {
        protected static $instance;
        protected static $params;

        private const SUCCESS_CODE = 200;
        private const STATUS_SUCCESS = 'success';
        private const STATUS_ERROR = 'error';

        /**
         * Default constructor.
         * Set the static variables.
         *
         * @return void
         *
         */
        public function __construct($params = array())
        {
            self::setParams($params);
        }

        /**
         * Static Singleton Factory Method
         * Return an instance of the current class if it exists
         * Construct a new one otherwise
         *
         * @return Activate object
         *
         */
        public static function instance()
        {
            if (!isset(self::$instance)) {
                $className = __CLASS__;
                self::$instance = new $className();
            }
            return self::$instance;
        }

        /**
         * Method to set APP Environment Params
         * @param array $params configuration array
         *
         * @return void
         */
        protected static function setParams($params)
        {
            // Check for Passed params
            // If empty fallback to config file defined params
            // based on SDK env.
            if (empty($params)) {
                global $APP_ENVIRONMENT_VARS;
                if (defined('PHP_SDK_ENV')) {
                    $params = $APP_ENVIRONMENT_VARS[PHP_SDK_ENV];
                }
            }
            self::$params = $params;
        }

        /**
         * Function to build the authorization URL.
         * It fetches the necessary app id and secret from the saved options.
         *
         * @return $requestUrl, string, the authorization URL.
         *
         * */
        public function getAuthorizeUrl()
        {
            $clientId = self::$params['clientId'];
            $oauthServer = self::$params['oauthServer'];

            $requestUrl  = $oauthServer;
            $requestUrl .= "/authorize?response_type=code";
            $requestUrl .= "&client_id=" . $clientId;

            if (isset(self::$params['instance']) && !empty(self::$params['instance'])) {
                $requestUrl .= "&instance=" . self::$params['instance'];
            }

            $redirectUri = '';
            if (isset(self::$params['redirectUri']) && !empty(self::$params['redirectUri'])) {
                $requestUrl .= "&redirect_uri=" . self::$params['redirectUri'];
            }

            return $requestUrl;
        }

        /**
         * Function to handle authorize callback.
         * Checks for received authorization code,
         * And Sends the code using Post to  authorization server.
         *
         * If success set variables and return true to controller.
         * If Fails return empty array.
         *
         * @param  array  $params URL Params ($_GET)
         * @return array          Response array
         */
        public function handleAuthorizeCallback($params = array())
        {
            // If the callback is the result of an authorization call to
            // the oAuth server:
            //      - Ask for the access token
            //      - Save the access token and all other info
            if (isset($params['code']) && !empty($params['code'])) {
                $responce = $this->fetchAccessTokens($params['code']);
                if (self::STATUS_SUCCESS === $responce['status']) {
                    return $responce;
                }
            }
            return array();
        }

       /**
        * Function To Check if the existing access token has expired.
        * @param  timestamp   $expiresOnDate date timeStamp
        * @return bool                       true / false
        */
        public function accessTokenExpired($expiresOnDate)
        {
            $utcTimezone = new \DateTimeZone('UTC');
            $expirationDate = new \DateTime($expiresOnDate, $utcTimezone);
            $now = new \DateTime(strtotime(DATE_FORMAT), $utcTimezone);

            return $expirationDate < $now;
        }

        /**
         * Function to get a new set of token tokens
         * from an previous refresh token
         * @param  string $refreshToken saved refresh token
         * @return object               response
         */
        public function refreshTokens($refreshToken)
        {
            if (empty($refreshToken)) {
                return;
            }

            $clientId = self::$params['clientId'];
            $clientSecret = self::$params['clientSecret'];
            $oauthServer = self::$params['oauthServer'];
            $instance = self::$params['instance'];

            $grantType = 'refresh_token';

            //Request Token
            $url = $oauthServer . "/token";
            $requestArgs['form_params'] = array(
                'refresh_token' => $refreshToken,
                'grant_type' => $grantType,
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
            );

            $guzzleClient = new Client();
            $response = $guzzleClient->request('POST', $url, $requestArgs);

            return $this->proccessResponse($response);
        }

        /**
         * Function to get a new set of token tokens
         * @param  string $refreshToken saved refresh token
         * @return object               response
         */
        public function fetchAccessTokens($code)
        {
            $clientId = self::$params['clientId'];
            $clientSecret = self::$params['clientSecret'];
            $oauthServer = self::$params['oauthServer'];
            $lmsInstance = self::$params['instance'];
            $redirectUri = self::$params['redirectUri'];

            $grantType = 'authorization_code';

            //Request Token
            $url = $oauthServer . "/token";
            $requestArgs['form_params'] = array(
                'grant_type' =>     $grantType,
                'code' =>           $code,
                'client_id' =>      $clientId,
                'client_secret' =>  $clientSecret,
                'redirect_uri' =>   $redirectUri,
            );

            $guzzleClient = new Client();
            $response = $guzzleClient->request('POST', $url, $requestArgs);

            return $this->proccessResponse($response);
        }

        /**
         * Function to get a new weblink access token
         * @return object response
         */
        public function getWeblinkCode()
        {

            $oauthServer = self::$params['oauthServer'];
            $portal = self::$params['portal'];

            //Request Token
            $url = $oauthServer . "/portal/guest";

            $requestArgs['headers'] =  array(
                'Content-Type' => 'application/json',
                'Accept' => 'application/json, text/plain, */*'
            );

            $body = '{"domain":"' . $portal . '"}';
            $requestArgs['body'] = $body;

            $guzzleClient = new Client();
            $response = $guzzleClient->request('POST', $url, $requestArgs);

            return $this->proccessResponse($response);
        }

        /**
         * Method to process guzzle client Response
         * and return results to be saved.
         *
         * @param  object $response Guzzle Response Object.
         * @return array            Response array
         */
        protected function proccessResponse($response)
        {
            $code = $response->getStatusCode();
            $result = array();
            if (self::SUCCESS_CODE === $response->getStatusCode()) {
                $body = $response->getBody();
                $result['status'] = self::STATUS_SUCCESS;
                $result['body'] = json_decode($body);
            } else {
                $result['status'] = self::STATUS_ERROR;
                $result['error'] = array(
                    'code' => $code,
                    'message' => $response->getReasonPhrase()
                );
            }
            return $result;
        }
    }
}

<?php
namespace Administrate\PhpSdk\Oauth;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

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
        static $redirectUri;
        static $oauthServer;
        static $lmsInstance;

        /**
         * Default constructor.
         * Set the static variables.
         *
         * @return void
         *
         */
        protected function __construct()
        {
            self::setRedirectUri();
            self::setOauthServer();
            self::setLmsInstance();
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
                self::$instance = new $className;
            }
            return self::$instance;
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
            $redirectUri = self::$redirectUri ?: self::setRedirectUri();
            $oauthServer = self::$oauthServer ?: self::setOauthServer();
            $lmsInstance = self::$lmsInstance ?: self::setLmsInstance();

            $appId = OAUTH2_CLIENT_ID;

            if (empty($appId)) {
                return;
            }

            $requestUrl  = $oauthServer;
            $requestUrl .= "/authorize?response_type=code";
            $requestUrl .= "&client_id=" . $appId;
            $requestUrl .= "&instance=" . $lmsInstance;
            $requestUrl .= "&redirect_uri=" . $redirectUri;

            return $requestUrl;
        }

        /**
         * Function to handle authorize callback.
         *
         * Checks for received authorization code,
         * And Sends the code using Post to  authorization server.
         *
         * If success set variables and return true to controller.
         * If Fails return null.
         *
         * */
        public function handleAuthorizeCallback($params=array())
        {
            // If the callback is the result of an authorization call to
            // the oAuth server:
            //      - Ask for the access token
            //      - Save the access token and all other info
            if (!empty($_GET['code'])) {

                $code = $_GET['code'];

                if ( ! $this->fetchAccessToken($code) ) { return false; }

                return true;

            } else {
                return false;
            }
        }

        /**
         * Function to make secure oAuth Call.
         *
         * Check if the access token is expired before making a call
         * to the oAuth server.
         * If the access token is expired, fetch another one.
         * To fetch a new access token, we must send the authorize API
         * on the server the refresh token and get a new access token
         * and refresh token to use.
         *
         * For more information:
         * https://github.com/applicake/doorkeeper/wiki/Enable-Refresh-Token-Credentials
         *
         * */
        protected function makeSecureOauthCall($requestUri)
        {
            $oauthServer = self::$oauthServer ?: self::setOauthServer();
            $url = $oauthServer . $requestUri;

            if ($this->accessTokenExpired()) {
                $this->refreshToken();
            }

            $accessToken = ''; // GET access token from save value;

            $postBody = array(
                'access_token' => $accessToken
            );

            //TODO: Send Call to OauthServer
            $response = array();
            // If the response gave us an error, return.
            //if ( is_wp_error( $response ) ) { return false; }

            if ($response['response']['code'] === 200) {
                return json_decode($response['body']);
            } else {
                return false;
            }
        }

        /**
        * Function To Check if the existing access token has expired.
        */
        protected function accessTokenExpired()
        {
            $expiresOnDate = // GET Refresh token from saved value
            $utcTimezone = new \DateTimeZone('UTC');
            $expirationDate = new \DateTime($expiresOnDate, $utcTimezone);
            $now = new \DateTime(strtotime(DATE_FORMAT), $utcTimezone);

            return $expirationDate < $now;
        }

        /**
        * Function to get a Refresh token.
        */
        protected function refreshToken()
        {
            $refreshToken = ''; // GET Refresh token from saved value
            $oauthServer = self::$oauthServer ?: self::setOauthServer();

            $appId     = OAUTH2_CLIENT_ID;
            $appSecret = OAUTH2_CLIENT_SECRET;

            $grantType = 'refresh_token';

            //Request Token
            $url = $oauthServer . "/token";
            $postBody = array(
                'refresh_token' => $refreshToken,
                'grant_type' => $grantType,
                'client_id' => $appId,
                'client_secret' => $appSecret,
            );

            //TODO: Send Call to OauthServer
            $response = array();

            return $this->saveAccessToken($response);
        }

        /**
        * Function to get a Access token.
        */
        protected function fetchAccessToken($code)
        {
            $redirectUri = self::$redirectUri ?: self::setRedirectUri();
            $oauthServer = self::$oauthServer ?: self::setOauthServer();

            $appId     = OAUTH2_CLIENT_ID;
            $appSecret = OAUTH2_CLIENT_SECRET;

            $grantType = 'authorization_code';

            //Request Token
            $url = $oauthServer . "/token";
            $requestArgs['form_params'] = array(
                'grant_type' =>     $grantType,
                'code' =>           $code,
                'client_id' =>      $appId,
                'client_secret' =>  $appSecret,
                'redirect_uri' =>   $redirectUri,
            );

            echo "<pre>";
            var_dump($url);
            var_dump($requestArgs);
            echo "</pre>";

            $guzzleClient = new Client();

            $response = $guzzleClient->request('POST', $url, $requestArgs);

            $code = $response->getStatusCode(); // 200
            $reason = $response->getReasonPhrase(); // OK
            $body = $response->getBody();
            var_dump($code);
            var_dump($reason);

            echo "<pre>";
            print_r(json_decode($body));
            echo "</pre>";

            die();

            //TODO: Send Call to OauthServer
            $response = array();

            return $this->saveAccessToken($response);
        }

        /**
        * Function to get a Save Access Token.
        */
        protected function saveAccessToken($response)
        {
            // If the response gave us an error, return.
            //TODO:
            //if ( error ) { return false; }

            if ($response['response']['code'] === 200) {

                $result = json_decode($response['body']);

                var_dump($result);

                $accessToken = $result->access_token;
                $expiresIn = $result->expires_in;
                $tokenType = $result->token_type;
                $scope = $result->scope;
                $refreshToken = $result->refresh_token;

                $accessExpiresIn = date(DATE_FORMAT, time() + (int) $expiresIn);

                //TODO: Save the returned values

                return true;

            } else { return false; }
        }

        /**
         * Sets the Redirect URI.
         *
         * @return void
         *
         * */
        protected static function setRedirectUri()
        {
            global $APP_ENVIRONMENT_VARS;
            if (!empty($APP_ENV)){
                self::$redirectUri = $APP_ENVIRONMENT_VARS[$env]['redirectUri'];
            } else {
                self::$redirectUri = $APP_ENVIRONMENT_VARS['dev']['redirectUri'];
            }
            //self::$redirectUri = APP_URL_ROUTES .'?_uri=oauth/callback';
        }

        /**
         * Checks APP Environment and sets the oAuth server path accordingly.
         *
         * @return void
         *
         * */
        protected static function setOauthServer()
        {
            global $APP_ENVIRONMENT_VARS;
            if (!empty($APP_ENV)){
                self::$oauthServer = $APP_ENVIRONMENT_VARS[$env]['aouthServer'];
            } else {
                self::$oauthServer = $APP_ENVIRONMENT_VARS['dev']['aouthServer'];
            }
        }

        protected static function setLmsInstance()
        {
            global $APP_ENVIRONMENT_VARS;
            if (!empty($APP_ENV)){
                self::$lmsInstance = $APP_ENVIRONMENT_VARS[$env]['instance'];
            } else {
                self::$lmsInstance = $APP_ENVIRONMENT_VARS['dev']['instance'];
            }
        }
    }
}

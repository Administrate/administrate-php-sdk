# ADMINISTRATE PHP SDK

PHP SDK which provides a simple way to interact with administrate platform.
Facilitate authorization to the APIs and Provides ways to use the available APIs.

## Usage

### Authorization:
You can use this DSK to authorize your app to connect to the APIs.

```
$activationParams = array(
  'clientId' => '', // Application ID
  'clientSecret' => '', // Application Secret
  'instance' => '', // Administrate Instance to connect to
  'aouthServer' => '', // Administrate Authorization endpoint
  'redirectUri' => '', // You App redirect uri to handle callbacks from api
);

// Create Activate Class instance
$activate = new Administrate\PhpSdk\Oauth\Activate($activationParams);

// Get Authorization Code:
$activate->getAuthorizeUrl();

// Handle Callback Code:
$activate->handleAuthorizeCallback($params); // $params with code.
or
$activate->fetchAccessTokens($code);

// Response Format (array):
{
  "status" => "success",
  "body" => {
    "access_token" => "sWNRpcf.....106vqR4",
    "expires_in"=> 3600,
    "token_type" => "Bearer",
    "scope" => "instance",
    "refresh_token" => "StEqsly.....V5nUhQd1i"
  }
}
```

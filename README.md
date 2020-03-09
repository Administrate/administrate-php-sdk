# Administrate PHP SDK

PHP SDK which provides a simple way to interact with administrate platform.
Facilitate authorization to the APIs and Provides ways to use the available APIs.


## Note

In order to use this library, please contact [Administrate](https://www.getadministrate.com/) to provide you with the needed credentials (clientId, clientSecret, instance url and oauthServer).

## Installation

Using [composer](https://getcomposer.org/)

```composer
composer require administrate/phpsdk
```

## Usage

### Authorization with Core API - Request Code
```php
require_once 'vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

$activationParams = [
    'clientId' => '9juZ...Ig7U',     // Application ID
    'clientSecret' => 'd1RN...qt2h', // Application secret
    'instance' => 'https://YourInstanse.administrateapp.com/',     // Administrate instance to connect to
    'oauthServer' => 'https://auth.getadministrate.com/oauth',  // Administrate authorization endpoint
    'apiUri' => 'https://api.administrateapp.com/graphql', // Administrate Core API endpoint
    'redirectUri' => 'https://YourAppDomain/callback.php',  // Your app redirect URI to handle callbacks from api
];

// Create Activate Class instance
$activationObj = new Activate($activationParams);

// Get Authorization Code:
$urlToGoTo = $activationObj->getAuthorizeUrl();
```

##### Example URL output:
*https://auth.getadministrate.com/oauth/authorize?response_type=code&client_id=9juZ...Ig7U&instance=https://YourInstanse.administrateapp.com/&redirect_uri=https://YourAppDomain/graphql-client/examples/oauth-callback.php*

The Previous code will create a link for you to go to.\
This link will redirect you to the login screen of your instance mentioned in the params with a redirect to link setup for the URL of your choice.\
Once you login to the instance of administrate you will be promoted to authorize the APP.\
Once done you will be redirected to the callback url.

*Check [oauth-activate.php](https://github.com/Administrate/administrate-php-sdk/blob/trunk/examples/oauth-activate.php) in examples folder*

### Authorization with Core API - Callback

##### Example Callback url:
*https://YourAppDomain/callback.php?code=9juZ...Ig7U*

```php
require_once 'vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

//same activationParams as before
$activationObj = new Activate($activationParams);

// Handle Callback.
$response = $activationObj->handleAuthorizeCallback($_GET);
// This method will extract the code from the url
// and trigger sending an access token request using
// "fetchAccessTokens".
// The returned response is an multidimensional array
// with a status and body.
// In the body you have an access_token and a refresh_token
// You should use the access_token in your request header
// as Authorization Bearer in order for you to be granted access to the Core API.

// Or you can get the code from the callback URL
// and pass it as arg to the following method.
$response = $activationObj->fetchAccessTokens($code);

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
*Check [oauth-callback.php](https://github.com/Administrate/administrate-php-sdk/blob/trunk/examples/oauth-callback.php) in examples folder*

You should save the **access_token** to be used with your calls to the API  You should save the **expires_in** to calculate when the **access_token** expires and request a new one.
You should save the **refresh_token** to be used later to get a new **access_token** once it expires.

### Authorization with Core API - Refresh Token
```php
require_once 'vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

//same activationParams as before
$activationObj = new Activate($activationParams);

//$refresh_token value previously saved

// Request an new Access Token.
$response = $activate->refreshTokens($refresh_token);

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
*Check [oauth-refreshToken.php](https://github.com/Administrate/administrate-php-sdk/blob/trunk/examples/oauth-refreshToken.php) in examples folder*

### Authorization with Weblink API
```php
require_once 'vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

$activationParams = [
    'oauthServer' => 'https://portal-auth.administratehq.com', // Administrate weblink authorization endpoint
    'uri' => 'https://weblink-api.administratehq.com/graphql', // Administrate Weblink endpoint
    'portal' => 'APPNAME.administrateweblink.com',
];

// Create Activate Class instance
$activationObj = new Activate($activationParams);

$response = $activationObj->getWeblinkCode();

// Response JSON Text:
{
    "portal_token": "Tcdg...DIY9o"
}
```
*Check [get-weblink-code.php](https://github.com/Administrate/administrate-php-sdk/blob/trunk/examples/get-weblink-code.php) in examples folder*

### Categories Management

*You need a weblink token to be able to list categories*

#### List Categories
```php
require_once '/vendor/autoload.php';

use Administrate\PhpSdk\Category;

$params = [
    'oauthServer' => 'https://portal-auth.administratehq.com', // Administrate weblink authorization endpoint
    'uri' => 'https://weblink-api.administratehq.com/graphql', // Administrate Weblink endpoint
    'portal' => 'APPNAME.administrateweblink.com',
    'accessToken' => 'Tcdg...DIY9o',
];

$categoryObj = new Category($params);

$defaultFields = [
    'id',
    'name',
    'shortDescription',
    'parent'
];

$categoryId = "TGVh....YeTox";

//Get Single Category
$category = $categoryObj->load($categoryId, $defaultFields);

//Get all categories
$page = 1;
$perPage = 5;
$categories = $categoryObj->loadAll($page, $perPage, $defaultFields);

#The parameter "defaultFields" is optional only pass it if you want to change the fields
```
*Check [get-single-category.php](https://github.com/Administrate/administrate-php-sdk/blob/trunk/examples/get-single-category.php)\
and\
[get-multiple-categories.php](https://github.com/Administrate/administrate-php-sdk/blob/trunk/examples/get-multiple-categories.php) in examples folder*

### Courses Management

### List Courses
```php
require_once '/vendor/autoload.php';

use Administrate\PhpSdk\Course;

$params = [
    'oauthServer' => 'https://portal-auth.administratehq.com', // Administrate weblink authorization endpoint
    'uri' => 'https://weblink-api.administratehq.com/graphql',
    'portal' => 'APPNAME.administrateweblink.com',
    'accessToken' => 'Tcdg...DIY9o',
];

$CourseObj = new Course($params);

$defaultFields = [
    'id',
    'name',
    'description',
    'category',
    'imageUrl'
];
$courseId = "TGVh......eTox";

//Get single course
$course = $CourseObj->load($courseId, $defaultFields);

//get Courses with filters
$page = 1; //optional
$perPage = 6; //optional
$categoryId = "TGVh......eTox"; //optional
$searchKeyword = "test_keyword_here"; //optional
$categories = $courseObj->loadAll($page, $perPage, $categoryId, $searchkeyword, $defaultFields);

```
*Check [get-single-course.php](https://github.com/Administrate/administrate-php-sdk/blob/trunk/examples/get-single-course.php)\
and\
[get-multiple-courses.php](https://github.com/Administrate/administrate-php-sdk/blob/trunk/examples/get-multiple-courses.php) in examples folder*

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)

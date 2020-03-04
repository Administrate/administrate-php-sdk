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

### Authorization
```php
require_once 'vendor/autoload.php';

use Administrate\PhpSdk\Oauth\Activate;

$activationParams = [
    'clientId' => '',     // Application ID
    'clientSecret' => '', // Application secret
    'instance' => '',     // Administrate instance to connect to
    'aouthServer' => '',  // Administrate authorization endpoint
    'redirectUri' => '',  // Your app redirect URI to handle callbacks from api,
    'weblink2' => [
        'uri' => '',
        'portal' => '',
        'accessToken' => ''
    ]
];

// Create Activate Class instance
$activationObj = new Activate($activationParams);

// Get Authorization Code:
$activationObj->getAuthorizeUrl();

// Handle Callback Code:
$activationObj->handleAuthorizeCallback($params); // $params with code.
or
$activationObj->fetchAccessTokens($code);

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

### Categories Management
```php
require_once '/vendor/autoload.php';

use Administrate\PhpSdk\Category;

$categoryObj = new Category();

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

### Courses Management
```php
require_once '/vendor/autoload.php';

use Administrate\PhpSdk\Course;

$CourseObj = new Course();

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

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.

Please make sure to update tests as appropriate.

## License
[MIT](https://choosealicense.com/licenses/mit/)
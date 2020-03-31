<?php
header('Content-Type: application/json');

require_once '../config.php';
require_once '../../vendor/autoload.php';

use Administrate\PhpSdk\GraphQL\QueryBuilder as QueryBuilder;
use Administrate\PhpSdk\GraphQL\Client;

$contactFields = array( 'firstName','lastName','emailAddress');

$contact = (new QueryBuilder('contact'));
foreach ($contactFields as $field) {
    $contact->selectField($field);
}

$onLearner = (new QueryBuilder('... on Learner'))
    ->selectField($contact);

$builder = (new QueryBuilder('node'))
    ->setVariable('learnerId', 'ID', true)
    ->setArgument('id', '$learnerId')
    ->selectField($onLearner);

$gqlQuery = $builder->getQuery();

if ($_SESSION['access_token']) {
    $accessToken = $_SESSION['access_token'];
}

$endpointUrl = $coreApiActivationParams['apiUri'];
// $accessToken is set in config.php
$authorizationHeaders = Client::setHeaders(
    array(
        'accessToken' => $accessToken
    ),
    Client::$CORE_API
);
$httpOptions = Client::setArgs();
$variablesArray = array(
    'learnerId' => $learnerId // Set this value in config.php
);

$client = new Client($endpointUrl, $authorizationHeaders);
$results = $client->runQuery($gqlQuery, true, $variablesArray);

echo json_encode($results->getData());

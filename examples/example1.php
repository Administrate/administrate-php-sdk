<?php
include 'vendor/autoload.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
require_once 'config.php';

use GraphQL\QueryBuilder\QueryBuilder as QueryBuilder;
use GraphQL\RawObject;
use GraphQL\Client;
use Administrate\PhpSdk\ClientHelper;

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

$endpointUrl = "https://api.getadministrate.com/graphql";
$accessToken = '21zAoFvqgVg997nHsH9rI2kykHbvwN';
$authorizationHeaders = ClientHelper::setHeaders($accessToken);
$httpOptions = ClientHelper::setArgs();
$variablesArray = array(
    'learnerId' => 'bGVhcm5lcjox'
);

$client = new Client($endpointUrl, $authorizationHeaders);
$results = $client->runQuery($gqlQuery, true, $variablesArray);

echo "<pre>";
var_dump($results->getData());
echo "<pre>";

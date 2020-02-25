<?php
namespace Administrate\PhpSdk;

use GraphQL\QueryBuilder\QueryBuilder as QueryBuilder;
use GraphQL\Client;
use Administrate\PhpSdk\ClientHelper;

/**
 * Category
 *
 * @package    Administrate\PhpSdk
 * @author     Jad Khater <jck@administrate.co>
 */
class Category {

    protected static $weblinkParams;
    protected static $accessToken;

    static $defaultFields = array('id', 'name', 'shortDescription', 'parent');

    /**
     * Default constructor.
     * Set the static variables.
     *
     * @return void
     *
     */
    public function __construct($params = array())
    {
        self::setWeblinkParams($params);
        self::$accessToken = "CHKGBIRRJAVc01aj4078ilWuvm99CCqOhaLBlw0HPGY";
    }

    /**
     * Method to set APP Environment Params
     * @param array $params configuration array
     *
     * @return void
     */
    protected static function setWeblinkParams($params)
    {
        // Check for Passed params
        // If empty fallback to config file defined params
        // based on SDK env.
        if (empty($params)) {
            global $APP_ENVIRONMENT_VARS;
            if (defined('PHP_SDK_ENV')) {
                self::$weblinkParams = $APP_ENVIRONMENT_VARS[PHP_SDK_ENV]['weblink2'];
            }
        } else {
            if (isset($params['weblink2'])) {
                self::$weblinkParams = $params['weblink2'];
            }
        }
    }

    /**
     * Method to set APP Environment Params
     * @param array $params configuration array
     *
     * @return void
     */
    protected static function setWeblinkUri($params)
    {
        // Check for Passed params
        // If empty fallback to config file defined params
        // based on SDK env.
        if (empty($params)) {
            global $APP_ENVIRONMENT_VARS;
            if (defined('PHP_SDK_ENV')) {
                self::$weblinkUri = $APP_ENVIRONMENT_VARS[PHP_SDK_ENV]['weblinkUri'];
            }
        } else {
            if (isset($params['weblinkUri'])) {
                self::$weblinkUri = $params['weblinkUri'];
            }
        }
    }

    /**
     * Method to Get a single category Info from ID.
     *
     * @param  string $id   LMS Category ID
     *
     * @return String       JSON Object
     */
    public static function load($id, $fields = array()) {
        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey) {
            if ('parent' === $fieldKey) {
                $node->selectField(
                    (new QueryBuilder($fieldKey))
                    ->selectField('id'));
            } else {
                $node->selectField($fieldKey);
            }
        }

        $builder = (new QueryBuilder('categories'))
            ->setVariable('filters', '[CategoryFieldFilter]', true)
            ->setArgument('filters', '$filters')
            ->selectField(
                (new QueryBuilder('edges'))
                    ->selectField($node)
            );

        $gqlQuery = $builder->getQuery();

        $authorizationHeaders = ClientHelper::setHeaders(self::$accessToken, self::$weblinkParams);
        $httpOptions = ClientHelper::setArgs();
        $variablesArray = array(
            "filters" => array(
                0 => array(
                    "field" => "id",
                    "operation" => "eq",
                    "value" => $id,
                )
            )
        );

        $client = new Client(self::$weblinkParams['uri'], $authorizationHeaders);
        $results = $client->runQuery($gqlQuery, true, $variablesArray);

        return $results->getData();
    }

    /**
     * Method to get a set of events by IDs
     *
     * @param  array $ids   Array of LMS events Ids
     *
     * @return String       JSON Object Array Of LMS Events
     */
    public static function loadMultiple($ids, $fields = array()) {
        if (!$fields) {
            $fields = self::$defaultFields;
        }
    }

    /**
     * Method to get a set of events by IDs
     *
     * @param  integer $page    The page number
     * @param  integer $perPage The number of items / page
     *
     * @return String       JSON Object Array Of LMS Events
     */
    public static function loadAll($page = 1, $perPage = 10) {

    }


}

<?php
namespace Administrate\PhpSdk;

use Administrate\PhpSdk\GraphQl\QueryBuilder as QueryBuilder;
use Administrate\PhpSdk\GraphQL\Client;

/**
 * Event
 *
 * @package Administrate\PhpSdk
 * @author Ali Habib <ahh@administrate.co>
 */
class Event
{
    public static $weblinkParams;
    static $defaultFields = array('id', 'name', 'start', 'end', 'location' => array('name'));
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
        }
        self::$weblinkParams = $params;
    }

    /**
     * Method to Get a single Event Info from ID.
     *
     * @param  string $id   LMS Event ID
     * @return String       JSON Object
     */
    public static function load($id, $fields = array())
    {

        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey => $fieldVal) {
            if (is_array($fieldVal)) {
                $subNode = (new QueryBuilder(''.$fieldKey.''));
                foreach ($fieldVal as $subFieldKey) {
                    $subNode->selectField($subFieldKey);
                }
                $node->selectField($subNode);
            } else {
                $node->selectField($fieldVal);
            }
        }

        $builder = (new QueryBuilder('events'))
            ->setVariable('filters', '[EventFieldFilter]', true)
            ->setArgument('filters', '$filters')
            ->selectField(
                (new QueryBuilder('edges'))
                    ->selectField($node)
            );

        $gqlQuery = $builder->getQuery();

        $variablesArray = array(
            "filters" => array(
                0 => array(
                    "field" => "id",
                    "operation" => "eq",
                    "value" => $id,
                )
            )
        );

        $class = get_called_class();
        $result = Client::sendSecureCall($class, $gqlQuery, $variablesArray);
        if (isset($result['events']['edges'][0]['node']) && !empty($result['events']['edges'][0]['node'])) {
            return json_encode($result['events']['edges'][0]['node']);
        }
    }

    /**
     * Method to get a set of events by IDs
     *
     * @param  array $ids   Array of LMS events Ids
     *
     * @return String       JSON Object Array Of LMS Events
     */
    public static function loadMultiple($ids, $fields = array())
    {
        //To Do
    }

    /**
     * Method to get all Events
     * @return String JSON Object Array Of Events
     */
    public static function loadAll($page = 1, $perPage = 5, $fields = array())
    {

        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey => $fieldVal) {
            if (is_array($fieldVal)) {
                $subNode = (new QueryBuilder(''.$fieldKey.''));
                foreach ($fieldVal as $subFieldKey) {
                    $subNode->selectField($subFieldKey);
                }
                $node->selectField($subNode);
            } else {
                $node->selectField($fieldVal);
            }
        }

        $first = $perPage;
        if ($page <= 0) {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;

        $builder = (new QueryBuilder('events'))
        ->setArgument('first', $first)
        ->setArgument('offset', $offset)
        ->setVariable('filters', '[EventFieldFilter]', true)
        ->setArgument('filters', '$filters')
        ->selectField(
            (new QueryBuilder('pageInfo'))
                ->selectField('startCursor')
                ->selectField('endCursor')
                ->selectField('totalRecords')
        )
        ->selectField(
            (new QueryBuilder('edges'))
                ->selectField($node)
        );

        $gqlQuery = $builder->getQuery();

        $variablesArray = array(
            "filters" => array()
        );

        $class = get_called_class();
        return Client::sendSecureCallJson($class, $gqlQuery, $variablesArray);
    }


    /**
     * Method to get all Events related to a single course
     * @param String course Code, Array fields
     * @return String JSON Object Array Of Events
     */
    public static function loadByCourseCode($page = 1, $perPage = 20, $courseCode = "", $fields = array())
    {

        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey => $fieldVal) {
            if (is_array($fieldVal)) {
                $subNode = (new QueryBuilder(''.$fieldKey.''));
                foreach ($fieldVal as $subFieldKey) {
                    $subNode->selectField($subFieldKey);
                }
                $node->selectField($subNode);
            } else {
                $node->selectField($fieldVal);
            }
        }

        $first = $perPage;
        if ($page <= 0) {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;

        $builder = (new QueryBuilder('events'))
        ->setArgument('first', $first)
        ->setArgument('offset', $offset)
        ->setVariable('filters', '[EventFieldFilter]', true)
        ->setArgument('filters', '$filters')
        ->selectField(
            (new QueryBuilder('pageInfo'))
                ->selectField('startCursor')
                ->selectField('endCursor')
                ->selectField('totalRecords')
        )
        ->selectField(
            (new QueryBuilder('edges'))
                ->selectField($node)
        );

        $gqlQuery = $builder->getQuery();
        //print_r(json_encode($gqlQuery));
        //die('ssss');

        $variablesArray = array(
            "filters" => array()
        );

        if ($courseCode !="") {
            array_push($variablesArray['filters'], array(
                "field" => "courseCode",
                "operation" => "eq",
                "value" => $courseCode
            ));
        }

        $class = get_called_class();
        return Client::sendSecureCallJson($class, $gqlQuery, $variablesArray);
    }
}

<?php

namespace Administrate\PhpSdk;

use Administrate\PhpSdk\GraphQl\QueryBuilder as QueryBuilder;
use Administrate\PhpSdk\GraphQL\Client;

/**
 * Event
 *
 * @package Administrate\PhpSdk
 * @author Ali Habib <ahh@administrate.co>
 * @author Jad Khater <jck@administrate.co>
 */
class Event
{
    public $params;
    private static $defaultFields = array('id', 'name', 'start', 'end', 'location' => array('name'));
    private static $paging = array('page' => 1, 'perPage' => 25);
    private static $sorting = array('field' => 'title', 'direction' => 'DESC');

    /**
     * Default constructor.
     * Set the static variables.
     *
     * @return void
     *
     */
    public function __construct($params = array())
    {
        $this->setParams($params);
    }

    /**
     * Method to set APP Environment Params
     * @param array $params configuration array
     *
     * @return void
     */
    public function setParams($params)
    {
        $this->params = $params;
    }

    /**
     * Method to Get a single Event Info from ID.
     *
     * @param  string $id   LMS Event ID
     * @return String       JSON Object
     */
    public function load($filters = [], $fields = [], $returnType = 1)
    {
        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey => $fieldVal) {
            if (is_array($fieldVal)) {
                $subNode = (new QueryBuilder('' . $fieldKey . ''));
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
            )
        );

        foreach ($filters as $key => $value) {
            $filter = array(
            "field" => $key,
            "operation" => "eq",
            "value" => $value
                
            );
            array_push($variablesArray['filters'], $filter);
        };
        $result = Client::sendSecureCall($this, $gqlQuery, $variablesArray);
        if (isset($result['events']['edges'][0]['node']) && !empty($result['events']['edges'][0]['node'])) {
            return Client::toType($returnType, $result['events']['edges'][0]['node']);
        }
    }

    /**
     * Method to get all Events
     * @return String JSON Object Array Of Events
     */
    public function loadAll($filters = [], $paging = [], $sorting = [], $fields = [], $returnType = 1)
    {

        //set paging variables
        if (empty($paging)) {
            $paging = self::$paging;
        }
        $perPage = $paging['perPage'];
        $page = $paging['page'];


        //set sorting variables
        if (empty($sorting)) {
            $sorting = self::$sorting;
        }
        $sortField = $sorting['field'];
        $sortDirection = $sorting['direction'];

        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey => $fieldVal) {
            if (is_array($fieldVal)) {
                $subNode = (new QueryBuilder('' . $fieldKey . ''));
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
        ->setVariable('order', 'EventFieldOrder', false)
        ->setArgument('order', '$order')
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
            "filters" => array(),
            "order" => ''
        );


        if (!empty($sorting)) {
            $sortingObject = new Class{
            };
            $sortingObject->field = $sortField;
            $sortingObject->direction = $sortDirection;
            //$sortingObject = new RawObject('{"field": "'.$sortField.'", "direction": "'.$sortDirection.'"}');
            $variablesArray['order'] = $sortingObject;
        }

        $result = Client::sendSecureCall($this, $gqlQuery, $variablesArray);
        return Client::toType($returnType, $result);
    }


    /**
     * Method to get all Events related to a single course
     * @param String course Code, Array fields
     * @return String JSON Object Array Of Events
     */
    public function loadByCourseCode($filters = [], $paging = [], $sorting = [], $fields = [], $returnType = 1)
    {
        //set paging variables
        if (empty($paging)) {
            $paging = self::$paging;
        }
        $perPage = $paging['perPage'];
        $page = $paging['page'];


        //set sorting variables
        if (empty($sorting)) {
            $sorting = self::$sorting;
        }
        $sortField = $sorting['field'];
        $sortDirection = $sorting['direction'];

        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey => $fieldVal) {
            if (is_array($fieldVal)) {
                $subNode = (new QueryBuilder('' . $fieldKey . ''));
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
        ->setVariable('order', 'EventFieldOrder', false)
        ->setArgument('order', '$order')
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
            "filters" => array(),
            "order" => ''
        );

        if (isset($filters['courseCode']) && $filters['courseCode'] != "") {
            array_push($variablesArray['filters'], array(
                "field" => "courseCode",
                "operation" => "eq",
                "value" => $filters['courseCode']
            ));
        }

        if (!empty($sorting)) {
            $sortingObject = new Class{
            };
            $sortingObject->field = $sortField;
            $sortingObject->direction = $sortDirection;
            //$sortingObject = new RawObject('{"field": "'.$sortField.'", "direction": "'.$sortDirection.'"}');
            $variablesArray['order'] = $sortingObject;
        }

        $result = Client::sendSecureCall($this, $gqlQuery, $variablesArray);
        return Client::toType($returnType, $result);
    }
}

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
    public function load($id, $fields = array())
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
                0 => array(
                    "field" => "id",
                    "operation" => "eq",
                    "value" => $id,
                )
            )
        );

        $result = Client::sendSecureCall($this, $gqlQuery, $variablesArray);
        if (isset($result['events']['edges'][0]['node']) && !empty($result['events']['edges'][0]['node'])) {
            return json_encode($result['events']['edges'][0]['node']);
        }
    }

    /**
     * Method to get all Events
     * @return String JSON Object Array Of Events
     */
    public function loadAll($page = 1, $perPage = 5, $fields = array())
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

        $result = Client::sendSecureCallJson($this, $gqlQuery, $variablesArray);
        return $result;
    }


    /**
     * Method to get all Events related to a single course
     * @param String course Code, Array fields
     * @return String JSON Object Array Of Events
     */
    public function loadByCourseCode($page = 1, $perPage = 20, $courseCode = "", $fields = array())
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

        if ($courseCode != "") {
            array_push($variablesArray['filters'], array(
                "field" => "courseCode",
                "operation" => "eq",
                "value" => $courseCode
            ));
        }

        $result = Client::sendSecureCallJson($this, $gqlQuery, $variablesArray);
        return $result;
    }
}

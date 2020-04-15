<?php

namespace Administrate\PhpSdk;

use Administrate\PhpSdk\GraphQl\QueryBuilder as QueryBuilder;
use Administrate\PhpSdk\GraphQL\Client;

/**
 * Course
 *
 * @package Administrate\PhpSdk
 * @author Ali Habib <ahh@administrate.co>
 * @author Jad Khater <jck@administrate.co>
 */
class Course
{
    public $params;
    private static $defaultFields = array('id', 'code', 'name', 'description', 'category', 'imageUrl');

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
     * Method to Get a single course Info from ID.
     *
     * @param  string $id   LMS Course ID
     *
     * @return String       JSON Object
     */
    public function load($id, $fields = array())
    {
        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey) {
            $node->selectField($fieldKey);
        }

        $builder = (new QueryBuilder('courses'))
            ->setVariable('filters', '[CourseFieldFilter]', true)
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
        if (isset($result['courses']['edges'][0]['node']) && !empty($result['courses']['edges'][0]['node'])) {
            return json_encode($result['courses']['edges'][0]['node']);
        }
    }

    /**
     * Method to get all Courses
     * @return String JSON Object Array Of Courses
     */
    public function loadAll($page = 1, $perPage = 5, $categoryId = "", $keyword = "", $fields = array())
    {
        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey) {
            $node->selectField($fieldKey);
        }

        $first = $perPage;
        if ($page <= 0) {
            $page = 1;
        }

        $offset = ($page - 1) * $perPage;

        $builder = (new QueryBuilder('courses'))
        ->setArgument('first', $first)
        ->setArgument('offset', $offset)
        ->setVariable('filters', '[CourseFieldFilter]', true)
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

        if ($categoryId != "") {
            array_push($variablesArray['filters'], array(
                "field" => "categoryId",
                "operation" => "eq",
                "value" => $categoryId
            ));
        }
        if ($keyword != "") {
            array_push($variablesArray['filters'], array(
                "field" => "name",
                "operation" => "like",
                "value" => "%$keyword%"
            ));
        }
        $result = Client::sendSecureCallJson($this, $gqlQuery, $variablesArray);
        return $result;
    }
}

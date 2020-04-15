<?php

namespace Administrate\PhpSdk;

use Administrate\PhpSdk\GraphQl\QueryBuilder as QueryBuilder;
use Administrate\PhpSdk\GraphQL\Client;

/**
 * Category
 *
 * @package Administrate\PhpSdk
 * @author Jad Khater <jck@administrate.co>
 * @author Ali Habib <ahh@administrate.co>
 */
class Category
{

    public $params;
    private static $defaultFields = array('id', 'name', 'shortDescription', 'parent');

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
     * Method to Get a single category Info from ID.
     *
     * @param  string $id LMS Category ID
     *
     * @return String JSON Object
     */
    public function load($id, $fields = array())
    {
        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey) {
            if ('parent' === $fieldKey) {
                $node->selectField(
                    (new QueryBuilder($fieldKey))
                    ->selectField('id')
                );
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
        if (isset($result['categories']['edges'][0]['node']) && !empty($result['categories']['edges'][0]['node'])) {
            return json_encode($result['categories']['edges'][0]['node']);
        }
    }

    /**
     * Method to get all Categories
     * @return String JSON Object Array Of Categories
     */
    public function loadAll($page = 1, $perPage = 5, $fields = array())
    {
        if (!$fields) {
            $fields = self::$defaultFields;
        }

        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey) {
            if ('parent' === $fieldKey) {
                $node->selectField(
                    (new QueryBuilder($fieldKey))
                    ->selectField('id')
                );
            } else {
                $node->selectField($fieldKey);
            }
        }

        $first = $perPage;
        if ($page <= 0) {
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $builder = (new QueryBuilder('categories'))
            ->setArgument('first', $first)
            ->setArgument('offset', $offset)
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

        $result = Client::sendSecureCallJson($this, $gqlQuery);
        return $result;
    }
}

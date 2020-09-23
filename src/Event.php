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
    private static $paging = array('page' => 1, 'perPage' => 25);
    private static $sorting = array('field' => 'id', 'direction' => 'desc');

    private static $defaultFields = array(
        'id',
        'name',
        'price' => array(
            'amount'
        ),
        'classroomStart',
        'classroomEnd',
        'lmsStart',
        'lmsEnd',
        'start',
        'end',
        'deliveryMethod',
        'remainingPlaces',
        'location' => array(
            'name',
        ),
        'tax' => array(
            'id',
            'effectiveRate',
            'name',
        ),
        'course' => array(
            'id',
            'code',
        ),
    );

    private static $defaultCoreFields = array(
        'id',
        'title',
        'price',
        'classroomStart',
        'classroomEnd',
        'lmsStart',
        'lmsEnd',
        'start',
        'end',
        'bookedPlaces',
        'remainingPlaces',
        'maxPlaces',
        'location' => array(
            'id',
            'name',
            'region' => array(
                'code'
            )
        ),
        'courseTemplate' => array(
            'id',
            'code',
            'title',
        ),
    );

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
     * Method to Get a single event Info from ID.
     *
     * @param  string $id LMS Category ID
     *
     * @return String JSON Object
     */
    public function loadById($eventId, $args)
    {
        if ($eventId) {
            $args['filters'] = array(
                array(
                    'field' => 'id',
                    'operation' => 'eq',
                    'value' => $eventId,
                )
            );
        }
        return self::load($args);
    }

    /**
     * Method to Get Events Info.
     * @param array $filters
     * @param array $fields //defaults defined in class constant $defaultFields
     * @param string $returnType //json, array, obj default: array
     * @return based on returnType
     */
    public function load($args)
    {
        $defaultArgs = array(
            'filters' => array(),
            'fields' => self::$defaultFields,
            'returnType' => 'json', //array, obj, json,
            'coreApi' => false,
        );

        $nodeType = "events";
        $nodeFilters = "EventFieldFilter";

        if (isset($args['coreApi']) && $args['coreApi']) {
            $defaultArgs['fields'] = self::$defaultCoreFields;
            $nodeType = 'events';
            $nodeFilters = 'EventFieldGraphFilter';
        }

        $args = Helper::setArgs($defaultArgs, $args);
        extract($args);

        $node = QueryBuilder::buildNode($fields);

        $builder = (new QueryBuilder($nodeType))
            ->setVariable('filters', "[$nodeFilters]", true)
            ->setArgument('filters', '$filters')
            ->selectField(
                (new QueryBuilder('edges'))
                    ->selectField($node)
            );

        $gqlQuery = $builder->getQuery();

        $variablesArray = array(
            "filters" => $filters
        );

        $result = Client::sendSecureCall($this, $gqlQuery, $variablesArray);
        if (isset($result[$nodeType]['edges'][0]['node']) && !empty($result[$nodeType]['edges'][0]['node'])) {
            return Client::toType($returnType, $result[$nodeType]['edges'][0]['node']);
        }
    }

    /**
     * Method to get all Events
     * @param array $filters
     * @param array $paging ['page' => '', 'perPage' => '']
     * @param array $sorting ['field' => '', 'direction' => '']
     * @param array $fields //defaults defined in class constant $defaultFields
     * @param string $returnType //json, array, obj default: array
     * @return based on returnType
     */
    public function loadAll($args)
    {
        $defaultArgs = array(
            'filters' => array(),
            'paging' => self::$paging,
            'sorting' => self::$sorting,
            'fields' => self::$defaultFields,
            'returnType' => 'json', //array, obj, json,
            'coreApi' => false,
        );

        $nodeType = 'events';
        $nodeOrder = 'EventFieldOrder';
        $nodeFilters = 'EventFieldFilter!';

        if (isset($args['coreApi']) && $args['coreApi']) {
            $defaultArgs['fields'] = self::$defaultCoreFields;
            $nodeType = 'events';
            $nodeOrder = 'EventFieldGraphOrder';
            $nodeFilters = 'EventFieldGraphFilter';
        }

        $args = Helper::setArgs($defaultArgs, $args);
        extract($args);

        //set paging variables
        $perPage = $paging['perPage'];
        $page = $paging['page'];

        $node = QueryBuilder::buildNode($fields);

        $first = $perPage;
        if ($page <= 0) {
            $page = 1;
        }
        $offset = ($page - 1) * $perPage;

        $builder = (new QueryBuilder($nodeType))
            ->setVariable('order', $nodeOrder, false)
                ->setArgument('first', $first)
                ->setArgument('offset', $offset)
                ->setArgument('order', '$order')
            ->setVariable('filters', "[$nodeFilters]", true)
                ->setArgument('filters', '$filters')
            ->selectField(
                (new QueryBuilder('pageInfo'))
                    ->selectField('startCursor')
                    ->selectField('endCursor')
                    ->selectField('totalRecords')
                    ->selectField('hasNextPage')
                    ->selectField('hasPreviousPage')
            )
            ->selectField(
                (new QueryBuilder('edges'))
                ->selectField($node)
            );

        $gqlQuery = $builder->getQuery();

        $variablesArray = array(
            "filters" => array(),
            'order' => Helper::toObject($sorting),
        );

        $result = Client::sendSecureCall($this, $gqlQuery, $variablesArray);
        return Client::toType($returnType, $result);
    }


    /**
     * Method to get all Events related to a single course
     * @param array $filters
     * @param array $paging ['page' => '', 'perPage' => '']
     * @param array $sorting ['field' => '', 'direction' => '']
     * @param array $fields //defaults defined in class constant $defaultFields
     * @param string $returnType //json, array, obj default: array
     * @return based on returnType
     */
    public function loadByCourseCode($filters = [], $paging = [], $sorting = [], $fields = [], $returnType = 'array')
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

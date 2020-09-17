<?php

namespace Administrate\PhpSdk\GraphQl;

use GraphQL\QueryBuilder\QueryBuilder as GqlQueryBuilder;

class QueryBuilder extends GqlQueryBuilder
{
    static function buildNode($fields)
    {
        $node = (new QueryBuilder('node'));
        foreach ($fields as $fieldKey => $fieldVal) {
            if (is_array($fieldVal)) {
                if (isset($fieldVal['type']) && $fieldVal['type'] == 'edges') {
                    $fields = $fieldVal['fields'];
                    $subNode = (new QueryBuilder('' . $fieldKey . ''));
                    $edgesNode = (new QueryBuilder('edges'));
                    $innerNode = (new QueryBuilder('node'));
                    foreach ($fields as $subFieldKey) {
                        $innerNode->selectField($subFieldKey);
                    }
                    $edgesNode->selectField($innerNode);
                    $subNode->selectField($edgesNode);
                } else {
                    $subNode = (new QueryBuilder('' . $fieldKey . ''));
                    foreach ($fieldVal as $subFieldKey) {
                        $subNode->selectField($subFieldKey);
                    }
                }
                $node->selectField($subNode);
            } else {
                $node->selectField($fieldVal);
            }
        }
        return $node;
    }
}

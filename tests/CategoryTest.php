<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Administrate\PhpSdk\Category;

/**
 * CategoryTest
 *
 * @package Administrate\PhpSdk
 * @author Ali Habib <ahh@administrate.co>
 * @author Jad Khater <jck@administrate.co>
 */
final class CategoryTest extends TestCase
{
    public function testLoadSingleCategory(): void
    {
        $weblinkActivationParams = getWeblinkActivationParams();

        $fields = [];
        $returnType = 'array'; //array, obj, json

        $categoryObj = new Category($weblinkActivationParams);
        $categoryArray = $categoryObj->loadById($_GET['categoryId'], $fields, 'array');
        $categoryJson = $categoryObj->loadById($_GET['categoryId'], $fields, 'json');
        $categoryObj = $categoryObj->loadById($_GET['categoryId'], $fields, 'obj');

        //check response is a php array
        $this->assertisArray($categoryArray);
        //check response is in json format
        $this->assertTrue($this->is_json($categoryJson));
        //check response is a pHP object
        $this->assertisObject($categoryObj);
        
        $this->assertArrayHasKey('id', json_decode($categoryJson, true), 'The returned json has invalid format');
        $this->assertArrayHasKey('name', json_decode($categoryJson, true), 'The returned json has invalid format');
        $this->assertArrayHasKey('id', $categoryArray, 'The returned array has invalid format');
        $this->assertArrayHasKey('name', $categoryArray, 'The returned array has invalid format');
        $this->assertObjectHasAttribute('id', $categoryObj, 'The returned object has invalid format');
        $this->assertObjectHasAttribute('name', $categoryObj, 'The returned object has invalid format');
    }

    public function testLoadMultipleCourses(): void
    {
        $weblinkActivationParams = getWeblinkActivationParams();
        
        $fields = [];
        $paging = ['page' => 1, 'perPage' => 25];
        $sorting = ['field' => 'name', 'direction' => 'asc'];
        $filters = [];

        $categoryObj = new Category($weblinkActivationParams);
        $resultArray = $categoryObj->loadAll($filters, $paging, $sorting, $fields, 'array');
        $resultJson = $categoryObj->loadAll($filters, $paging, $sorting, $fields, 'json');
        $resultObject = $categoryObj->loadAll($filters, $paging, $sorting, $fields, 'obj');

        //check response is a php array
        $this->assertisArray($resultArray);
        //check response is in json format
        $this->assertTrue($this->is_json($resultJson));
        //check response is a pHP object
        $this->assertisObject($resultObject);

        $jsonToArray  = json_decode($resultJson, true);
        $this->assertArrayHasKey('edges', $jsonToArray['categories'], 'The returned json has invalid format');
        $this->assertArrayHasKey('pageInfo', $jsonToArray['categories'], 'The returned json has invalid format');
        $this->assertArrayHasKey('edges', $resultArray['categories'], 'The returned array has invalid format');
        $this->assertArrayHasKey('pageInfo', $resultArray['categories'], 'The returned array has invalid format');
        $this->assertObjectHasAttribute('edges', $resultObject->categories, 'The returned object has invalid format');
        $this->assertObjectHasAttribute('pageInfo', $resultObject->categories, 'The returned object has invalid format');
    }

    public function testPagination(): void
    {
        $weblinkActivationParams = getWeblinkActivationParams();

        $fields = [];
        $paging = ['page' => 1, 'perPage' => 25];
        $sorting = ['field' => 'name', 'direction' => 'asc'];
        $filters = [];

        $categoryObj = new Category($weblinkActivationParams);
        $resultArray = $categoryObj->loadAll($filters, $paging, $sorting, $fields, 'array');

        //check if pagination returns the requested number of results
        if ($resultArray['categories']['pageInfo']['totalRecords'] >= $paging['perPage']) {
            $this->assertEquals(25, count($resultArray['categories']['edges']), 'Error in pagination results');
        } else {
            $perPage = ceil($resultArray['pageInfo']['totalRecords']/2);
            $resultArray = $categoryObj->loadAll(
                $filters,
                $paging = ['page' => 1, 'perPage' => $perPage],
                $sorting,
                $fields,
                'array'
            );
             $this->assertEquals($perPage, count($resultArray['categories']['edges']), 'Error in pagination results');
        }
    }

    public function is_json($str)
    {
        return json_decode($str) != null;
    }
    public function getWeblinkActivationParams()
    {
        array(
        'oauthServer' => $_GET['weblinkOauthServer'],
        'apiUri' => $_GET['weblinkApiUri'],
        'portal' => $_GET['portal'],
        'portalToken' => ''.$_GET['portalToken'].''
        );
    }
}

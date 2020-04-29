<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Administrate\PhpSdk\Event;

final class EventTest extends TestCase
{
    public function testLoadSingleEvent(): void
    {
        $weblinkActivationParams = array(
            'oauthServer' => 'https://portal-auth.administratehq.com',
            'apiUri' => 'https://weblink-api.administratehq.com/graphql/',
            'portal' => 'mdbcwl-kfmc.administrateweblink.com',
            'portalToken' => ''.$_GET['portalToken'].''
        );
        $fields = [];
        $returnType = 'array'; //array, obj, json
        $event = new Event();
        $eventObj = new Event($weblinkActivationParams);
        $eventArray = $eventObj->loadById($_GET['eventId'], $fields, 'array');
        $eventJson = $eventObj->loadById($_GET['eventId'], $fields, 'json');
        $eventObj = $eventObj->loadById($_GET['eventId'], $fields, 'obj');

        //check response is a php array
        $this->assertisArray($eventArray);
        //check response is in json format
        $this->assertTrue($this->is_json($eventJson));
        //check response is a pHP object
        $this->assertisObject($eventObj);
    }

    public function testLoadMultipleEvent(): void
    {
        $weblinkActivationParams = array(
            'oauthServer' => 'https://portal-auth.administratehq.com',
            'apiUri' => 'https://weblink-api.administratehq.com/graphql/',
            'portal' => 'mdbcwl-kfmc.administrateweblink.com',
            'portalToken' => ''.$_GET['portalToken'].''
        );
        $fields = [];
        $event = new Event();
        
        $fields = [];
        $paging = ['page' => 1, 'perPage' => 25];
        $sorting = ['field' => 'title', 'direction' => 'asc'];
        $filters = [];

        $eventObj = new Event($weblinkActivationParams);
        $resultArray = $eventObj->loadAll($filters, $paging, $sorting, $fields, 'array');
        $resultJson = $eventObj->loadAll($filters, $paging, $sorting, $fields, 'json');
        $resultObject = $eventObj->loadAll($filters, $paging, $sorting, $fields, 'obj');

        //check response is a php array
        $this->assertisArray($resultArray);
        //check response is in json format
        $this->assertTrue($this->is_json($resultJson));
        //check response is a pHP object
        $this->assertisObject($resultObject);
    }

    public function testLoadEventsbyCourseCode(): void
    {
        $weblinkActivationParams = array(
            'oauthServer' => 'https://portal-auth.administratehq.com',
            'apiUri' => 'https://weblink-api.administratehq.com/graphql/',
            'portal' => 'mdbcwl-kfmc.administrateweblink.com',
            'portalToken' => ''.$_GET['portalToken'].''
        );
        $fields = [];
        $event = new Event();
        
        $fields = [];
        $paging = ['page' => 1, 'perPage' => 25];
        $sorting = ['field' => 'title', 'direction' => 'asc'];
        $filters = ['courseCode' => $courseCode];

        $eventObj = new Event($weblinkActivationParams);
        $events = $eventObj->loadByCourseCode($filters, $paging, $sorting, $fields, $returnType);

        $resultArray = $eventObj->loadAll($filters, $paging, $sorting, $fields, 'array');
        $resultJson = $eventObj->loadAll($filters, $paging, $sorting, $fields, 'json');
        $resultObject = $eventObj->loadAll($filters, $paging, $sorting, $fields, 'obj');

        //check response is a php array
        $this->assertisArray($resultArray);
        //check response is in json format
        $this->assertTrue($this->is_json($resultJson));
        //check response is a pHP object
        $this->assertisObject($resultObject);
    }

    public function testPagination(): void
    {
            $weblinkActivationParams = array(
            'oauthServer' => 'https://portal-auth.administratehq.com',
            'apiUri' => 'https://weblink-api.administratehq.com/graphql/',
            'portal' => 'mdbcwl-kfmc.administrateweblink.com',
            'portalToken' => ''.$_GET['portalToken'].''
            );
                $fields = [];
                $event = new Event();
        
                $fields = [];
                $paging = ['page' => 1, 'perPage' => 25];
                $sorting = ['field' => 'title', 'direction' => 'asc'];
                $filters = [];

                $eventObj = new Event($weblinkActivationParams);
                $events = $eventObj->loadAll($filters, $paging, $sorting, $fields, $returnType);

                $resultArray = $eventObj->loadAll($filters, $paging, $sorting, $fields, 'array');
                //check if pagination returns the requested number of results
                $this->assertEquals(27, count($resultArray['events']['edges']), 'Error in pagination results');
    }

    public function is_json($str)
    {
        return json_decode($str) != null;
    }
}

<?php
namespace Administrate\PhpSdk;

/**
 * Event
 *
 * @package    Administrate\PhpSdk
 * @author     Jad Khater <jck@administrate.co>
 */
class Event {

    /**
     * Method to Get a single event Info from ID.
     *
     * @param  string $id   LMS Event ID
     *
     * @return String       JSON Object
     */
    public static function load($id) {

    }

    /**
     * Method to get a set of events by IDs
     *
     * @param  array $ids   Array of LMS events Ids
     *
     * @return String       JSON Object Array Of LMS Events
     */
    public static function loadMultiple($ids) {

    }

    /**
     * Method to get Events By Category Id with paging abilty
     *
     * @param  string  $cid     LMS Event Category ID
     * @param  integer $page    The page number
     * @param  integer $perPage The number of items / page
     *
     * @return String           JSON Object Array Of LMS Events
     */
    public static function loadByCategoryId($cid, $page = 1, $perPage = 10) {

    }

    /**
     * Method to parse search filters and return Filters array
     * @param  array $args  Search Arguments Array
     * @return array        Search Filters array
     */
    public static function searchFilters($args) {

    }

    /**
     * Method to search for Events.
     * @param  array $args Search Arguments Array
     * @return String      JSON Response String of List of events / Search Facets
     */
    public static function search($args) {
        $filters = self::searchFilters($args);
    }
}

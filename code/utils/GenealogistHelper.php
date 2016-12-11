<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 10, 2016 - 3:13:22 PM
 */
class GenealogistHelper {

    public static function get_born_today($date = null) {
        return self::get_filtered_people('BirthDate', 'Anniversary', $date);
    }

    public static function get_born_this_year($date = null) {
        return self::get_filtered_people('BirthDate', 'Annual', $date);
    }

    public static function get_dead_today($date = null) {
        return self::get_filtered_people('DeathDate', 'Anniversary', $date);
    }

    public static function get_dead_this_year($date = null) {
        return self::get_filtered_people('DeathDate', 'Annual', $date);
    }

    public static function get_today_date() {
        $today = new Date();
        $time = SS_Datetime::now()->Format('U');
        $today->setValue($time);

        return $today->Nice();
    }

    public static function get_filtered_people($field, $filter, $date = null) {
        $people = Person::get();

        if (!$date) {
            $date = self::get_today_date();
        }

        $people = $people->filter(array(
            $field . ':' . $filter => $date
        ));

        return $people;
    }

    public static function get_root_clans() {
        return Clan::get()->filter(array('FatherID' => 0));
    }

    public static function search_all_people($request, $term) {
        if (is_numeric($term)) {
            die('Numeric: ' . $term);
            return DataObject::get_by_id('Person', $term);
        }

        // to fetch books that's name contains the given search term
        $people = DataObject::get('Person')->filterAny(array(
            'Name:PartialMatch' => $term,
            'NickName:PartialMatch' => $term,
        ));

        return $people;
    }

    public static function add_sons($id, $names, $delimiter = "|") {
        $parent = DataObject::get_by_id('Person', (int) $id);
        $namesList = explode($delimiter, $names);

        echo 'Add ' . count($namesList) . ' sons to: ' . $parent->getTitle() . '<br />';

        foreach ($namesList as $name) {
            $son = new Male();
            $son->Name = $name;
            $son->FatherID = $id;
            $son->write();
            echo '&emsp;Sone: ' . $name . ' has ID: ' . $son->ID . '<br />';
        }
    }

    public static function add_parent($id, $name) {
        $person = DataObject::get_by_id('Person', (int) $id);

        echo 'Add parent (' . $name . ') to: ' . $person->getTitle() . '<br />';

        $parent = new Male();
        $parent->Name = $name;
        $parent->FatherID = $person->FatherID;
        $parent->write();

        $person->FatherID = $parent->ID;
        $person->write();

        echo '&emsp;became: ' . $person->getTitle() . '<br />';
    }

    public static function delete_person($id, $reconnect = false) {
        // Connects
    }

}

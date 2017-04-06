<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 27, 2017 - 1:33:25 PM
 */
class PersonalEvent
        extends DataObject {

    private static $db = array(
        'EventTitle' => 'Varchar(255)',
        'EventDate' => 'Date',
        'DatePrecision' => 'Enum("Accurate, Estimated, Calculated", "Accurate")',
        'EventPlace' => 'Varchar(255)',
        'EventContent' => 'HTMLText',
        'EventType' => 'Enum("Birth, Death, Marriage, Resident, Graduated, Custom", "Custom")',
        'IsPrivate' => 'Boolean',
        'IsEdited' => 'Boolean',
        'Relation' => 'Varchar(25)',
        'Age' => 'Varchar(255)',
    );
    private static $has_one = array(
        'Person' => 'Person',
        'RelatedPerson' => 'Person',
    );
    private static $summary_fields = array(
        'Title',
        'Person.Name',
        'RelatedPerson.Name',
        'EventDate',
        'EventPlace',
        'Age',
        'EventType',
    );
    private static $default_sort = 'EventDate';

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['DatePrecision'] = _t('Genealogist.Date_Precision', 'Date Precision');
        $labels['IsEdited'] = _t('Genealogist.Date_Precision', 'Date Precision');

        $labels['Title'] = _t('Genealogist.EVENT_TITLE', 'Event Title');
        $labels['EventTitle'] = _t('Genealogist.EVENT_TITLE', 'Event Title');
        $labels['Person.Name'] = _t('Genealogist.PERSON', 'Person');
        $labels['RelatedPerson.Name'] = _t('Genealogist.RELATED_PERSON', 'Related Person');
        $labels['EventDate'] = _t('Genealogist.EVENT_DATE', 'Event Date');
        $labels['EventPlace'] = _t('Genealogist.EVENT_PLACE', 'Event Place');
        $labels['Age'] = _t('Genealogist.AGE', 'Age');
        $labels['EventType'] = _t('Genealogist.EVENT_TYPE', 'Event Type');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        if ($field = $fields->fieldByName('Root.Main.EventDate')) {
            $field->setConfig('showcalendar', true);
            $field->setConfig('dateformat', 'dd-MM-yyyy');
        }
        return $fields;
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();

        if ($this->EventDate) {
            $this->Age = GenealogistEventsHelper::age_at_event(
                            GenealogistEventsHelper::get_birth_date($this->Person()), //
                            $this->EventDate
            );
        }

        $content = GenealogistEventsHelper::generate_event_content($this, $this->Person(), $this->RelatedPerson());
        if ($content) {
            $this->EventContent = $content;
        }
    }

    public function getTitle() {
        return _t('Genealogist.' . $this->EventTitle, $this->EventTitle);
    }

    public function getContent() {
        return _t('Genealogist.' . $this->EventContent, $this->EventContent);
//        return GenealogistEventsHelper::generate_event_content($this->Person(), $this->RelatedPerson(), $this->EventType, $this->EventRelation, $this->DatePrecision);
    }

    public function getDateValue() {
        if ($this->DatePrecision == 'Accurate') {
//            $value = _t("Genealogist.ON_DATE", 'on {date}', array(
//                'age' => $this->EventDate
//            ));
            $value = $this->EventDate;
        } else {
            $date = new DateTime($this->EventDate);
//            $value = _t("Genealogist.IN_DATE", 'in {date}', array(
//                'age' => $date->format('Y')
//            ));
            $value = $date->format('Y');
        }
        return $value;
    }

}

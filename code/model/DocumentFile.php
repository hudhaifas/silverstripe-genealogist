<?php

/*
 * MIT License
 *  
 * Copyright (c) 2016 Hudhaifa Shatnawi
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Jan 3, 2017 - 9:34:12 AM
 */
class DocumentFile
        extends DataObject {

    private static $db = array(
        'Title' => 'Varchar(255)',
        'Date' => 'Date',
        'Description' => 'Text',
    );
    private static $has_one = array(
        'Docuement' => 'Image',
        'Person' => 'Person',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
        'Tags' => 'DocumentTag',
    );
    private static $searchable_fields = array(
        'Title' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'Description' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'ThumbDocuement',
        'Title',
        'Date',
        'Description',
    );
    public static $STATE_ALIVE = 1;
    public static $STATE_DEAD = 2;

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Docuement'] = _t('Genealogist.DOCUEMENT', 'Docuement');
        $labels['ThumbDocuement'] = _t('Genealogist.DOCUEMENT', 'Docuement');

        $labels['Title'] = _t('Genealogist.TITLE', 'Title');
        $labels['Description'] = _t('Genealogist.DESCRIPTION', 'Description');
        $labels['Person'] = _t('Genealogist.PERSON', 'Person');
        $labels['Date'] = _t('Genealogist.DATE', 'Date');
        $labels['Tags'] = _t('Genealogist.TAGS', 'Tags');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            if ($field = $fields->fieldByName('Root.Main.Docuement')) {
                $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $field->setFolderName("genealogist/docs");
            }

            if ($field = $fields->fieldByName('Root.Main.Date')) {
                $field->setConfig('showcalendar', true);
                $field->setConfig('dateformat', 'dd-MM-yyyy');
            }

            $fields->removeFieldFromTab('Root', 'Tags');
            $tagsField = TagField::create(
                            'Tags', //
                            _t('Genealogist.TAGS', 'Tags'), //
                            DocumentTag::get(), //
                            $self->Tags()
            );
            $fields->addFieldToTab('Root.Main', $tagsField);
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function getDefaultSearchContext() {
        $fields = $this->scaffoldSearchFields(array(
            'restrictFields' => array(
                'Name',
            )
        ));

        $filters = array(
            'Name' => new PartialMatchFilter('Name'),
        );

        return new SearchContext(
                $this->class, $fields, $filters
        );
    }

    /// Permissions ///
    public function canCreate($member = null) {
        return $this->hasPermission();
    }

    public function canView($member = false) {
        return $this->hasPermission();
    }

    public function canDelete($member = false) {
        return $this->hasPermission();
    }

    public function canEdit($member = false) {
        return $this->hasPermission();
    }

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public function hasPermission() {
        return GenealogistHelper::is_genealogists();
    }

    public function ThumbDocuement() {
        return $this->Docuement()->CMSThumbnail();
    }

}
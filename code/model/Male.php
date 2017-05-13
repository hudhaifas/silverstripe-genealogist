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
 * This class presents every male in the genealogy tree.
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 2, 2016 - 11:05:40 AM
 */
class Male
        extends Person {

    private static $db = array(
        // Order
        'HusbandOrder' => 'Int'
    );
    private static $has_one = array(
        'Parent' => 'Person',
        'Tribe' => 'Tribe',
    );
    private static $has_many = array(
        'Children' => 'Person',
    );
    private static $many_many = array(
        'Wives' => 'Female',
    );

    public function canCreate($member = null) {
        return true;
    }

    public function canView($member = false) {
        return true;
    }

    public function canDelete($member = false) {
        return true;
    }

    public function canEdit($member = false) {
        return true;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main', 'HusbandOrder');

        if (!$this->ID) {
            return $fields;
        }

        // Sons
        $config = $this->personConfigs();

        $field = $fields->fieldByName('Root.Sons.Sons');
        $field->setConfig($config);

        // Daughters
        $field = $fields->fieldByName('Root.Daughters.Daughters');
        $field->setConfig($config);

        // Children
        $field = $fields->fieldByName('Root.Children.Children');
        $config = $this->personConfigs();
        $config->addComponent(new GridFieldOrderableRows('ChildOrder'));
        $field->setConfig($config);

        // Husbands
        $field = $fields->fieldByName('Root.Wives.Wives');
        $config = $this->personConfigs(true);
        $config->addComponent(new GridFieldOrderableRows('WifeOrder'));
        $field->setConfig($config);

        return $fields;
    }

    public function getTribeName() {
        $name = '';

        if ($this->Tribe()->exists()) {
            $name .= $this->Tribe()->getTribeName();
        }

        if ($this->Father()->exists()) {
            $name .= $this->Father()->getTribeName();
        }

        return $name;
    }

    public function getObjectDefaultImage() {
        return "genealogist/images/default-male.png";
    }

}

<?php

/**
 * Fetches the name of the current module folder name.
 *
 * @return string
 */
if (!defined('GENEALOGIST_DIR')) {
    define('GENEALOGIST_DIR', ltrim(Director::makeRelative(realpath(__DIR__)), DIRECTORY_SEPARATOR));
}

//Display in cms menu
GenealogistAdmin::add_extension('SubsiteMenuExtension');
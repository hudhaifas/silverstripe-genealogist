<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Jan 28, 2017 - 9:54:02 PM
 */
class GenealogistSearchHelper {

    private static $sort = 'CHAR_LENGTH(IndexedName) ASC';
    private static $all_names = array(
        // Similar names
        'سالم', 'سليم', 'سليمان', 'سلمان', 'مسلم',
        'موسى', 'عيسى',
        'سعيد', 'سعد', 'مسعد', 'سعدى', 'سعدي',
        'محسن', 'حسان',
        'فالح', 'مفلح', 'فليح', 'فلاح',
        'ناجي', 'راجي', 
         'حمود', 'حمد', 'حمدان', 'حميدان', 'حميد',
        'رشيد', 'رشدة', 'ارشيد', 'الرشدان', 'رشدي', 'رشوان', 'فواز', '', '', '', '', '', '', '',
        // Weird names
        'شهرزاد', 'فريال', 
        // Males names
        // Femous names
        'محمد', 'محمود', 'حامد', 'حسن', 'حسين', 'مصطفى',
        // starts with ء
        'أحمد', 'أمجد', 'أسعد', 'إبراهيم', 'إسماعيل', 'أدهم', 'أوس', 'أمير', 'إدريس', 'إقبال', 'آدم', 'أشرف', 'أنس',
        // ends with ء
        'علاء', 'بهاء',
        // ends with الدين
        'علاء الدين', 'بهاء الدين', 'زهاء الدين', 'عماد الدين', 'بدر الدين',
        'عماد', 'عمار', 'بدر', 'بدور', 'بدرية',
        // contains ء
        'فؤاد', 'سائد', 'رائد', 'عايد', 'نايل', 'نائل', 'هايل', 'صايل',
        // with ة
        'حذيفة', 'قتيبة', 'عبيدة', 'عبادة', 'سلامة', 'حمزة',
        // with عبد
        'عبدالله', 'عبدالرحمن', 'عبدالرحيم', 'عبدالمجيد', 'عبدالحميد', 'عبدالمحسن', 'عبدالحي', 'عبدالجواد', 'عبدالرؤوف', 'عبدالغفور', 'عبدالمنعم', 'عبدالوالي', 'عبدالهادي', 'عبدربه', 'عبدالإله', 'العبد', 'عبد', 'عبده',
        // with ى
        'علي', 'فادي', 'شادي', 'هادي', 'رامي', 'فندي', 'ناجي', 'راجي',
        // Females names
        // starts with ء
        'آمنة', 'إيمان', 'إخلاص', 'أروى', 'أسماء', 'آية', 'آيات',
        // ends with ء
        'غيداء', 'شفاء', 'صفاء', 'فداء', 'رجاء', 'آلاء', 'نداء', 'هنا', 'هناء', 'شيماء', 'علياء',
        // ends with ا
        'شما', 'عليا', 'فضا', 'سميا', 'صبحا', 'فلحا', 
        // ends with ى
        'سلمى', 'سلوى',
        // with ة
        'سكينة', 'نجاة', 'ناجية', 'خولة', 'بسمة', 'عشبة', 'شيخة', 'نصرة', 'شريفة', 'فاطمة', 'سالمة', 'حاجة', 'حياة', 'سميرة', 'بديوية', 'زانة', 'رية', 'جهينة', 'سمية',
        'فلحة', 'ربيعة', 'طردة', 'رحيلة', 'زغندة', 'كرمة', 'بدرة', 'طولة', 'طلة', 'قطنة', 'حسنة', 'فندية', 'شمسية', 'عائشة', 'عيشة', 
        //
//        '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',
    );

//    private static $sort = 'IndexedName ASC';

    public static function search_objects($list, $keywords) {
        $r1 = self::search_round($list, $keywords);
        
        $words = explode(" ", $keywords);
        $newQuery = '';
        foreach ($words as $input) {
            $newQuery .= self::correct($input) . ' ';
        }

//        $keywords = self::words_ends_with($keywords, array("ة", "ه", "ى", "ئ", "ء"));
//        $keywords = self::words_starts_with($keywords, array("أ", "إ", "آ", "ا"));

        $r2 = self::search_round($list, $newQuery);

        $results = self::merge($r1, $r2);

        $results->removeDuplicates();
        return $results;
    }

    private static function merge($list1, $list2) {
        $results = new ArrayList;

        foreach ($list1 as $obj) {
            $results->push($obj);
        }

        foreach ($list2 as $obj) {
            $results->push($obj);
        }
        return $results;
    }

    private static function search_round($list, $keywords) {
        $r1 = $list->filterAny(array(
                    'IndexedName:StartsWith' => $keywords,
                ))->sort(self::$sort);

        $r2 = $list->filterAny(array(
                    'IndexedName:PartialMatch' => $keywords,
                ))->sort(self::$sort);

        return self::merge($r1, $r2);
    }

    private static function words_starts_with($keywords, $vowels) {
        $words = explode(" ", $keywords);
        $newwords = '';

        foreach ($words as $word) {
            foreach ($vowels as $vowel) {
                if (self::starts_with($word, $vowel)) {
                    $word = substr_replace(trim($word), "_", 0, 2);
                }
            }

            $newwords .= $word . ' ';
        }

        return trim($newwords);
    }

    private static function words_ends_with($keywords, $vowels) {
        $words = explode(" ", $keywords);
        $newwords = '';

        foreach ($words as $word) {
            foreach ($vowels as $vowel) {
                if (self::ends_with($word, $vowel)) {
//                    $word = str_replace($vowel, "_", $word);
                    $word = substr(trim($word), 0, -2) . "_";
                }
            }

            $newwords .= $word . ' ';
        }

        return trim($newwords);
    }

    /**
     * 
     * @see http://php.net/manual/en/function.levenshtein.php
     */
    private static function correct($input) {
        // no shortest distance found, yet
        $shortest = -1;

        // loop through words to find the closest
        foreach (self::$all_names as $word) {

            // calculate the distance between the input word,
            // and the current word
            $lev = levenshtein($input, $word);

            // check for an exact match
            if ($lev == 0) {

                // closest word is this one (exact match)
                $closest = $word;
                $shortest = 0;

                // break out of the loop; we've found an exact match
                break;
            }

            // if this distance is less than the next found shortest
            // distance, OR if a next shortest word has not yet been found
            if ($lev <= $shortest || $shortest < 0) {
                // set the closest match, and shortest distance
                $closest = $word;
                $shortest = $lev;
            }
        }
        
        return $closest;
    }

    /**
     * http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php/834355#834355
     */
    public static function starts_with($word, $needle) {
        $length = strlen($needle);
        return (substr($word, 0, $length) === $needle);
    }

    /**
     * http://stackoverflow.com/questions/834303/startswith-and-endswith-functions-in-php/834355#834355
     */
    public static function ends_with($word, $needle) {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($word, -$length) === $needle);
    }

}

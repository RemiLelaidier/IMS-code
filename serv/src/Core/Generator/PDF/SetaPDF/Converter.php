<?php

namespace App\Core\Generator\PDF\SetaPDF;


/**
 * Class Converter
 * @package App\Core\Generator\PDF\SetaPDF
 *
 * Generate easily fields.json to use with PDFGenerator
 *
 * You need to do a PDF Form with Acrobat, and the string to convert is given by this page :
 * https://www.setasign.com/products/setapdf-core/demos/find-form-field-coordinates/
 *
 * This converter gives you a JSON Array containing fields with locations / page, in a form usable by the Generator
 *
 * Usage : $converter = new Converter($string);
 *         $pages = $converter->getPagesWithFieldsCount()
 *         $json = $converter->formatFieldsAsJson($pages);
 */
class Converter {

    private $string;

    public function __construct ($string) {
        $this->string = $string;
    }

    public function formatFieldsAsJSON($pages){
        // Find every field to convert into JSON
        $re = '/(\S*): (\S*)/';

        preg_match_all($re, $this->string, $matches, PREG_SET_ORDER, 0);

        $coords = ['llx', 'lly', 'urx', 'ury', 'width', 'height'];
        $objects = [];
        $json = "";

        foreach($matches as $match){
            $newObject = "";
            if(!in_array($match[1], $coords)){
                $newObject = "{\"".$match[1]."\":{";
            } else if($match[1] == 'height') {
                $onPage = count($objects);
                $page = $this->findPageForField($pages, $onPage);
                $newObject .=  "\"". $match[1] . "\":".$match[2] . ",\"page\":$page}},";
                $objects[] = $newObject;
                $json .= $newObject;
            } else {
                $newObject .=  "\"". $match[1] . "\":".$match[2] . ",";
            }
        }

        $formatJSON = "[";

        foreach($objects as $field){
            $formatJSON .= $field;
        }

        $formatJSON = substr($formatJSON, 0, -1);

        $formatJSON .= "]";

        return $formatJSON;
    }

    public function getPagesWithFieldsCount(){
        $re = '/(\d*).* page (\d)/';
        $matches = [];
        $pages = [];

        preg_match_all($re, $this->string, $matches, PREG_SET_ORDER, 0);

        $previousPageCount = 0;
        foreach($matches as $pageInfo){
            if($pageInfo[1] != 0){
                $pages[$pageInfo[2]] = $pageInfo[1] + $previousPageCount;
                $previousPageCount+= $pageInfo[1];
            }
        }

        return $pages;
    }

    private function findPageForField($pages, $count){
        foreach($pages as $p => $page){
            if($count <= $page){
                return $p;
            }
        }
    }
}
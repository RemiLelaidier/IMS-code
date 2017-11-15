<?php
namespace App\Core\Generator;

use \PhpOffice\PhpWord\PhpWord;

/**
 * DocumentGenerator
 * 
 * Based on PhpWord (part of PhpOffice)
 * 
 */
class DocumentGenerator {

    public function __construct($data){
        $this->phpWord = new PhpWord();
        $this->model = $data;
        $this->document = null;
    }

    /**
     * Debug func, full document ceremony
     */
    public function generateConvention(){     
        $templatePath = __DIR__ . "/../../../../assets/convention_template.docx";   

        $this->document = $this->phpWord->loadTemplate($templatePath);
        $this->writeData();
        $this->save();
    }

    /**
     * Write instance data into document
     */
    private function writeData(){
        // Setting year
        $this->document->setValue("school_year", date('Y') . " - " . date('Y')+1);

        // TODO : Missing infos from Front
        $this->document->setValue("student_usage_name", " ");
        $this->document->setValue("internship_service", " ");
        $this->document->setValue("internship_hours", " ");
        $this->document->setValue("internship_hours_daysOrWeek", " ");

        // TODO : Calc
        $this->document->setValue("internship_duration", " ");
        $this->document->setValue("internship_daysOrMonth", " ");
        $this->document->setValue("internship_presence_days", " ");

        // Parsing structured data (reverse logic of MiConv.endCeremony() ^^)
        // foreach dancing \o/
        foreach($this->model as $section){
            $inputs = $section['inputs'];
            $addresses = $section['addresses'];
            $dropdowns = $section['dropdowns'];
            $textareas = $section['textareas'];

            foreach($inputs as $input){
                $this->document->setValue($input['id'], $input['value']);
            }

            foreach($addresses as $address){
                $this->document->setValue($address['id'], $address['value']);
            }

            foreach($dropdowns as $dropdown){
                $this->document->setValue($dropdown['id'], $dropdown['value']);
            }

            foreach($textareas as $textarea){
                $this->document->setValue($textarea['id'], $textarea['value']);
            }
        }
    }

    /**
     * TODO
     * Save our document on?
     * Send by mail?
     * @return string path to edited document
     */
    private function save():string {
        $editedPath = __DIR__ . "/../../../../assets/edited.docx";

        // Todo : define final path
        $this->document->saveAs($editedPath);

        return $editedPath;   
    }

}
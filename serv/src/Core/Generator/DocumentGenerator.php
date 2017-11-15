<?php
namespace App\Core\Generator;

use App\Security\Exception\TemplateNotFoundException;
use PhpOffice\PhpWord\Exception\Exception;
use \PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;

/**
 * DocumentGenerator
 * 
 * Based on PhpWord (part of PhpOffice)
 * 
 */
class DocumentGenerator {

    /**
     * @param array $data
     * @param string $template 
     * @param string $destPath
     */
    public function __construct($data, $template, $destPath){
        $this->phpWord = new PhpWord();
        $this->model = $data;
        $this->document = null;
        $this->template = $template;
        $this->destPath = $destPath;
        $this->templatePath =__DIR__ . "/../../../../assets/";
    }

    /**
     * Debug func, full document ceremony
     */
    public function generateConvention(){
        try {
            $this->document = new TemplateProcessor($this->templatePath . $this->template . ".docx");
        } catch (Exception $e) {
            throw new TemplateNotFoundException("Document template not found", $e);
        }
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
            $inputs = [];
            $dropdowns = [];
            $addresses = [];
            $textareas = [];

            if(array_key_exists('inputs', $section))
                $inputs = $section['inputs'];

            if(array_key_exists('addresses', $section))
                $addresses = $section['addresses'];

            if(array_key_exists('dropdowns', $section))
                $dropdowns = $section['dropdowns'];

            if(array_key_exists('textareas', $section))
                $textareas = $section['textareas'];

            foreach($inputs as $input){
                if(array_key_exists('value', $input))
                    $this->document->setValue($input['id'], $input['value']);
            }

            foreach($addresses as $address){
                if(array_key_exists('value', $address))
                    $this->document->setValue($address['id'], $address['value']);
            }

            foreach($dropdowns as $dropdown){
                if(array_key_exists('value', $dropdown))
                    $this->document->setValue($dropdown['id'], $dropdown['value']);
            }

            foreach($textareas as $textarea){
                if(array_key_exists('value', $textarea))
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
<?php
namespace App\Core\Generator;

use \PhpOffice\PhpWord\PhpWord;

class DocumentGenerator {

    public function __construct($data){
        $this->phpWord = new PhpWord();
        $this->model = $data;
        $this->document = null;
    }

    public function generateConventionFrom(){     
        $templatePath = __DIR__ . "/../../../../assets/convention_template.docx";   

        $this->document = $this->phpWord->loadTemplate($templatePath);
        $this->writeData();
        $this->save();
    }

    private function writeData(){
        foreach($this->model as $section){
            $inputs = $section['inputs'];
            $addresses = $section['addresses'];
            $dropdowns = $section['dropdowns'];

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

    private function save(){
        $this->document->saveAs(__DIR__ . "/../../../../assets/edited.docx");        
    }

}
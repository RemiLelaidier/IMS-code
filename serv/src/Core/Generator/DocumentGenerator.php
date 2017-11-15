<?php
namespace App\Core\Generator;

use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Cocur\Slugify\Slugify;

use App\Security\Exception\TemplateNotFoundException;

/**
 * DocumentGenerator
 * 
 * Based on PhpWord (part of PhpOffice)
 *
 */
class DocumentGenerator {

    /**
     * @var PhpWord phpWord instance
     */
    private $phpWord;

    /**
     * @var array values to write in template
     */
    private $model;

    /**
     * @var array extra values to write in template
     */
    private $extras;

    /**
     * @var string template name
     */
    private $template;

    /**
     * @var TemplateProcessor $document
     */
    private $document;

    /**
     * @var string output filename
     */
    private $filename;

    /**
     * @var string destination bucket
     */
    private $bucket;

    /**
     * @param array  $data
     * @param string $template
     * @param string $filename
     */
    public function __construct(array $data, string $template, string $filename){
        $this->phpWord = new PhpWord();
        $this->model = $data;
        $this->document = null;
        $this->template = $template;
        $this->filename = $filename;
        $this->bucket =__DIR__ . "/../../../../assets/";
    }

    /**
     * Write instance data into document
     */
    private function writeData(){
        foreach($this->extras as $key => $extra){
            $this->document->setValue($key, $extra);
        }

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
     * Save current document on disk
     *
     * @return string path to edited document
     */
    private function save():string {
        $basePath = dirname(dirname(dirname(dirname(__DIR__))));

        $slugify = new Slugify();
        $this->filename = $slugify->slugify($this->filename);

        $output = $basePath . "/assets/$this->filename.docx";

        $this->document->saveAs($output);

        return $output;
    }

    /**
     * Write in document and save on disk
     *
     */
    public function writeAndSave(){
        try {
            $this->document = new TemplateProcessor($this->bucket . $this->template . ".docx");
        } catch (Exception $e) {
            throw new TemplateNotFoundException("Document template not found", $e);
        }
        $this->writeData();
        $this->save();
    }

    /**
     * @param array $extras
     */
    public function setExtras (array $extras) {

        $this->extras = $extras;
    }

}
<?php
namespace App\Core\Generator;

use App\Security\Exception\GenericException;

use Dompdf\Dompdf;
use PhpOffice\PhpWord\Exception\Exception;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\TemplateProcessor;
use Cocur\Slugify\Slugify;


use App\Security\Exception\TemplateNotFoundException;
use PhpOffice\PhpWord\Writer\HTML;

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
     * @var string path to edited file
     */
    private $edited;

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
        $this->edited = null;
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
        $this->edited = $this->save();

        try {
            $this->saveAsPDF();
        } catch (Exception $e) {
            throw new GenericException("Error while saving as PDF", $e);
        }
    }

    /**
     * @param array $extras
     */
    public function setExtras (array $extras) {

        $this->extras = $extras;
    }

    /**
     *
     * @return string path to PDF writer
     *
     */
    public function findPdfWriter(){
        $directory = __FILE__;
        $root = null;

        // If not found and dir not root..root?
        while(is_null($root) && $directory != '/'){
            $directory = dirname($directory);
            $composerConfig = $directory . '/composer.json';

            if(file_exists($composerConfig))
                $root = $directory;

        }

        return $root ."/vendor/dompdf";
    }

    /**
     * @throws Exception
     */
    public function saveAsPDF(){
        if(!$this->edited)
            throw new Exception("Trying to save as pdf without editing before");

        $temp = IOFactory::load($this->edited);

        /**
         * @var HTML $html
         */
        $html = IOFactory::createWriter($temp, 'HTML');
        $writer = new Dompdf();
        $writer->setPaper('A4', 'portrait');

        $pdfPath = dirname($this->edited) . "/$this->filename.pdf";

        $writer->loadHtml($html->getContent());

        file_put_contents($pdfPath, $writer->output());
    }

}
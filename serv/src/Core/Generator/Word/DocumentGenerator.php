<?php
namespace App\Core\Generator\Word;

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
        $this->edited = null;
        $this->bucket = dirname(dirname(getcwd())) . "/assets/";
    }

    /**
     * Write instance data into document
     */
    private function writeData(){

        foreach ($this->model as $value){
            $this->document->setValue($value->getId(), $value->getValue());
        }

    }

    /**
     * Write in document and save on disk
     *
     * @param string $outputDir
     */
    public function writeAndSave(string $outputDir){
        $dest = $this->bucket . $this->template . ".docx";
        try {
            $this->document = new TemplateProcessor($dest);
        } catch (Exception $e) {
            throw new TemplateNotFoundException("Document template not found", $e);
        }

        $this->writeData();
        $this->edited = $this->save($outputDir);
    }

    /**
     * Save current document on disk
     *
     * @param string $atPath
     *
     * @return string path to edited document
     */
    private function save(string $atPath) : string {
        $slugify = new Slugify();
        $this->filename = $slugify->slugify($this->filename);

        $this->document->saveAs($atPath . $this->filename . ".docx");

        return $atPath;
    }

}
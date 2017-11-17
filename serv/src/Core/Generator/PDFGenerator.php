<?php

namespace App\Core\Generator;

use FPDI;
use FPDF;

class PDFGenerator {

    /**
     * @var FPDF $fpdf instance
     */
    private $fpdf;

    /**
     * @throws \Exception
     */
    public function start(){
        opcache_reset();

        $this->fpdf = new FPDF('P', 'pt', 'A4');

        $this->fpdf->SetMargins(10, 10, 10);

        $this->fpdf->SetAutoPageBreak(true, 0);

        $this->fpdf->AddPage();

        $this->fpdf->AliasNbPages();
        $this->fpdf->SetFont('Arial', 'B', '8');

        // Fields 
        $fields = $this->findBase() . "/assets/convention/fields.json";
        $conventionFields = json_decode(file_get_contents($fields), true);

        // Data
        $data = $this->findBase() . "/assets/convention/sample.json";
        $fakeData = json_decode(file_get_contents($data), true);

        // Mapping our fake data
        $mappedData = [];
        foreach($fakeData as $data){
            if(array_key_exists('inputs', $data)){
                foreach($data['inputs'] as $input){
                    $mappedData[$input['id']] = $input['value'];
                }
            }
            if(array_key_exists('dropdowns', $data)){
                foreach($data['dropdowns'] as $dropdown){
                    $mappedData[$dropdown['id']] = $dropdown['value'];
                }
            }
            if(array_key_exists('addresses', $data)){
                foreach($data['addresses'] as $address){
                    $mappedData[$address['id']] = $address['value'];
                }
            }
        }

        // TODO : Need to debug width + ReverseYAxis correlation + append data
        // TODO : Handle many pages ->addPage(); when needed -> process each page one by one

        // writing fields, if value not defined defaults to blank string
        $this->writeFields($conventionFields, $mappedData);

        // generated path
        $generated = $this->findBase() . "/assets/convention/generated/temp.pdf";
        $this->fpdf->Output("F", $generated, true);

        // merging original with our blank (only with values, not "background")
        $original = $this->findBase() . "/assets/convention/convention_compatibility.pdf";

        // our final merged path
        $merged = $this->findBase() . "/assets/convention/generated/merged.pdf";

        // merge original with our pdf
        $this->merge($original, $generated, $merged);

        // clean generated not merged
        unlink($generated);
    }

    /**
     * Write fields on current pdf with data
     * @param array $fields
     * @param array $data
     * 
     * @return void
     */
    public function writeFields($fields, $data){
        foreach($fields as $field){
            // Grabbing key / values
            $key = array_keys($field)[0];
            $values = array_values($field)[0];

            // if page is set
            if(array_key_exists('page', $values) && $values['page'] === 1){
                // Set with good coords system.
                $this->fpdf->SetXY($values['llx'], PDFHelper::reverseYAxis($values['lly']));
 
                // Write !
                if(!array_key_exists($key, $data))
                    $text = "";
                else
                    $text = $data[$key];

                $this->fpdf->Cell(192, 32, utf8_decode($text));
            }
        }
    }

    /**
     * Merge two PDF (page 1 doc A + page 1 doc B layered)
     * @param string $pdfA path of pdfA
     * 
     * @return Boolean
     * 
     * @throws \Exception
     */
    public function merge($pdfA, $pdfB, $dest){
        $pdf = new FPDI();

        $pdf->setSourceFile($pdfA);
        $pageA = $pdf->importPage(1, '/MediaBox');

        $pdf->setSourceFile($pdfB);
        $pageB = $pdf->importPage(1, '/MediaBox');

        $pdf->addPage();
        $pdf->useTemplate($pageA);
        $pdf->useTemplate($pageB);

        try {
            $pdf->Output("F", $dest, true);
        } catch (Exception $e){
            // Path not writable, probably
            return false;
        }

        return true;
    }

    /**
     * TODO : REMOVE
     * 
     * Debug func
     * @return string path to base api
     */
    public function findBase(){
        $directory = __FILE__;
        $root = null;

        // If not found and dir not root..root?
        while(is_null($root) && $directory != '/'){
            $directory = dirname($directory);
            $composerConfig = $directory . '/.watchi';

            if(file_exists($composerConfig))
                $root = $directory;

        }

        return $root;
    }

}
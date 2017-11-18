<?php

namespace App\Core\Generator;

use FPDI;
use FPDF;

/**
 * PDFGenerator
 * Fields are generated using : 
 * https://www.setasign.com/products/setapdf-core/demos/find-form-field-coordinates/
 * 
 * TODO : Need to regen fields.json with new form builded using Adobe Acrobat
 *        Handle many pages ->addPage(); when needed -> process each page one by one
 */
class PDFGenerator {

    /**
     * @var FPDF $fpdf instance
     */
    private $fpdf;

    /**
     * @var array $fields
     * 
     * Field example: (every unit is in Points, according to fpdf setting)
     * {
     *   "id": {
     *      "llx": 364.883, // Lower left x
     *      "lly": 475.455, // Lower left y
     *      "urx": 393.256,
     *      "ury": 491.348,
     *      "width": 28.373, // pt
     *      "height": 15.893, // pt
     *      "page": 1 
     *   } 
     * }
     * 
     */
    private $fields;

    /**
     * @var array $data : JSON object containing inputs, dropdowns and addresses to fill example in "assets/sample.json"
     */
    private $data;

    /**
     * Constructor
     * 
     * @param array $fields
     * @param array $data
     * 
     */
    public function __construct($fields, $data){
        $this->fields = $fields;
        $this->data   = $data;
    }

    /**
     * Start to generate using original, save to dest
     * @param string $formPath
     * @param string $dest
     * 
     * @throws \Exception
     * 
     */
    public function start($formPath, $dest){
        $this->fpdf = new FPDF('P', 'pt', 'A4');

        $this->fpdf->SetMargins(10, 10, 10);

        $this->fpdf->SetAutoPageBreak(true, 0);

        $this->fpdf->AddPage();

        $this->fpdf->AliasNbPages();
        $this->fpdf->SetFont('Arial', 'B', '8');

        // Mapping our fake data
        $mappedData = [];
        foreach($this->data as $data){
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

        // writing fields, if value not defined defaults to blank string
        $this->writeFields($this->fields, $mappedData);

        // generated path
        $generated = "tmp/temp.pdf";

        $this->fpdf->Output("F", $generated, true);

        // merge original with our pdf
        $this->merge($formPath, $generated, $dest);

        // clean generated not merged
        //unlink($generated);
    }

    /**
     * Write fields on current pdf with data
     * @param array $fields
     * @param array $data
     * 
     * @return void
     */
    public function writeFields($fields, $data){
        $currentPage = -1;

        foreach($fields as $field){
            // Grabbing key / values
            $key = array_keys($field)[0];
            $values = array_values($field)[0];

            // if page is set
            if(array_key_exists('page', $values)){
                if($values['page'] === -1){
                    $currentPage = $values['page'];
                } else if($currentPage == $values['page']-1){
                    $currentPage++;
                    $this->fpdf->AddPage();
                }
            }

            // Set with good coords system.
            $this->fpdf->SetXY($values['llx'], PDFHelper::reverseYAxis($values['lly']));
 
            // Write !
            if(!array_key_exists($key, $data))
                $text = "";
            else
                $text = $data[$key];

            // 20 is fpdf offset for new pages
            $offset = 20;
            $this->fpdf->Cell($values['width'], $values['height']+ $offset, utf8_decode($text));
        }
    }

    /**
     * Merge two PDF (doc A and over doc B)
     * @param string $pdfA path of pdfA
     * @param string $pdfB path of pdfB
     * @param string $dest 
     * 
     * @return Boolean
     * 
     * @throws \Exception
     */
    public function merge($pdfA, $pdfB, $dest){
        $pdf = new FPDI();
        $pdf->setSourceFile($pdfA);

        $pageCount = $pdf->currentParser->getPageCount();   

        for($i = 1; $i <= $pageCount; $i++){
            $pdf->addPage();   

            // Adding background pdf
            $pdf->setSourceFile($pdfA);            
            $pageA = $pdf->importPage($i, '/MediaBox');
            $pdf->useTemplate($pageA);

            // Looking for file B -> our generated pdf with data
            $pdf->setSourceFile($pdfB);

            // If page exists on it
            if($i <= $pdf->currentParser->getPageCount()){
                $pageB = $pdf->importPage($i, '/MediaBox');            
                $pdf->useTemplate($pageB);
            }
        }

        // Done.
        try {
            $pdf->Output("F", $dest, true);
        } catch (Exception $e){
            // Path not writable, probably
            return false;
        }

        return true;
    }

}
<?php

namespace App\Core\Generator;

use FPDI;
use FPDF;
use FPDM;
use PDFLib\PDFLib;

class PDFGenerator {

    /**
     * @throws \Exception
     */
    public function start(){
        $fields = array(
            'name'    => 'My name',
            'address' => 'My address',
            'city'    => 'My city',
            'phone'   => 'My phone number'
        );

        $fpdf = new FPDF("P", "pt", "A4");

        $fpdf->SetMargins(0, 0, 0);

        $fpdf->SetAutoPageBreak(true, 0);

        $fpdf->AddPage();
        $fpdf->AliasNbPages();
        $fpdf->SetFont('Arial', 'B');

        $template = $this->findBase() . "/assets/outTest.pdf";

        //$theReturnedFecha = PDFHelper::pdf2text($template, "@SCHOOLYEAR");

        $fpdf->SetXY(2.695, 0.17);

        $fpdf->Cell(190, 30, '2016-2017', 0, 0, 'L');

        $dest = $this->findBase() . "/assets/generateTry.pdf";
        $fpdf->Output("F", $dest, true);
        //$this->merge();
    }

    /**
     * @throws \Exception
     */
    public function merge(){
        $pdf = new FPDI();

        $pdf->setSourceFile($this->findBase() . "/assets/convention_form.pdf");
        $tplIdxA = $pdf->importPage(1, '/MediaBox');

        $pdf->setSourceFile($this->findBase() . "/assets/generateTry.pdf");
        $tplIdxB = $pdf->importPage(1, '/MediaBox');

        $pdf->addPage();
        // place the imported page of the first document:
        $pdf->useTemplate($tplIdxA);
        // place the imported page of the snd document:
        $pdf->useTemplate($tplIdxB);
        $dest = $this->findBase() . "/assets/generateTry.pdf";

        $pdf->Output("F", $dest, true);
    }

    /**
     *
     * @return string path to PDF writer
     *
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
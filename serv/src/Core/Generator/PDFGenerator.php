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

        $fpdf->AliasNbPages();
        $fpdf->SetFont('Arial', 'B');

        $fpdf->SetXY(99, 794);

        $fpdf->Cell(190, 488, 'This is where we have moved our XY position to', 0, 0, 'L');

        $dest = $this->findBase() . "/assets/generateTry.pdf";
        $fpdf->Output("F", $dest, true);
    }

    /**
     * @throws \Exception
     */
    public function merge(){
        $pdf = new FPDI();

        $pdf->setSourceFile("convention.pdf");
        $tplIdxA = $pdf->importPage(1, '/MediaBox');

        $pdf->setSourceFile("Another-Fantastic-Speaker.pdf");
        $tplIdxB = $pdf->importPage(1, '/MediaBox');

        $pdf->addPage();
        // place the imported page of the first document:
        $pdf->useTemplate($tplIdxA, 10, 10, 90);
        // place the imported page of the snd document:
        $pdf->useTemplate($tplIdxB, 100, 10, 90);

        $pdf->Output();
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
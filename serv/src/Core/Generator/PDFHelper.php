<?php

namespace App\Core\Generator;

/**
 * PDFHelper
 * 
 * @used in PDFHelper
 */
class PDFHelper {

    /**
     * As setasign extract gives me coords in reversed -> llx, lly.. Y isn't good
     * Let's convert it to Fpdf coords
     * 
     * TODO : Bugfix. It's only by chance I think
     * @return int $y inverted axis
     */
    public static function reverseYAxis(int $y){
        return 822 - $y;
    }

}
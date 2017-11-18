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
     * $pageSize - 20
     * @return int $y inverted axis
     */
    public static function reverseYAxis(int $pageSize, int $offset, int $y){
        // 822 is the page size in points - 20 (fpdf offset)
        return $pageSize - $offset - $y;
    }

}
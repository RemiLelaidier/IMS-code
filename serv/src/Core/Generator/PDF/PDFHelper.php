<?php

namespace App\Core\Generator\PDF;

/**
 * PDFHelper
 *
 */
class PDFHelper {

    /**
     * As setasign extract gives me coords in reversed -> llx, lly.. Y isn't good
     * Let's convert it to FPDF coords
     * 
     * @return int $y inverted axis
     */
    public static function reverseYAxis(int $pageSize, int $offset, int $y){
        // 841.890 is the page size in points - 20 (fpdf offset)
        return $pageSize - $offset - $y;
    }

}
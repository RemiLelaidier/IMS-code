<?php 

namespace App\Core\Generator\PDF;

/**
 * An Adobe PDF Form Field
 * 
 * Field example: (every unit is in Points, according to FPDF setting)
 * 
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
 */
class Field {
     /**
      * @var string $id : id of the field
      */
     private $id;

     /**
      * @var float $llx : lower left x
      */
     private $llx;

     /**
      * @var float $lly : lower left y
      */
     private $lly;

     /**
      * @var float $urx : upper right x
      */
     private $urx;

     /**
      * @var float $ury : upper right y
      */
     private $ury;

     /**
      * @var float $width
      */
     private $width;

     /**
      * @var float $height
      */
     private $height;

     /**
      * @var int $page
      */
     private $page;

    /**
     * @return string
     */
    public function getId (): string {

        return $this->id;
    }

    /**
     * @return float
     */
    public function getLlx (): float {

        return $this->llx;
    }

    /**
     * @return float
     */
    public function getLly (): float {

        return $this->lly;
    }

    /**
     * @return float
     */
    public function getUrx (): float {

        return $this->urx;
    }

    /**
     * @return float
     */
    public function getUry (): float {

        return $this->ury;
    }

    /**
     * @return float
     */
    public function getWidth (): float {

        return $this->width;
    }

    /**
     * @return float
     */
    public function getHeight (): float {

        return $this->height;
    }

    /**
     * @return int
     */
    public function getPage (): int {

        return $this->page;
    }

     /**
      * Convert an array to Field
      *
      * @return Field
      */
     public static function fieldFromArray(array $data) : Field {
        $field = new Field;
        $field->id = array_keys($data)[0];
        $values = $data[$field->id];

        if(!array_key_exists('page', $values))
            throw new \Exception("Field needs to have a page : $data");
        
        $field->llx = $values['llx'];
        $field->lly = $values['lly'];
        $field->urx = $values['urx'];
        $field->ury = $values['ury'];
        $field->height = $values['height'];
        $field->width = $values['width'];
        $field->page = $values['page'];

        return $field;
     }

     /**
      * Convert a Json field to Field
      *
      * @return Field
      */
     public static function fieldFromJSON(string $json) : Field {
        $field = json_encode($json);
        
        return self::fieldFromArray($field);
     }
}
<?php

namespace App\Core\Generator\Word;

/**
 * Class TemplateValue
 *
 * A simple PHPWord Template value
 *
 * @package App\Core\Generator\Word
 */
class TemplateValue {

    /**
     * @var string $id
     */
    private $id;

    /**
     * @var string $value
     */
    private $value;

    /**
     * TemplateValue constructor.
     *
     * @param string $id
     * @param string $value
     */
    public function __construct (string $id, string $value) {
        $this->id = $id;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getId () : string {

        return $this->id;
    }

    /**
     * @return string
     */
    public function getValue () : string {

        return $this->value;
    }

}
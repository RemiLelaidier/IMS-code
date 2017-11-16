<?php

namespace App\Security\Exception;

use Exception;
use RuntimeException;

class GenericException extends RuntimeException {

    public function __construct($message = 'Generic exception', Exception $previous = null)
    {
        parent::__construct($message, 42, $previous);
    }

}
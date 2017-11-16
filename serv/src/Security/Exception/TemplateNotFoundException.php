<?php

namespace App\Security\Exception;

use Exception;
use RuntimeException;

class TemplateNotFoundException extends RuntimeException {

    public function __construct($message = 'Document template not found.', Exception $previous = null)
    {
        parent::__construct($message, 404, $previous);
    }

}
<?php

namespace App\Exception;

use RuntimeException;

class UnCorrectDateForRentException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Uncorrect dates! Please, check dates!', );
    }

}
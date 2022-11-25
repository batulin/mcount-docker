<?php

namespace App\Exception;

use RuntimeException;

class SelectedPlacesIsBusyOnThisTimeException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Selected places are busy in this time! Pleasw check and select other!', );
    }

}
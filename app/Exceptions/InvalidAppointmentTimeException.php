<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Debug\ShouldntReport;

class InvalidAppointmentTimeException extends Exception implements ShouldntReport
{
    //
}

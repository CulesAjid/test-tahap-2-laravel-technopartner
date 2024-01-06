<?php

namespace App\Traits;

trait FormatNumber
{
    protected function formatDecimal($number, $digit){
        return floatval(number_format($number, $digit, '.', ''));
    }
}

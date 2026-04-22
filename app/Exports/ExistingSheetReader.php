<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\ToArray;

class ExistingSheetReader implements ToArray
{
    public function array(array $array)
    {
        return $array;
    }
}
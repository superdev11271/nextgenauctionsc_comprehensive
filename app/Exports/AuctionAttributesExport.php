<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AuctionAttributesExport implements FromArray, WithHeadings
{
    protected $fields;

    public function __construct(array $fields)
    {
        $this->fields = $fields;
    }

    // Define the data to be written to the sheet (initially empty)
    public function array(): array
    {
        return [

        ];
    }

    public function headings(): array
    {
        return $this->fields;
    }

}


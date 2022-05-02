<?php


namespace App\Exports;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;


class MenuExport implements FromArray, WithHeadings
{
    protected $dishes;

    public function __construct(array $dishes)
    {
        $this->dishes = $dishes;
    }

    public function array(): array
    {
        return $this->dishes;
    }

    public function headings(): array
    {
        if (isset($this->dishes[0]))  {
            return array_keys($this->dishes[0]);
        }   else {
            return [];
        }
    }
}

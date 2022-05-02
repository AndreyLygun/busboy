<?php

namespace App\Imports;

use App\Models\Dish;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;

class MenuImport implements ToCollection, WithHeadingRow
{
    /**
    * @param Collection $rows
    */
    use Importable;
    public function headingRow(): int
    {
        return 2;
    }

    public function collection(Collection $rows)
    {
    }

    public function makeDish($row) {
        $dish =['fullname' => $row[2],
                'shortname' => $row[3],
                'category_id' => $row[1],
                'description' => $row[4],
                'alias' => '',
                'hide' => $row[13],
                'article' => $row[5],
                'photo' => $row[11],
                'options' => $row[12],
                'price' => $row[7],
                'out_price' => $row[7],
                'change_price' => $row[8],
                'hall' => 1,
                'pickup' => 1,
                'delivery' => 1,
                'size' => $row[6],
                'kbju' => $row[10],
                'recomendation' => $row[14],
                'timing' => $row[15],
                'special' => $row[14],
            ];
        return $dish;
    }
}

<?php

namespace App\Imports;

use App\Models\Accountinfo;
use Maatwebsite\Excel\Concerns\ToModel;

class ExcelImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Accountinfo([
            'username' => $row[0],
            'name' => $row[1],
            'gender' => $row[2],
            'birthday' => $row[3],
            'email' => $row[4],
            'note' => $row[5]
        ]);
    }
}

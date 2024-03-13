<?php

namespace App\Imports;

use App\Models\ScamBroker;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportUser implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new ScamBroker([
            //
        ]);
    }
}

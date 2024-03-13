<?php

namespace App\Exports;

use App\Models\ScamBroker;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExportUser implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ScamBroker::all();
    }
}

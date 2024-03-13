<?php

namespace App\Imports;

use App\Models\ScamBroker;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
// class ImportScamBroker implements ToCollection, SkipsEmptyRows
class ImportScamBroker implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
            return new ScamBroker([
                'name' => $row['name'],
                'body' => $row['data'],
            ]);
    }
}

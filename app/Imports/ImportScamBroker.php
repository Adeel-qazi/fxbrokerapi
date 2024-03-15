<?php

namespace App\Imports;

use App\Models\ScamBroker;
use App\Models\Point;
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
        $brokerName = $row['name'];
        if (!empty($brokerName)) {
            # code...
        $pointDescriptions = explode('[',$row['data']); 
        // dd($pointDescriptions);
        // $pointDescriptions = $row['data']; 
        
        $broker = ScamBroker::firstOrCreate(['name' => $brokerName]);
        // dd($broker);
        
        foreach ($pointDescriptions as $pointDescription) {
            $pointDescription = trim($pointDescription);
            
            $point = Point::firstOrCreate(['description' => $pointDescription,'scambroker_id' => $broker->id]);

            $broker->points()->save($point);
        }

        // Return null to indicate that no model instance is returned
        return null;
    }

            // return new ScamBroker([
            //     'name' => $row['name'],
            //     'body' => $row['data'],
            // ]);
    }
}

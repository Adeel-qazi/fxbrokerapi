<?php

namespace App\Imports;

use App\Models\Broker;
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
        $pointDescriptions = explode('[',$row['data']); 
        // $broker = Broker::firstOrCreate(['name' => $brokerName]);   new import along broker model
        $broker = ScamBroker::firstOrCreate(['name' => $brokerName]);
        
            foreach ($pointDescriptions as $pointDescription) {
                $pointDescription = trim($pointDescription);
                
                $point = Point::firstOrCreate(['description' => $pointDescription,'scambroker_id' => $broker->id]);

                $broker->points()->save($point);
            }

            return null;
        }

    }
}

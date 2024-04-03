<?php

namespace App\Imports;

use App\Models\Broker;
use App\Models\Level;
use App\Models\Scambroker;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportBrokerLevel implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $brokerName = $row['name'];
        if (!empty($brokerName)) {
            // Explode the string into an array
            $brokerLevels = explode('[', $row['data']);
            $brokerPoints = explode('[', $row['points']); // Assuming points are separated by bracket
            
           
            $broker = Broker::firstOrCreate(['name' => $brokerName]);
            
                foreach ($brokerPoints as $brokerPoint) {
                    $brokerPoint = trim($brokerPoint);
            
                    foreach ($brokerLevels as $brokerLevel) {
                        
                        if (!empty($brokerLevel)) {
                            $brokerLevel = trim($brokerLevel);
                            $level = Level::firstOrCreate(['point' => $brokerLevel, 'name' => $brokerPoint, 'broker_id' => $broker->id]);
                            $broker->levels()->save($level);
                        }
                    }

                }
                return null;


            
        }
        
        
    }


}

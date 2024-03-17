<?php

namespace App\Http\Controllers;

use App\Exports\ExportScamBroker;
use App\Imports\ImportScamBroker;
use App\Models\Broker;
use App\Models\Comparebroker;
use App\Models\FeeData;
use App\Models\HighestData;
use App\Models\ScamBroker;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Validator;
use File;
use App\Traits\ImageUploadTrait;
use Illuminate\Support\Str;
use App\Models\Image;
use Maatwebsite\Excel\Facades\Excel;





class ApiDataController extends Controller
{
    use ImageUploadTrait;

    public function store(Request $request)
    {
        $brokerDAta = file_get_contents(public_path("Json_Data/AllReaviews.json"));
        $feeData = file_get_contents(public_path("Json_Data/FeeCallcultor.json"));
        $compareBroker = file_get_contents(public_path("Json_Data/NewComparedata.json"));
        $highest = file_get_contents(public_path("Json_Data/HigestData.json"));
        
        // Import Excel file
        $scamBroker = $request->file('file')->store('files');
        $excelFullPath = storage_path("app/{$scamBroker}");
        
        Excel::import(new ImportScamBroker, $excelFullPath);



        $folderPath = public_path('Json_Data/brokerlogoclient');

        if ($brokerDAta === null || $feeData === null || $compareBroker === null || $highest === null || $folderPath === null) {
            return response()->json(['error' => 'Failed to fetch data.'], 500);
        }

        $files = File::allfiles($folderPath);
        foreach ($files as $file) {
            if ($file->isFile()) {
                $filename = pathinfo($file->getFilename(), PATHINFO_FILENAME);
                // $filename2 = rtrim(str_replace(['logo','2'], '', $filename));

                $extension = $file->getExtension();

                $filePath = asset('Json_Data/brokerlogoclient/' . $filename . '.' . $extension);
                Image::create([
                    'filename' => $filename,
                    'path' => $filePath,
                ]);

            }
        }
        $brokerDAta = str_replace("\r\n", "", $brokerDAta);
        $highestData = str_replace("\r\n", "", $highest);
        $decodedBrokers = json_decode($brokerDAta, true)['BrokerData'];
        $decodedFees = json_decode($feeData, true)['feeData'];
        $decodedCompares = json_decode($compareBroker, true)['BrokerDatas'];
        $decodedHighests = json_decode($highestData, true)['BrokerData'];



        $brokerDataList = [];
        $feeDataList = [];
        $compareDataList = [];
        $highestDataList = [];

        foreach ($decodedBrokers as $decodedData) {
            $broker = new Broker;
            $broker->name = $decodedData['Name'];
            $broker->country = json_encode($decodedData['Country'] ?? null);
            $broker->url = $decodedData['url'];
            $broker->ratting = $decodedData['ratting'];
            $broker->lose = $decodedData['lose'];
            $broker->path = $decodedData['path'] ?? null;
            $broker->min_deposit = $decodedData['mindeposit'];
            $broker->max_leverage = $decodedData['maxLeverge'];
            $broker->platform = $decodedData['platform'];
            $broker->broker_img = $decodedData['brokerimg'];
            $broker->recommended = $decodedData['Recommended'] ?? false;
            $broker->save();

            $brokerDataList[] = $broker->toArray();
        }


        foreach ($decodedHighests as $decodedData) {
            $highest = new HighestData;
            $highest->name = $decodedData['Name'];
            $highest->country = json_encode($decodedData['Country'] ?? null);
            $highest->url = $decodedData['url'];
            $highest->ratting = $decodedData['ratting'];
            $highest->lose = $decodedData['lose'];
            $highest->path = $decodedData['path'] ?? null;
            $highest->min_deposit = $decodedData['mindeposit'];
            $highest->max_leverage = $decodedData['maxLeverge'];
            $highest->platform = $decodedData['platform'];
            $highest->broker_img = $decodedData['brokerimg'];
            $highest->recommended = $decodedData['Recommended'] ?? false;
            $highest->save();

            $highestDataList[] = $highest->toArray();
        }


        foreach ($decodedCompares as $decodedData) {
            $compareBroker = new Comparebroker;
            $compareBroker->brokername = $decodedData['BrokerName'];
            $compareBroker->country = json_encode($decodedData['Country'] ?? null);
            $compareBroker->lose = $decodedData['lose'];
            $compareBroker->url = $decodedData['url'];
            $compareBroker->score = $decodedData['score'];
            $compareBroker->available = $decodedData['Avaiable'];
            $compareBroker->popularity = $decodedData['popularity'] ?? null;
            $compareBroker->updated = $decodedData['Updated'];
            $compareBroker->img = $decodedData['img'];
            $compareBroker->tradingfees = json_encode($decodedData['tradingfees']);
            $compareBroker->nontradingfees = json_encode($decodedData['Nontradingfees']);
            $compareBroker->safety = json_encode($decodedData['Safety']);
            $compareBroker->depositandwithdrawal = json_encode($decodedData['DepositandWithdrawal']);
            $compareBroker->platformandexperience = json_encode($decodedData['PlatformandExperience']);

            $compareBroker->save();

            $compareDataList[] = $compareBroker->toArray();
        }



        foreach ($decodedFees as $decodedData) {
            $fee = new FeeData;
            $fee->broker = $decodedData['broker'];
            $fee->type = $decodedData['type'] ?? null;
            $fee->image = $decodedData['img'];
            $fee->lose = $decodedData['lose'];
            $fee->link = $decodedData['link'];
            $fee->country = json_encode($decodedData['Country'] ?? null);
            $fee->eurusd = json_encode($decodedData['EURUSD'] ?? null);
            $fee->usdjpy = json_encode($decodedData['USDJPY'] ?? null);
            $fee->gbpusd = json_encode($decodedData['GBPUSD'] ?? null);
            $fee->usdcad = json_encode($decodedData['USDCAD'] ?? null);
            $fee->audusd = json_encode($decodedData['AUDUSD'] ?? null);
            $fee->nzdusd = json_encode($decodedData['NZDUSD'] ?? null);
            $fee->eurjpy = json_encode($decodedData['EURJPY'] ?? null);
            $fee->gbpjpy = json_encode($decodedData['GBPJPY'] ?? null);
            $fee->usdchf = json_encode($decodedData['USDCHF'] ?? null);
            $fee->eurgbp = json_encode($decodedData['EURGBP'] ?? null);
            $fee->nzdjpy = json_encode($decodedData['NZDJPY'] ?? null);
            $fee->audjpy = json_encode($decodedData['AUDJPY'] ?? null);
            $fee->gold = json_encode($decodedData['GOLD'] ?? null);
            $fee->save();

            $feeDataList[] = $fee->toArray();
        }


        return response()->json([
            'dataBroker' => $brokerDataList,
            'dataCompareBroker' => $compareDataList,
            'dataFee' => $feeDataList,
            'highestData' => $highestDataList,
            'image' => 'Images uploaded successfully.',
        ]);

    }



    public function fetchBroker()
    {

        // $imageData = Image::all();

        // $images = [];
        // foreach ($imageData as $image) {
        //     $images[$image->filename] = $image->path;
        // }

        $brokerName = request()->name;

        $brokerRecords = Broker::query();
        if ($brokerName)
            $brokerRecords->where('name', $brokerName);

        $brokerRecords = $brokerRecords->with('image')->get();
      

        // foreach ($brokerRecords as &$broker) {
        //     $brokerName = $broker['name'];

        //     if (array_key_exists($brokerName, $images)) {
        //         $broker['broker_img'] = $images[$brokerName];
        //     } else {
        //         $broker['broker_img'] = 'default_path'; // Change 'default_path' to your desired default value
        //     }
        // }


        $brokerRecords = $brokerRecords->map(function ($record) {
            if($record->image){
              $record['img'] = $record->image->path?? 'null';
              unset($record->image);
            }
            $record->country = json_decode($record->country, true);
            return $record;
        });

        // $data = [];
        // if ($name) {
        //     foreach ($brokerRecords as $broker) {
        //         $data[] = [
        //             'broker_name' => $broker->name,
        //             'broker_country' => $broker->country,
        //         ];
        //     }
        //     return response()->json(['status' => true, 'message' => 'Broker retrieved successfully', 'data' => $data], 200);
        // }
        return response()->json(['status' => true, 'message' => 'Broker retrieved successfully', 'data' => $brokerRecords], 200);


    }


    public function fetchHighest()
    {
        $highestName = request()->name;

        $highestRecords = HighestData::query();
        if ($highestName)
            $highestRecords->where('name', $highestName);
        $highestRecords = $highestRecords->with('image')->get();

        $highestRecords = $highestRecords->map(function ($record) {
            if($record->image){
                $record['img'] = $record->image->path?? 'null';
                unset($record->image);
              }
              $record->country = json_decode($record->country, true);
              return $record;
         
        });
    
        return response()->json(['status' => true, 'message' => 'Highest Broker retrieved successfully', 'data' => $highestRecords], 200);

    }




    public function fetchCompareBroker()
    {
        $brokername = request()->brokername;

        $compareBrokerRecords = Comparebroker::query();
        if ($brokername)
            $compareBrokerRecords->where('brokername', $brokername);
          
            $compareBrokerRecords = $compareBrokerRecords->with('image')->get();

        $compareBrokerRecords = $compareBrokerRecords->map(function ($record) {

            if($record->image){
                $record['img'] = $record->image->path?? 'null';
                unset($record->image);
              }
            
            $record->country = json_decode($record->country, true);
            $record->tradingfees = json_decode($record->tradingfees, true);
            $record->nontradingfees = json_decode($record->nontradingfees, true);
            $record->safety = json_decode($record->safety, true);
            $record->depositandwithdrawal = json_decode($record->depositandwithdrawal, true);
            $record->platformandexperience = json_decode($record->platformandexperience, true);
            return $record;
        });

        return response()->json(['status' => true, 'message' => 'CompareBroker retrieved successfully', 'data' => $compareBrokerRecords], 200);

    }




    public function fetchFee()
    {
        $brokerName = request()->broker;

        $feeRecords = FeeData::query();
        if ($brokerName)
            $feeRecords->where('broker', $brokerName);
        $feeRecords = $feeRecords->with('imageFee')->get();




        $feeRecords = $feeRecords->map(function ($record) {
            if($record->imageFee){
                $record['img'] = $record->imageFee->path?? 'null';
                unset($record->imageFee);
              }
            $record->country = json_decode($record->country, true);
            $record->eurusd = json_decode($record->eurusd, true);
            $record->usdjpy = json_decode($record->usdjpy, true);
            $record->gbpusd = json_decode($record->gbpusd, true);
            $record->usdcad = json_decode($record->usdcad, true);
            $record->audusd = json_decode($record->audusd, true);
            $record->nzdusd = json_decode($record->nzdusd, true);
            $record->eurjpy = json_decode($record->eurjpy, true);
            $record->gbpjpy = json_decode($record->gbpjpy, true);
            $record->usdchf = json_decode($record->usdchf, true);
            $record->eurgbp = json_decode($record->eurgbp, true);
            $record->nzdjpy = json_decode($record->nzdjpy, true);
            $record->audjpy = json_decode($record->audjpy, true);
            $record->gold = json_decode($record->gold, true);
            return $record;
        });

        return response()->json(['status' => true, 'message' => 'Fee retrieved successfully', 'data' => $feeRecords], 200);

    }

  

    public function fetchScamBroker()
    {
        
        $scambroker = request()->name;

        $scamItems = ScamBroker::query();
        if ($scambroker)
            $scamItems->where('name', $scambroker);
        $scamItems = $scamItems->with('points', 'image', 'broker')->get();

       
            $scamItems = $scamItems->map(function ($item) {
                if($item->image){
                    $item['img'] = $item->image->path ?? 'null';  
                    unset($item->image);
                }
                if ($item->broker) {
                    $item['url'] = $item->broker->url;
                    $item['ratting'] = $item->broker->ratting;
                    $item['lose'] = $item->broker->lose;
                    $item['path'] = $item->broker->path;
                    $item['recommended'] = $item->broker->recommended;
                    unset($item->broker);
                }
                return $item;
            });


        return response()->json(['status' => true, 'message' => 'Scam Broker retrieved successfully', 'data' => $scamItems], 200);


    }


    public function fetchImages()
    {
        $imageData = Image::all();

        $images = [];
        if ($imageData) {
            foreach ($imageData as $image) { // Use a different name for the loop variable
                $images[] = [
                    'image-name' => $image->filename, // Use the loop variable
                    'path' => $image->path, // Use the loop variable
                ];
            }

            return response()->json(['status' => true, 'message' => 'Images retrieved successfully', 'data' => $images], 200);
        } else {
            return response()->json(['status' => false, 'message' => 'No images found', 'data' => []], 404);
        }
    }


    public function getCountryCodeFromApi()
    {
        $ipAddress = request()->ip();
        if ($ipAddress === '127.0.0.1')
            return response(['status' => false, 'error' => 'Accessing from local server will not provide country details'], 500);
        $client = new Client();

        try {
            $client = new Client;
            $response = $client->get('https://ipinfo.io/' . $ipAddress . '/json');
            $body = $response->getBody();
            $data = json_decode($body, true);
            return $data;

        } catch (\Exception $e) {
            return response()->json(['status' => false, 'message' => $e->getMessage()], 500);
        }
    }


}

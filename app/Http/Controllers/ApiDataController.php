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



        $name = request()->name;

        $brokerData = Broker::query();
        if ($name)
            $brokerData->where('name', $name);

        $brokerData = $brokerData->with('image')->get();
      

        // foreach ($brokerData as &$broker) {
        //     $brokerName = $broker['name'];

        //     if (array_key_exists($brokerName, $images)) {
        //         $broker['broker_img'] = $images[$brokerName];
        //     } else {
        //         $broker['broker_img'] = 'default_path'; // Change 'default_path' to your desired default value
        //     }
        // }


        $brokerData = $brokerData->map(function ($data) {
            $data->country = json_decode($data->country, true);
            return $data;
        });

        // $data = [];
        // if ($name) {
        //     foreach ($brokerData as $broker) {
        //         $data[] = [
        //             'broker_name' => $broker->name,
        //             'broker_country' => $broker->country,
        //         ];
        //     }
        //     return response()->json(['status' => true, 'message' => 'Broker retrieved successfully', 'data' => $data], 200);
        // }
        return response()->json(['status' => true, 'message' => 'Broker retrieved successfully', 'data' => $brokerData], 200);


    }


    public function fetchHighest()
    {
        $name = request()->name;

        // $imageData = Image::all();

        // $images = [];
        // foreach ($imageData as $image) {
        //     $images[$image->filename] = $image->path;
        // }


        $highestData = HighestData::query();
        if ($name)
            $highestData->where('name', $name);
        $highestData = $highestData->with('image')->get();


        // foreach ($highestData as &$highest) {
        //     $highestName = $highest['name'];

        //     if (array_key_exists($highestName, $images)) {
        //         $highest['broker_img'] = $images[$highestName];
        //     } else {
        //         $highest['broker_img'] = 'default_path'; // Change 'default_path' to your desired default value
        //     }
        // }


        $highestData = $highestData->map(function ($data) {
            $data->country = json_decode($data->country, true);
            return $data;
        });

        // $data = [];
        // if ($name) {
        //     foreach ($highestData as $highest) {
        //         $data[] = [
        //             'highest_name' => $highest->name,
        //             'highest_country' => $highest->country,
        //         ];
        //     }
        //     return response()->json(['status' => true, 'message' => 'Highest retrieved successfully', 'data' => $data], 200);
        // }
        return response()->json(['status' => true, 'message' => 'Highest retrieved successfully', 'data' => $highestData], 200);

    }




    public function fetchCompareBroker()
    {
        
        // $imageData = Image::all();
        
        // $images = [];
        // foreach ($imageData as $image) {
            //     $images[$image->filename] = $image->path;
            // }
            
            $brokername = request()->brokername;

        $compareBrokerData = Comparebroker::query();
        if ($brokername)
            $compareBrokerData->where('brokername', $brokername);
          
            $compareBrokerData = $compareBrokerData->with('image')->get();

            // return $compareBrokerData;

        // $compareBrokerData = $compareBrokerData->get();


        // foreach ($compareBrokerData as &$compareBroker) {
        //     $compareBrokerName = $compareBroker['brokername'];

        //     if (array_key_exists($compareBrokerName, $images)) {
        //         $compareBroker['img'] = $images[$compareBrokerName];
        //     } else {
        //         $compareBroker['img'] = 'default_path'; // Change 'default_path' to your desired default value
        //     }
        // }



        $compareBrokerData = $compareBrokerData->map(function ($data) {
            $data->country = json_decode($data->country, true);
            $data->tradingfees = json_decode($data->tradingfees, true);
            $data->nontradingfees = json_decode($data->nontradingfees, true);
            $data->safety = json_decode($data->safety, true);
            $data->depositandwithdrawal = json_decode($data->depositandwithdrawal, true);
            $data->platformandexperience = json_decode($data->platformandexperience, true);
            return $data;
        });



        // $data = [];
        // if ($brokername) {
        //     foreach ($compareBrokerData as $comBrokerData) {
        //         $data[] = [
        //             'name' => $comBrokerData->brokername,
        //             'country' => $comBrokerData->country,
        //             'lose' => $comBrokerData->lose,
        //             'img' => $comBrokerData->img,
        //         ];
        //     }
        //     return response()->json(['status' => true, 'message' => 'CompareBroker retrieved successfully', 'data' => $data], 200);
        // }
        return response()->json(['status' => true, 'message' => 'CompareBroker retrieved successfully', 'data' => $compareBrokerData], 200);

    }




    public function fetchFee()
    {
        $broker = request()->broker;

        // $imageData = Image::all();

        // $images = [];
        // foreach ($imageData as $image) {
        //     $images[$image->filename] = $image->path;
        // }

        $feeData = FeeData::query();
        if ($broker)
            $feeData->where('broker', $broker);
        $feeData = $feeData->with('image')->get();

        // foreach ($feeData as &$fee) {
        //     $feeName = $fee['broker'];

        //     if (array_key_exists($feeName, $images)) {

        //         $fee['image'] = $images[$feeName];
        //     } else {
        //         $fee['image'] = 'default_path'; // Change 'default_path' to your desired default value
        //     }
        // }




        $feeData = $feeData->map(function ($data) {
            $data->country = json_decode($data->country, true);
            $data->eurusd = json_decode($data->eurusd, true);
            $data->usdjpy = json_decode($data->usdjpy, true);
            $data->gbpusd = json_decode($data->gbpusd, true);
            $data->usdcad = json_decode($data->usdcad, true);
            $data->audusd = json_decode($data->audusd, true);
            $data->nzdusd = json_decode($data->nzdusd, true);
            $data->eurjpy = json_decode($data->eurjpy, true);
            $data->gbpjpy = json_decode($data->gbpjpy, true);
            $data->usdchf = json_decode($data->usdchf, true);
            $data->eurgbp = json_decode($data->eurgbp, true);
            $data->nzdjpy = json_decode($data->nzdjpy, true);
            $data->audjpy = json_decode($data->audjpy, true);
            $data->gold = json_decode($data->gold, true);
            return $data;
        });



        // $data = [];
        // if ($broker) {
        //     foreach ($feeData as $fee) {
        //         $data[] = [
        //             'fee_broker' => $fee->broker,
        //             'country' => $fee->country,
        //         ];
        //     }
        //     return response()->json(['status' => true, 'message' => 'Fee retrieved successfully', 'data' => $data], 200);
        // }
        return response()->json(['status' => true, 'message' => 'Fee retrieved successfully', 'data' => $feeData], 200);

    }

  

    public function fetchScamBroker()
    {

        $scambroker = request()->name;

        $scamData = ScamBroker::query();
        if ($scambroker)
            $scamData->where('name', $scambroker);
        $scamData = $scamData->with('points', 'image', 'broker')->get();


       
            $scamData = $scamData->map(function ($data) {
                if ($data->broker) {
                    $country = $data->broker->country;
                    $data->broker->country = json_decode($country, true);
                }
                return $data;
            });


        return response()->json(['status' => true, 'message' => 'Scam Broker retrieved successfully', 'data' => $scamData], 200);


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

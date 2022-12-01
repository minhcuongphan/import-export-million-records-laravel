<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesRequest;
use App\Jobs\SalesCsvProcess;
use App\Models\Sales;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        $sales = Sales::paginate(20);
        return view('sales.index', compact('sales'));
    }

    public function store(SalesRequest $request)
    {
        //get data from the uploaded file
        //$uploadedFile = request()->file('mycsv');
        //$data =  file($uploadedFile->getPathName() . '/csvfiles/' . $uploadedFile->getClientOriginalName());
        $data = file($request->file('mycsv'));
        $chunks = array_chunk($data, 1000);
        $header = [];
        $batch  = Bus::batch([])->dispatch();

        foreach ($chunks as $key => $chunk) {
            //push chunk data to a new csv file
            $data = array_map('str_getcsv', $chunk);
            if ($key === 0) {
                $header = $data[0];
                unset($data[0]);
            }
            
            $batch->add(new SalesCsvProcess($data, $header));
        }

        return response()->json(['success'   => true]);
    }

    public function getBatch()
    {
        $batchId = request('id');
        return Bus::findBatch($batchId);
    }

    public function batchInProgress()
    {
        $batches = DB::table('job_batches')->where('pending_jobs', '>', 0)->get();
        if (count($batches) > 0) {
            return Bus::findBatch($batches[0]->id);
        }

        return [];
   }
}

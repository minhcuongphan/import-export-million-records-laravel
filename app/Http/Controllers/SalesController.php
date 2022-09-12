<?php

namespace App\Http\Controllers;

use App\Jobs\SalesCsvProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class SalesController extends Controller
{
    public function index()
    {
        return view('upload-file');
    }

    public function store()
    {
        if (request()->has('mycsv')) {
            $uploadedFile = request()->file('mycsv');
            //get data from the uploaded file
            $data =  file($uploadedFile->path() . '\\csvfiles\\' . $uploadedFile->getClientOriginalName());
            // Chunking file
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

            return $batch;
        }

        return 'please upload file';
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

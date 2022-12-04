<?php

namespace App\Http\Controllers;

use App\Http\Requests\SalesRequest;
use App\Jobs\SalesCsvProcess;
use App\Models\Sales;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\Facades\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

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

    public function download()
    {
        $this->exportByFastExcel();    
    }

    private function salesGenerator($sales)
    {
        foreach ($sales as $sale) {
            yield $sale;
        }
    }

    private function exportByFastExcel()
    {
        return FastExcel::data($this->salesGenerator(Sales::cursor()))->download('salesCsvFile.csv', function ($sale) {
            return [
                'ID'            => $sale->id,
                'Region'        => $sale->Region,
                'Country'       => $sale->Country,
            ];
        });
    }

    private function exportbByStreamedResponse()
    {
        $response = new StreamedResponse(function () {
            // Open output stream
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, [
                'ID',
                'Region', 
                'Country'
            ]);
            
            Sales::chunk(500000, function($sales) use($handle) {
                foreach ($this->salesGenerator($sales) as $sale) {
                    // Add a new row with data
                    fputcsv($handle, [
                      $sale->id,
                      $sale->Region,
                      $sale->Country
                    ]);
                }
            });

            // Close the output stream
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="salesCsvFile.csv"',
        ]);

        return $response;
    }

    private function exportbByStreamedResponsetTest()
    {
        $response = new StreamedResponse(function () {
            // Open output stream
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, [
                'ID',
                'Region', 
                'Country'
            ]);
            
            Sales::chunk(500000, function($sales) use($handle) {
                foreach ($this->salesGenerator($sales) as $sale) {
                    // Add a new row with data
                    fputcsv($handle, [
                      $sale->id,
                      $sale->Region,
                      $sale->Country
                    ]);
                }
            });

            // Close the output stream
            fclose($handle);
        }, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="salesCsvFile.csv"',
        ]);

        return $response;
    }
}

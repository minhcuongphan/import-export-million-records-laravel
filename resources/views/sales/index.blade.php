<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="_token" content="{{csrf_token()}}" />
    <title>Document</title>
    <link rel="stylesheet" href="{{ URL::asset('css/app.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="og-contianer">
        <h1 class="heading-line">Sales Data</h1>
        <div class="og-row" id="og-filters">
          <select class="" id="education" placeholder="Education">
            <option value="" selected="">Region</option>
          </select>
          <select class="" id="experience" placeholder="Experience">
            <option value="" selected="">Country</option>
          </select>
          <select class="" id="availability" placeholder="Availability">
            <option value="" selected="">Availability</option>
          </select>
          <select class="" id="relocation" placeholder="Relocation">
            <option value="" selected="">Relocation</option>
            <option value="Yes">Yes</option>
            <option value="No">No</option>
          </select>
          <select name="state" id="location">
            <option value="">Location</option>
            <option value="Andhra Pradesh">Andhra Pradesh</option>
          </select>
          <button class="btn btn-success h-100" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">import sales data</button>
          <button class="btn btn-success h-100 ms-3" data-bs-toggle="modal" data-bs-target="#exampleModal2">export sales data</button>
        </div>

        <div class="og-row og-li og-li-head">
          <div class="og-li-col og-li-col-1 text-center">#</div>
          <div class="og-li-col og-li-col-2">Region</div>
          <div class="og-li-col og-li-col-3 text-center">Country</div>
          <div class="og-li-col og-li-col-4 text-center">Item Type</div>
          <div class="og-li-col og-li-col-5 text-center">Total Revenue</div>
          <div class="og-li-col og-li-col-6 text-center">Total Cost</div>
          <div class="og-li-col og-li-col-7 text-center">Total Profit</div>
          <div class="og-li-col og-li-col-8 text-center">Created At</div>
        </div>

        @foreach ($sales as $sale)
            <div class="data-row og-row og-li Experienced Engineering 7.3 ready_to_hire Andhra Pradesh Yes">
                <div class="og-li-col og-li-col-1 text-center">{{ $sale['id'] }}</div>
                <div class="og-li-col og-li-col-2">{{ $sale['Region'] }}</div>
                <div class="og-li-col og-li-col-3 text-center">{{ $sale['Country'] }}</div>
                <div class="og-li-col og-li-col-4 text-center">{{ $sale['Item Type'] }}</div>
                <div class="og-li-col og-li-col-5 text-center">{{ $sale['Total Revenue'] }}</div>
                <div class="og-li-col og-li-col-6 text-center">{{ $sale['Total Cost'] }}</div>
                <div class="og-li-col og-li-col-7 text-center">{{ $sale['Total Profit'] }}</div>
                <div class="og-li-col og-li-col-8 text-center">{{ $sale['created_at'] }}</div>
            </div>
        @endforeach

        <div id="no-match" class="no-match og-li  text-center hide">Sorry, No Sales Matches your Criteria</div>

        <div class="d-flex justify-content-center mt-3">
            {!! $sales->links('vendor.pagination.custom') !!}
        </div>
      </div>

      <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLabel">Import Big Sales Data</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    @csrf
                    <div class="mb-3">
                    <label for="recipient-name" class="col-form-label">File:</label>
                    <input type="file" class="form-control" name="mycsv" id="mycsv" accept=".csv">
                    @if($errors->has('mycsv'))
                        <div class="text-danger">{{ $errors->first('mycsv') }}</div>
                    @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" onclick="uploadCsvFile()" class="btn btn-primary">Submit</button>
                    </div>
              </form>
            </div>
           
          </div>
        </div>
      </div>


      <!-- Modal 2 -->
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">Export Sales Data</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('download.csv') }}" method="GET">
                @csrf
                <div class="modal-body">
                    Are you sure to export all data to a csv file?
                </div>
                <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
            </div>
        </div>
        </div>
    </div>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" integrity="sha512-aVKKRRi/Q/YV+4mjoKBsE4x3H+BkegoM/em46NNlCqNTmUYADjBbeNefNxYV7giUp0VxICtqdrbqU7iVaeZNXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.js" integrity="sha512-CX7sDOp7UTAq+i1FYIlf9Uo27x4os+kGeoT7rgwvY+4dmjqV0IuE/Bl5hVsjnQPQiTOhAX1O2r2j5bjsFBvv/A==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    function uploadCsvFile() {
        let file = $('#mycsv')[0].files[0];
        if (!file) return;
        
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });

        let formData = new FormData();           
        formData.append("mycsv", file);

        $.ajax({
            type: 'POST',
            url: `{{ url('/sales') }}`,
            data: formData,
            cache : false,
            processData: false,
            contentType: false,
            success:function(data) {}
        });
    }
</script>
</html>
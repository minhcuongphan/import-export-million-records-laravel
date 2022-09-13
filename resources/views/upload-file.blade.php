<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Million Records</title>
</head>
<body>
<form action="/upload" method="post" enctype="multipart/form-data">
    @csrf
    <input type="file" name="mycsv" id="mycsv" accept=".csv">
    @if($errors->has('mycsv'))
        <div class="error">{{ $errors->first('mycsv') }}</div>
    @endif
    <input type="submit" value="Upload">
</form>
</body>
</html>

<!-- resources/views/try.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Try Blade View</title>
</head>
<body>
    <h1>Welcome to the Try Page</h1>
    <p>This is a test page for trying out routes and views in Laravel.</p>

    <form action="{{ url('/upload-image') }}" method="POST" enctype="multipart/form-data">
        @csrf <!-- This will generate the CSRF token -->
        <input type="file" name="image">
        <button type="submit">Upload Image</button>
    </form>
    
</body>
</html>

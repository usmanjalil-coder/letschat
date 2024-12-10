<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video Upload</title>
</head>
<body>
    <h1>Upload Video</h1>
    <form action="/videos" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="video" required>
        <button type="submit">Upload</button>
    </form>

    <h1>Uploaded Videos</h1>
    @foreach($videos as $video)
        <video width="320" height="240" controls>
            <source src="{{ asset('storage/' . $video->video_path) }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    @endforeach
</body>
</html>

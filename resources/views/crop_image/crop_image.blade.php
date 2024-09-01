<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Hello, world!</title>
  </head>
  <body>
    <form action="{{url('image/insert')}}" method="post" enctype="multipart/form-data">
@csrf
        <div class="mb-3">
            <label for="formFileMultiple" class="form-label">Multiple files input example</label>
            <input class="form-control" name="image" type="file" id="formFileMultiple" multiple>
          </div>
          <button class="btn btn-info" type="submit">Save</button>
    </form>

@foreach ($image as $image)
<img src="{{asset($image->image)}}" alt="" height="300" width="300" id="image">
<button class="btn btn-success" id="cropImgBtn">Crop</button>
@endforeach
<img src="" alt="" id="output" height="100" name="crop_image" width="100">


<script src="https://unpkg.com/cropperjs@1.5.12/dist/cropper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script>
   const image =document.getElementById('image');
   const cropper = new Cropper(image, {
        aspectRatio: 1, // Set to 0 for free aspect ratio
        viewMode: 0,
    });

//     document.getElementById('cropImgBtn').addEventListener('click', function() {
//     var croppedImage = cropper.getCroppedCanvas().toDataURL("image/png");
//     // This will log the base64 encoded string of the cropped image
//    var cropImage= document.getElementById('output').src=croppedImage;
//    console.log(cropImage);

// });

    </script>
    <script>
        document.getElementById('cropImgBtn').addEventListener('click', function() {
    var croppedImage = cropper.getCroppedCanvas().toDataURL("image/png");
    var cropImage= document.getElementById('output').src = croppedImage;
  var crop_image= croppedImage;
  $.ajax({
    url: '/save/crop', 
    method: 'POST',
    data: {
        crop_image: crop_image, // Ensure crop_image contains the base64 image data
        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for Laravel
    },
    success: function(data) {
        console.log(data); // Handle the successful response
    },
    error: function(xhr, status, error) {
        console.error('Error:', error); // Handle errors
    }
});

  });
    </script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  </body>
</html>

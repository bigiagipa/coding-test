@extends('layouts.app')

@section ('content')
   

    <div class="panel panel-primary">

      <div class="panel-heading"><h2>Laravel 5.7 image upload example - HDTuto.com</h2></div>

      <div class="panel-body">

   

        <div id="uploadedImage">
        
            @if ($message = Session::get('success'))

                <div class="alert alert-success alert-block">

                    <button type="button" class="close" data-dismiss="alert">×</button>

                        <strong>{{ $message }}</strong>

                </div>

                <img src="images/{{ Session::get('image') }}">
                
                <br/>

                <button class="btn btn-sm btn-info" type="button" onClick="manipulateImage('images/{{ Session::get('image') }}')">manipulate</button>
                
            @endif

                <img id="original-image" src="images/1591082157.jpg">
                
                <br/>

                <button class="btn btn-sm btn-info" type="button" onClick="manipulateImage('images/1591082157.jpg')">manipulate</button>
                <button class="btn btn-sm btn-secondary" type="button" onClick="manipulateImage2('images/1591082157.jpg')">manipulate 2</button>
                <button class="btn btn-sm btn-warning" type="button" onClick="manipulateImage3('images/1591082157.jpg')">manipulate 2</button>
                
        </div>
        
  

        @if (count($errors) > 0)

            <div class="alert alert-danger">

                <strong>Whoops!</strong> There were some problems with your input.

                <ul>

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

  

        <form action="{{ route('image.upload.post') }}" method="POST" enctype="multipart/form-data">

            @csrf

            <div class="row">

  

                <div class="col-md-6">

                    <input type="file" name="image" class="form-control">

                </div>

   

                <div class="col-md-6">

                    <button type="submit" class="btn btn-success">Upload</button>

                </div>

   

            </div>

        </form>

  

      </div>

    </div>
@endsection

@section('footer-js')
<script type="text/javascript">
    $(document).ready(function() {
        alert("Settings page was loaded");
    });

            // please scroll all the way down to set your image

        function manipulateImage(imgPath) {

            var img = new Image(),
                $canvas = $("<canvas>"),
                canvas = $canvas[0],
                context;

            var removeBlanks = function (imgWidth, imgHeight) {
                var imageData = context.getImageData(0, 0, imgWidth, imgHeight),
                    data = imageData.data,
                    getRBG = function(x, y) {
                        var offset = imgWidth * y + x;
                        return {
                            red:     data[offset * 4],
                            green:   data[offset * 4 + 1],
                            blue:    data[offset * 4 + 2],
                            opacity: data[offset * 4 + 3]
                        };
                    },
                    isWhite = function (rgb) {
                        // many images contain noise, as the white is not a pure #fff white
                        return rgb.red > 200 && rgb.green > 200 && rgb.blue > 200;
                    },
                    scanY = function (fromTop) {
                        var offset = fromTop ? 1 : -1;
                        
                        // loop through each row
                        for(var y = fromTop ? 0 : imgHeight - 1; fromTop ? (y < imgHeight) : (y > -1); y += offset) {
                            
                            // loop through each column
                            for(var x = 0; x < imgWidth; x++) {
                                var rgb = getRBG(x, y);
                                if (!isWhite(rgb)) {
                                    return y;                        
                                }      
                            }
                        }
                        return null; // all image is white
                    },
                    scanX = function (fromLeft) {
                        var offset = fromLeft? 1 : -1;
                        
                        // loop through each column
                        for(var x = fromLeft ? 0 : imgWidth - 1; fromLeft ? (x < imgWidth) : (x > -1); x += offset) {
                            
                            // loop through each row
                            for(var y = 0; y < imgHeight; y++) {
                                var rgb = getRBG(x, y);
                                if (!isWhite(rgb)) {
                                    return x;                        
                                }      
                            }
                        }
                        return null; // all image is white
                    };
                
                var cropTop = scanY(true),
                    cropBottom = scanY(false),
                    cropLeft = scanX(true),
                    cropRight = scanX(false),
                    cropWidth = cropRight - cropLeft,
                    cropHeight = cropBottom - cropTop;
                
                var $croppedCanvas = $("<canvas>").attr({ width: cropWidth, height: cropHeight });
                
                // finally crop the guy
                $croppedCanvas[0].getContext("2d").drawImage(canvas,
                    cropLeft, cropTop, cropWidth, cropHeight,
                    0, 0, cropWidth, cropHeight);
                
                $("#uploadedImage").
                    append("<p>same image with white spaces cropped:</p>").
                    append($croppedCanvas);
                console.log(cropTop, cropBottom, cropLeft, cropRight);
            };

            img.crossOrigin = "anonymous";
            img.onload = function () {
                $canvas.attr({ width: this.width, height: this.height });
                context = canvas.getContext("2d");
                if (context) {
                    context.drawImage(this, 0, 0);

                    $("#uploadedImage").height(this.height * 4);
                    $("#uploadedImage").append("<p>original image:</p>").append($canvas);
                
                    removeBlanks(this.width, this.height);
                } else {
                    alert('Get a real browser!');
                }
            };

            // define here an image from your domain
            img.src = imgPath;

        }

        function manipulateImage2(imgPath) {
            $("img").each(function() {
                var $this = $(this);
                var tim = $this.get(0);
                var idx = $this.index();

                var canvas = null;
                var ctx = null;

                var img = new Image();
                img.onload = function() {
                    copyImageToCanvas(img);
                };
                img.setAttribute("src", tim.src);

                function copyImageToCanvas(aImg) {
                    canvas = document.createElement("canvas");

                    var w = typeof aImg.naturalWidth == "undefined" ? aImg.width : aImg.naturalWidth;
                    var h = typeof aImg.naturalHeight == "undefined" ? aImg.height : aImg.naturalHeight;

                    canvas.id = "img" + idx;
                    canvas.width = w;
                    canvas.height = h;

                    $this.replaceWith(canvas);

                    ctx = canvas.getContext("2d");
                    ctx.clearRect(0, 0, w, h);      
                    ctx.drawImage(aImg, 0, 0);

                    makeTransparent(aImg);
                }

                function makeTransparent(aImg) {

                    var w = typeof aImg.naturalWidth == "undefined" ? aImg.width : aImg.naturalWidth;
                    var h = typeof aImg.naturalHeight == "undefined" ? aImg.height : aImg.naturalHeight;
                    var imageData = ctx.getImageData(0, 0, w, h);

                    for (var x = 0; x < imageData.width; x++)
                        for (var y = 0; y < imageData.height; y++) {
                            var offset = (y * imageData.width + x) * 4;
                            var r = imageData.data[offset];
                            var g = imageData.data[offset + 1];
                            var b = imageData.data[offset + 2];

                            //if it is pure white, change its alpha to 0              
                            if (r >= 255 && g == 255 && b == 255)
                                imageData.data[offset + 3] = 0;
                        };

                    ctx.putImageData(imageData, 0, 0);
                }
            });
        }

        function manipulateImage3(imgPath) {
            // get the image
            var img = document.getElementById("original-image");
            // create and customize the canvas
            var canvas = document.createElement("canvas");
            canvas.width = 500;
            canvas.height = 200;
            document.body.appendChild(canvas);
            // get the context
            var ctx = canvas.getContext("2d");
            // draw the image into the canvas
            ctx.drawImage(img, 0, 0);

            // get the image data object
            var image = ctx.getImageData(0, 0, 500, 200);
            // get the image data values 
            var imageData = image.data,
            length = imageData.length;
            // set every fourth value to 50
            for(var i=3; i < length; i+=4){  
                imageData[i] = 50;
            }
            // after the manipulation, reset the data
            image.data = imageData;
            // and put the imagedata back to the canvas
            ctx.putImageData(image, 0, 0);

            img.src = canvas.toDataURL();
        }

</script>
@endsection
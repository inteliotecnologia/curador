<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <title>Image Loading</title>
    <style type="text/css" media="screen">
    <!--
        BODY { margin: 10px; padding: 0; font: 1em "Trebuchet MS", verdana, arial, sans-serif; font-size: 100%; }
        H1 { margin-bottom: 2px; }

        DIV#loader {
            border: 1px solid #ccc;
            width: 500px;
            height: 500px;
            overflow: hidden;
        }
		
		DIV#loader img {
			background: #f0f;
		}

        DIV#loader.loading {
            background: url(images/spinner.gif) no-repeat center center;
        }
    -->
    </style>

    <script src="js/jquery.js" type="text/javascript"></script>

    <script type="text/javascript">
    <!--
    $(function () {
        var img = new Image();
        $(img).load(function () {
            //$(this).css('display', 'none'); // .hide() doesn't work in Safari when the element isn't on the DOM already
            $(this).hide();
            $('#loader').removeClass('loading').append(this);
            $(this).fadeIn();
        }).error(function () {
            // notify the user that the image could not be loaded
        }).attr('src', 'http://farm3.static.flickr.com/2405/2238919394_4c9b5aa921_o.jpg');
    });
    
    //-->
    </script>
</head>
<body id="page">
    <h1>Image Loading</h1>
    <p>This demonstration shows how to pre-load an image in the background while showing a loading screen.  It also supports loading the image, then running additional code, such as Ajax requests before finally showing the image.</p>
    <p><a href="http://jqueryfordesigners.com/image-loading/">Read the article this demonstration relates to</a></p>
    <div id="loader" class="loading">

    </div>
    <p><a href="http://www.flickr.com/photos/remysharp/">More photos...</a></p>

    <!-- this is just a way to simulate a delay (the script will sleep for 2 seconds then response) -->
    <script src="image-load-demo.php?action=delay" type="text/javascript" charset="utf-8"></script>
</body>
</html>
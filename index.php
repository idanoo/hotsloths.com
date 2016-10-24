<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>HotSloths!</title>

    <meta property="og:image" content="https://upload.wikimedia.org/wikipedia/commons/a/a7/Cute_Sloth.jpg"/>
    <meta property="og:title" content="HotSloths!"/>
    <meta property="og:site_name" content="HotSloths!"/>
    <meta property="og:description" content="HotSloths is a hot new way to view images of sloths on the internet. Images are pulled directly from reddit.com/r/sloths."/>
    <meta property="og:url" content="https://hotsloths.com"/>
    <meta property="fb:app_id" content="706742206156294"/>

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
    <link rel="icon" href="/favicon.ico" type="image/x-icon">

    <style>
        html, body, #sloths {
            overflow: hidden;
            background-color: #333;
            width: 100%;
            max-height: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
        }
    </style>
    <script src="jquery-3.1.1.min.js"></script>
</head>
<body>
<div id="sloths"></div>
<!-- Creator: Daniel Mason
       Twitter: @iDanooNZ
       Comments: After a few casual drinks, a friend of mine suggested a domain.. It was available.. :D
                Also.. I don't really javascript often so excuse the crappy code.
                 ALSO I AM SORRY ABOUT THE ADS :( -->
<?php $adUrl = "//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js";?>
<script async src="<?=$adUrl?>"></script>
<ins class="adsbygoogle"
     style="position:absolute; bottom:0; right:0; z-index:9999; display:block; width:320px;height:50px; padding:0!important;margin:0!important;"
     data-ad-client="ca-pub-1912142404509863"
     data-ad-slot="9408954236"></ins>
<script>
    (adsbygoogle = window.adsbygoogle || []).push({});
</script>

<script>
    $(document).ready(function () {
        var slothDom = $('#sloths'); //HOME OF SLOTHS.
        var delayTime = 4000; //MASTER SLOTH TIMER.

        //Check if can access imgur.
        $.ajax({
            url: "http://imgur.com",
            type: 'GET',
            timeout: 1000,
            cache: false,
            success: function(response) {
                console.log("SERVER UP USE IMGUR!");
                imgurIsBlocked = 0;
            },
            error: function(e) {
                console.log("SERVER DOWN USE PROXY! ");
                imgurIsBlocked = 1;
            }
        });

        function checkExtention(url) {
            //Should I add gifs too? Or too slow to load?
            var parts = url.split('.');
            var ext = parts[parts.length - 1];
            switch (ext.toLowerCase()) {
                case 'png':
                case 'jpg':
                    return true;
            }
            return false;
        }

        function shuffleImages(sloths) {
            //There's no sloth.shuffle()? seriously JS?
            var i = sloths.length - 1;
            while (i > 0) {
                var j = parseInt(Math.random() * i);
                var x = sloths[--i];
                sloths[i] = sloths[j];
                sloths[j] = x;
            }
            return sloths;
        }

        function updateImage(image) {
            slothDom.fadeOut(200,function() {
                slothDom.html('<img src="' + image + '" style="width:100%; height:100%; position:absolute; top:0; left: 0;">');
                slothDom.fadeIn(200);
            });
            return true;
        }

        function updateSloths(last) {
            $.ajax({
                url: "https://www.reddit.com/r/sloths/.json?count=25&after=" + last,
                dataType: 'json',
                success: function (data) {
                    var i = 1;
                    var last = data.data.after;

                    //SLOTH ARRAY!!!!
                    var sloths = $.map(data.data.children, function (item) {
                        var url = item.data.url;
                        if (url.indexOf("imgur.com") != -1 && (url.indexOf(".png") == 0 || url.indexOf(".jpg") == 0)) {
                            url = url + ".jpg";
                        }

                        //We only want image files.
                        var legitExtention = checkExtention(url);
                        if (legitExtention == false) return;

                        //Ignore albums at this point.
                        if (url.indexOf("imgur.com/a/") != -1) return;

                        //HTTPS THAT SUCKER! Holy shit this is hax.
                        if (url.indexOf("http://imgur.com") != -1 || url.indexOf("http://i.imgur.com") != -1) {
                            url = url.substring(4);
                            url = "https" + url;
                        }
                        return url;
                    });
                    sloths = shuffleImages(sloths);
                    var x = 1;
                    var slothCount = sloths.length - 1;

                    //setInterval does the interval before first iteration.. manually run first img.
                    updateImage(sloths[0]);
                    var preload = new Image();
                    preload.src = sloths[1];

                    //LET THE SLOTHS BEGIN.
                    var timer = setInterval(function () {
                        updateImage(sloths[x]);
                        if (x == slothCount) {
                            clearInterval(timer);
                            updateSloths(last); // <- CHECK OUT MY RECURSIVE SHIT
                        } else {
                            var preload = new Image();
                            preload.src = sloths[x + 1];
                        }
                        x++;
                    }, delayTime);
                },
            timeout: 2500
            });
        }

        //This is where it all started.
        updateSloths("");
    });
</script>
</body>
</html>
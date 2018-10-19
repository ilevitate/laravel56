<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>One Word !</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 77px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 26px;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height">
    <div class="content">
        <div class="title m-b-md">
            One Word
        </div>

        <div class="links">
            <a href="#" id="word">{{$hitokoto or ''}}</a>
        </div>
        <br>
        <br>
        <div style="float: right;margin-right: 20px"><span id="creator">{{$creator or ''}}</span></div>

    </div>
</div>
<script type="text/javascript" src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
<script>
    window.onload = function () {
        show();
    };

    function show() {
        $.ajax({
            url: 'https://v1.hitokoto.cn?callback=' + 'http://one-word.aonelang.cn',
            type: 'GET',
            dataType: 'json',
            success: function (result) {
                // console.log(result);
                $("#word").html('『 ' + result.hitokoto + ' 』');
                $("#creator").html('—「 ' + result.creator + ' 」');
            }
        })
    }

    setInterval(show, 5000);
</script>
</body>
</html>

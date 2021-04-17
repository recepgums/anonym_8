<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Leave Note</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css"
          integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
    <!-- Styles -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-weight: 400;
            height: 100%;
            width: 100%;
            margin: 0;
            overflow-x: hidden;
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
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px
        } .iframe-container{
              position: relative;
              width: 100%;
              padding-bottom: 56.25%;
              height: 0;
          }
        .iframe-container iframe{
            position: absolute;
            top:7%;
            left: 7%;
            width: 85%;
            height: 80%;
        }
    </style>
</head>
<body onload="start()" style="width: 100%/*background-image: url('https://i.ytimg.com/vi/aiU22lKiZbA/maxresdefault.jpg');*/">
<div style="display: flex;background-color: #0c673b" >
    <div id="tab-1" style="flex:1" >
        <a href="/" class="btn btn-outline-light btn-sm text-white" style="font-size: 15px;">&larr; </a>
    </div>
    <div id="tab-2" style="flex:8">
        <h4  style="text-align:center;color:white;justify-content: center">Leave Note</h4>
    </div>
    <div style="flex:5;" >
        <div class=" row align-items-start " style="float: right;">
            @if (Route::has('login'))
                <div class="">
                    @auth
                        <a class="btn btn-outline-light  btn-sm " href="{{ url('/home') }}">Home</a>
                    @else
                        <a class="btn btn-outline-light btn-sm" href="{{ route('login') }}">Log In</a>

                        @if (Route::has('register'))
                            <a class="btn btn-outline-light btn-sm " href="{{ route('register') }}">Sign Up</a>
                        @endif
                    @endauth
                </div>
            @endif
        </div>
    </div>
    <div style="flex: 1;">
    </div>
</div>
    <div style="flex: 1;">
        <div style="height:120px;text-align: center;justify-content: center">
            <p style="font-size: 28px;" id="animation" >{{$number}}</p>
        </div>
    </div>
<div class="container p-0">
    <div class="col-sm text-center" >
        <div class=" align-items-center justify-content-center text-center" style="height: 20%">
            <form action="{{route('create_private_room_message',$number)}}" method="post" enctype="multipart/form-data">
                @csrf
                <textarea  class="form-control col-xl-12"  type="text" name="title" placeholder="Share a youtube link or any text..." required></textarea>

                <div class="input-group justify-content-center align-items-center mt-2"  >
                    <input class="form-control col-xl-1" id="file_input" type="file" name="file"   style="display: none;font-size: 5px"/>
                    <input type="button" class="btn" value="Browse..." onclick="document.getElementById('file_input').click();" />
                    <input class="form-control col-xl-11" type="password" style="width:50%;margin-left: 8px;display: none" placeholder="Optional password..." id="password_input" name="password" />
                </div>
                <div style="text-align: center" class="mt-3"><input class="btn btn-success"  style="width: 100%" type="submit" value="Send"></div>
            </form>
            @if (isset($file_size_error))
                <div >
                    <ul>
                        <li class="text-danger">{{$file_size_error}}</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</div>
    <br><br>
    <div class="row" style="text-align: center;justify-content: center;">

        <div class=" mb-2 ml-3"
             style="overflow-y:scroll;height: 1900px;float:left;width: 100%; border-style: solid;border-left-color: #28A745;border-right-color: #28A745;border-top-color: white;border-bottom-color: #28A745;border-width: 12px;margin-right: 16px ">

            <div id="app">
                <privateroom :number="{{ $number }}">

                </privateroom>
            </div>
            <script src="/js/app.js"></script>
        </div>
    </div>
        <script >
            page_loaded=()=>{
                $.get(
                    "/private-test",
                );
            };
            let a = document.getElementById('file_input');
            let b = document.getElementById('password_input');
            a.onchange = function () {
                b.style.display= "block";
                b.style.height = "30px";
            }
        </script>

    <script>
        window.laravel_echo_port='{{env("LARAVEL_ECHO_PORT")}}';
    </script>
    <script src="http://127.0.0.1:6001/socket.io/socket.io.js"  ></script>
    <script src="https://cdn.socket.io/socket.io-1.3.4.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    </script>

<script>

     start=()=> {
        var value=(document.getElementById("animation").style.fontSize);
        value=value.toString();
        value=value.substring(0,value.indexOf("px"));
        if(value<=60){
            value=value*1+8;
            value=value+"px";
            document.getElementById("animation").style.fontSize=value;
        }
    }
</script>
</body>
</html>

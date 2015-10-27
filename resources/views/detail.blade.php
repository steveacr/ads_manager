<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>
        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }
            td{
                text-align: center;
                vertical-align: middle;
            }
            .title {
                font-size: 96px;
            }
        </style>
    </head>

    <script src="{{asset('js/jquery.js')}}"></script>
    <link href="{{ asset('/packages/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">

    <body>

        <div class="container">
            <div class="content">
                <div class="row">
                    <a href="/" class="navbar-brand"><strong>Home</strong></a>
                </div>
                <div>
                    <h2>Current item：{{$current}} / {{$total}}</h2>
                </div>
                <p class="text-danger">
                    <strong>
                    Please check whether the appearances of the audios/videos below indicates a same advertisement. <br>
                    If it is, choose any one of audios/videos, adjust and mark "Start Time", "End Time", fill "Product", "Brand" info and click "Submit" to mark an advertisement.<br>
                    Otherwise, click "Delete" to edit the next item.
                    </strong>
                </p>


                <table id="my_table" class="table table-striped table-responsive">
                    <tr>
                        <th>Appearance Time</th>
                        <th>Start Time</th>
                        <th>End Time</th>
                        <th>Product</th>
                        <th>Brand</th>
                        <th>Actions</th>
                    </tr>
                    <?php $i = 1; ?>
                    @foreach( $data as $ii )
                        <tr style="height: 10px;background-color: #B0BEC5"><td  colspan="6"></td></tr>
                        <tr >
                            <form id="newad{{$i}}" action="<?php echo url('/submit'); ?>" method="POST">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <input type="hidden" name="my_id" value="{{ $myid }}">
                                <input type="hidden" name="index_id" value="{{ $current }}">
                            </form>
                        </tr>
                        <tr style="height: 10px;background-color: lightgoldenrodyellow">
                            <td colspan="6">
                                <?php $path = url()."/".$ii->file; $path = str_replace('.wav','',$path); ?>
                                <input type="hidden" name="filename" form="newad{{$i}}" value="{{ $path }}">
                                @if( substr($path, -4) === '.mp3' || substr($path, -4) === '.wav' )
                                    <audio preload="auto" id="audio_{{$i}}" style="width: 1000px" controls src="{{$path}}" >
                                    </audio>
                                @elseif( substr($path, -4) === '.mp4' || substr($path, -4) === '.flv' )
                                    <!--
                                <video preload="auto" controls="controls" height="300" width="400">
                                    <source src="{{ $path }}" type="video/mp4" />
                                    Your browser does not support the video tag.
                                </video>
                                    -->
                                @else
                                    <p>Unsupported file format: {{$path}}</p>
                                @endif
                            </td>
                        </tr>
                        <tr style="height: 10px;background-color: lightgoldenrodyellow">
                            <td></td>
                            <td colspan="4">
                                <button class="btn btn-default back" for="audio_{{$i}}">
                                    <strong> << (1s back)</strong>
                                </button>
                                <button class="btn btn-default play" for="audio_{{$i}}"><strong>play</strong></button>
                                <button class="btn btn-default pause" for="audio_{{$i}}"><strong>pause</strong></button>
                                <button  class="btn btn-default forward" for="audio_{{$i}}">
                                    <strong>(1s forward) >></strong>
                                </button>
                            </td>
                            <td></td>
                        </tr>

                        <tr style="height: 10px;background-color: lightgoldenrodyellow">
                            <td>
                                @foreach( $ii->time as $tt )
                                    <button class="btn btn-default time_point" for="audio_{{$i}}">
                                            <strong>
                                                <?php echo $tt; ?>
                                            </strong>
                                    </button>
                                @endforeach
                            </td>
                            <td>
                                <input readonly class="btn btn-default set_sec" for="audio_{{$i}}" name="start_sec" value="mark start time" form="newad{{$i}}" type="text">
                            </td>
                            <td>
                                <input readonly class="btn btn-default set_sec" for="audio_{{$i}}" name="end_sec" value="mark end time" form="newad{{$i}}" type="text">
                            </td>
                            <td>
                                <input name="product" form="newad{{$i}}" type="text">
                            </td>
                            <td>
                                <input name="brand" form="newad{{$i}}" type="text">
                            </td>
                            <td>
                                <ul class="list-unstyled">
                                    <li style="float:left;">
                                        <input type="submit" form="newad{{$i}}" class="btn btn-primary" value="Submit">
                                    </li>
                                    <li style="float:left;">
                                        <input type="submit" form="delad" class="btn btn-danger" value="Delete">
                                    </li>
                                </ul>
                            </td>
                        </tr>

                        <?php $i++; ?>
                    @endforeach
                </table>
                <form id="delad" action="<?php echo url('/remove'); ?>" method="POST">
                    <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                    <input type="hidden" name="my_id" value="{{ $myid }}">
                    <input type="hidden" name="index_id" value="{{ $current }}">
                </form>
            </div>
        </div>

    </body>

    <script>

        $('.time_point').click( function(){
            var audioId = $(this).attr('for');
            var hms = $(this).text().trim().split( ':' );
            var sec = parseInt(hms[0])*3600 + parseInt(hms[1]*60) + parseInt(hms[2]);
            var audio = document.getElementById(audioId);
            audio.pause();
            audio.currentTime = sec;
            audio.play();
        } );

        $('.back').click( function(){
            var audioId = $(this).attr('for');
            var audio = document.getElementById(audioId);
            audio.pause();
            audio.currentTime -= 1;
        });

        $('.forward').click( function(){
            var audioId = $(this).attr('for');
            var audio = document.getElementById(audioId);
            audio.pause();
            audio.currentTime += 1;
        });

        $('.play').click(function(){
            var audioId = $(this).attr('for');
            var audio = document.getElementById(audioId);
            audio.play();
        });

        $('.pause').click(function(){
            var audioId = $(this).attr('for');
            var audio = document.getElementById(audioId);
            audio.pause();
        });

        $('.set_sec').click(function(){
            var audioId = $(this).attr('for');
            var audio = document.getElementById(audioId);
            var time = ttos(audio.currentTime);
            $(this).attr('value', time);
        });

        function ttos( time ){
            var h = Math.floor( time / 3600 );
            var hh = h > 9 ? h : '0'+h;
            var m = Math.floor( time % 3600 / 60 );
            var mm = m > 9 ? m : '0'+m;
            var s = Math.floor( time % 60);
            var ss = s > 9 ? s : '0'+s;
            return hh + ':' + mm + ':' + ss;
        }

    </script>
</html>

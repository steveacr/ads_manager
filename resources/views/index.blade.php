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
    <link href="{{ asset('/packages/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <body>
        <div class="container">
            <div class="content">
                <div class="title"></div>
                <table class="table table-striped table-responsive">
                    <tr>
                        <th>频道</th>
                        <th>日期</th>
                        <th>操作</th>
                    </tr>
                @foreach ($data as $item)
                    <tr>
                        <td>{{ $item['channel'] }}</td>
                        <td>{{ $item['day'] }}</td>
                        <td><a href="show/<?php echo $item['id']; ?>/1"><button class="btn btn-primary">详细</button></a></td>
                    </tr>
                @endforeach
                </table>
            </div>
        </div>
    </body>
</html>

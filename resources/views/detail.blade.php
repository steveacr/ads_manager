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
        <header class="navbar navbar-static-top bs-docs-nav" role="banner">
            <div class="navbar-header">
                <a href="/" class="navbar-brand">返回主页</a>
            </div>
        </header>
        <main>
        <div class="container">
            <div class="content">
                <div class="title"></div>

                <h2>当前：{{$current}}, 总数：{{$total}}</h2>
                <p class="text-danger">请核实以下目标位置是否为同一广告，如是，在任意包含完整广告的条目中补全并提交广告信息，否则，点击任意 "删除无效广告" 以编辑下一条</p>

                <table id="my_table" class="table table-striped table-responsive">
                    <tr>
                        <th>视频</th>
                        <th>广告出现时间</th>
                        <th>设置开始时间</th>
                        <th>设置结束时间</th>
                        <th>设置产品名称</th>
                        <th>设置品牌名称</th>
                        <th>操作</th>
                    </tr>
                    <?php $i = 1; ?>
                    @foreach( $data as $ii )
                        <tr>
                            <form id="newad{{$i}}" action="<?php echo url('/submit'); ?>" method="POST">
                                <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                                <input type="hidden" name="my_id" value="{{ $myid }}">
                                <input type="hidden" name="index_id" value="{{ $current }}">
                            </form>
                            <td>
                                <video preload="auto" controls="controls" height="300" width="400">
                                    <?php $path = "/cctv1/".$ii->file; ?>
                                    <input type="hidden" name="filename" form="newad{{$i}}" value="{{ $path }}">
                                    <source src="{{ $path }}" type="video/mp4" />
                                    Your browser does not support the video tag.
                                </video>
                            </td>
                            <td>
                                <strong>
                                <?php echo join( '<br/>', $ii->time ); ?>
                                </strong>
                            </td>
                            <td>
                                <input name="start_sec" placeholder="00:00:00" form="newad{{$i}}" type="text">
                            </td>
                            <td>
                                <input name="end_sec" placeholder="00:00:00" form="newad{{$i}}" type="text">
                            </td>
                            <td>
                                <input name="product" form="newad{{$i}}" type="text">
                            </td>
                            <td>
                                <input name="brand" form="newad{{$i}}" type="text">
                            </td>
                            <td>
                                <ul class="list-unstyled">
                                    <li>
                                        <input type="submit" form="newad{{$i}}" class="btn btn-primary" value="提交广告信息">
                                    </li>
                                    <li><br/></li>
                                    <li>
                                        <input type="submit" form="delad" class="btn btn-danger" value="删除无效广告">
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
        </main>
    </body>
</html>

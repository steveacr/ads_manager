<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\AdsAutoDetect;
use App\AdsAutoLog;
use Mockery\CountValidator\Exception;
use Pheanstalk\Pheanstalk;

class IndexController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $data = AdsAutoDetect::where('status','=',0)->get(['id','channel','day']);
        return view('index', ['data'=>$data] );
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $id = $request->get('my_id');
        $index = $request->get('index_id');
        $product = $request->get('product');
        $brand = $request->get('brand');
        $start_sec = $request->get('start_sec');
        $end_sec = $request->get('end_sec');
        $filename = $request->get('filename');
        //echo $id.' '.$index.' '.$product.' '.$brand.' '.$start_sec.' '.$end_sec.' '.$filename;
        $start_sec_i = -1;
        $end_sec_i = -1;
        var_dump($request->all());
        var_dump($start_sec);
        var_dump($end_sec);
        return;
        $hms = explode(':', $start_sec);
        if( sizeof($hms) == 3 )
            $start_sec_i = (int)$hms[0]*3600+(int)$hms[1]*60+(int)$hms[2];
        $hms = explode(':', $start_sec);
        if( sizeof($hms) == 3 )
            $end_sec_i = (int)$hms[0]*3600+(int)$hms[1]*60+(int)$hms[2];

        if( $start_sec_i == -1 && $end_sec_i == -1 )
            return view('error',['data'=>'','msg'=>'invalid start time or end time']);

        $log = AdsAutoLog::where('s_id','=',$id)->where('index_id','=',$index)->get();
        if( sizeof($log) == 0 ) {
            $log = AdsAutoLog::create(
                [
                    's_id' => $id,
                    'index_id' => $index,
                    'status' => 0,
                    'brand' => $brand,
                    'product' => $product,
                    'startSec' => $start_sec_i,
                    'endSec' => $end_sec_i,
                    'filename' => $filename,
                ]
            );

        }

        return redirect('show/'.(string)$id.'/'.(string)($index+1));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id, $index)
    {

        $datas = AdsAutoDetect::find($id);
        if( sizeof($datas) < 1 ){
            return view( 'error', ['data'=>'nothing','msg'=>'invalid id: '.(string)$id] );
        }
        // 如果当前全天节目已处理完毕，返回第二个
        if( $datas->status != 0 ){
            return redirect('show/'.(string)($id+1).'/1' );
        }

        // 如果当前第index条目标广告已处理完毕，返回下一个
        $log = AdsAutoLog::where('s_id','=',$id)->where('index_id','=',$index)->get();
        while( sizeof($log) > 0 ){
            $index++;
            $log = AdsAutoLog::where('s_id','=',$id)->where('index_id','=',$index)->get();
        }

        //$datas = AdsAutoDetect::find($id);
        $json = json_decode($datas->result);
        $total = sizeof($json);

        if( $index < $total ){
            $data = $json[$index];
            return view('detail',['data'=>$data, 'myid'=>$id, 'total'=>$total, 'current'=>$index]);
        }else if( $index == $total ) {
            $datas->status = 1;
            $datas->save();

            $pheanstalk = new Pheanstalk('127.0.0.1');
            $pheanstalk->useTube('auto_ads')->put((string)($log->id));

            return redirect('/');
        }
        else{
            return view( 'error', ['data'=>'nothing','msg'=>'invalid index id: '.(string)$index ] );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Request $request
     * @return Response
     */
    public function destroy(Request $request)
    {
        $id = $request->get('my_id');
        $index = $request->get('index_id');

        $log = AdsAutoLog::where('s_id','=',$id)->where('index_id','=',$index)->get();
        if( sizeof($log) == 0 ) {

            $log = AdsAutoLog::create(
                [
                    's_id' => $id,
                    'index_id' => $index,
                    'status' => 2,
                ]
            );
        }

        return redirect('show/'.(string)$id.'/'.(string)($index+1));
    }
}

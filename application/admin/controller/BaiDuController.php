<?php

namespace app\admin\controller;


use app\index\controller\Live;
use think\Request;

class BaiDuController
{
    protected $liveController;
    protected $token = '8f5b899e9d598d6669e3940d79d8f314';
    protected $user_name = 'sh-邦纬';
    protected $pwd = 'Bd59160181';

    public function __construct(Live $liveController)
    {
        $this->liveController = $liveController;
    }

    public function getData(Request $request)
    {
        $param = $request->param();
        $cycle = [];
        $time = 3600 * 24;
        $cycle[] = date('m/d', strtotime($param['start_date']));
        $start = (int)$param['start_date'];
        for ($i = 0; $i >= 0; $i++) {
            $start = date('Ymd', strtotime($start) + $time);

            if ((int)$start > (int)$param['end_date']) {
                break;
            }

            $cycle[] = date('m/d', strtotime($start));
        }
        //网站概况
        $data = [
            'header' => [
                'account_type' => 1,
                'username' => $this->user_name,
                'password' => $this->pwd,
                'token' => $this->token,
            ],
            'body' => [
                'siteId' => '16453568',
                'method' => 'overview/getTimeTrendRpt',
                'start_date' => $param['start_date'],
                'end_date' => $param['end_date'],
                'metrics' => 'pv_count,visitor_count,ip_count,bounce_ratio,avg_visit_time,trans_count',
            ]
        ];
        $url = 'https://api.baidu.com/json/tongji/v1/ReportService/getData';
        $res = $this->liveController->liveCurl(json_encode($data, JSON_UNESCAPED_UNICODE), $url);
        //地域分布
        $list = [
            'header' => [
                'account_type' => 1,
                'username' => $this->user_name,
                'password' => $this->pwd,
                'token' => $this->token,
            ],
            'body' => [
                'siteId' => '16453568',
                'method' => 'overview/getDistrictRpt',
                'start_date' => $param['start_date'],
                'end_date' => $param['end_date'],
                'metrics' => 'pv_count',
            ]
        ];
        $result = $this->liveController->liveCurl(json_encode($list), $url);

        //趋势分析
        $trend = [
            'header' => [
                'account_type' => 1,
                'username' => $this->user_name,
                'password' => $this->pwd,
                'token' => $this->token,
            ],
            'body' => [
                'siteId' => '16453568',
                'method' => 'trend/time/a',
                'start_date' => $param['start_date'],
                'end_date' => $param['end_date'],
                'metrics' => 'pv_count,visitor_count,ip_count,bounce_ratio,avg_visit_time,trans_count',
                'clientDevice' => 'pc',
                'source' => 'through',
                'gran' => 'hour'
            ]
        ];
        $trenddata = $this->liveController->liveCurl(json_encode($trend), $url);

        $getData['overview'] = $res['body']['data'][0]['result']['items'][1];
        $getData['cycle'] = $cycle;
        $getData['terrain'] = $result['body']['data'][0]['result']['items'];
        $terrain = [];
        foreach ($getData['terrain'][0] as $k => $v) {
            $terrain[] = ['name' => $getData['terrain'][0][$k][0], 'value' => $getData['terrain'][1][$k][0], 'prop' => $getData['terrain'][1][$k][1]];
        }
        //        foreach ($getData['terrain'][0] as $k => $v) {
        //            $terrain[] = ['name' => $getData['terrain'][0][$k][0], 'value' => $getData['terrain'][1][$k][0], 'prop'=>$getData['terrain'][1][$k][1]];
        //        }
        $getData['terrain'] = $terrain;
        $getData['trend'] = $trenddata['body']['data'][0]['result']['items'];
        $trendData = [];
        foreach ($getData['trend'][0] as $key => $val) {
            if ($getData['trend'][1][$key][0] == '--') {
                $getData['trend'][1][$key][0] = 0;
            }
            if ($getData['trend'][1][$key][1] == '--') {
                $getData['trend'][1][$key][1] = 0;
            }
            if ($getData['trend'][1][$key][2] == '--') {
                $getData['trend'][1][$key][2] = 0;
            }
            if ($getData['trend'][1][$key][3] == '--') {
                $getData['trend'][1][$key][3] = 0;
            }
            if ($getData['trend'][1][$key][4] == '--') {
                $getData['trend'][1][$key][4] = 0;
            }
            if ($getData['trend'][1][$key][5] == '--') {
                $getData['trend'][1][$key][5] = 0;
            }
            $trendData['time'][] = $getData['trend'][0][$key][0];
            $trendData['pv_count'][] = $getData['trend'][1][$key][0];
            $trendData['visitor_count'][] = $getData['trend'][1][$key][1];
            $trendData['ip_count'][] = $getData['trend'][1][$key][2];
            $trendData['bounce_ratio'][] = $getData['trend'][1][$key][3];
            $trendData['avg_visit_time'][] = $getData['trend'][1][$key][4];
            $trendData['trans_count'][] = $getData['trend'][1][$key][5];
        }
        $trendData['time'] = array_reverse($trendData['time']);
        $trendData['pv_count'] = array_reverse($trendData['pv_count']);
        $trendData['visitor_count'] = array_reverse($trendData['visitor_count']);
        $trendData['ip_count'] = array_reverse($trendData['ip_count']);
        $trendData['bounce_ratio'] = array_reverse($trendData['bounce_ratio']);
        $trendData['avg_visit_time'] = array_reverse($trendData['avg_visit_time']);
        $trendData['trans_count'] = array_reverse($trendData['trans_count']);
        $getData['trend'] = $trendData;

        //受访页面
        $visited = [
            'header' => [
                'account_type' => 1,
                'username' => $this->user_name,
                'password' => $this->pwd,
                'token' => $this->token,
            ],
            'body' => [
                'siteId' => '16453568',
                'method' => 'visit/toppage/a',
                'start_date' => $param['start_date'],
                'end_date' => $param['end_date'],
                'metrics' => 'pv_count',
                'max_results' => '10'
            ]
        ];
        $visitedList = [];
        $visitedData = $this->liveController->liveCurl(json_encode($visited), $url);

        $getData['visited'] = $visitedData['body']['data'][0]['result']['items'];
        foreach ($visitedData['body']['data'][0]['result']['items'][0] as $k => $v) {
            $visitedList[] = ['link' => $getData['visited'][0][$k][0]['name'], 'value' => $getData['visited'][1][$k][0]];
        }
        $getData['visited'] = $visitedList;

        //全部来源
        $source = [
            'header' => [
                'account_type' => 1,
                'username' => $this->user_name,
                'password' => $this->pwd,
                'token' => $this->token,
            ],
            'body' => [
                'siteId' => '16453568',
                'method' => 'source/all/a',
                'start_date' => $param['start_date'],
                'end_date' => $param['end_date'],
                'metrics' => 'pv_count',
                'max_results' => '10'
            ]
        ];
        $sourceData = $this->liveController->liveCurl(json_encode($source), $url);
        $getData['sourse'] = $sourceData['body']['data'][0]['result']['items'];
        $sourceList = [];
        foreach ($sourceData['body']['data'][0]['result']['items'][0] as $k => $v) {
            $sourceList[] = ['link' => $getData['sourse'][0][$k][0]['name'], 'value' => $getData['sourse'][1][$k][0]];
        }
        $getData['sourse'] = $sourceList;
        //        unset($getData['terrain'][2]);
        //        unset($getData['terrain'][3]);
        sort($getData['terrain']);
        //        pd($getData);
        if ($res) {
            return show(200, '请求成功', $getData);
        }
        return show(401, '暂无数据');
    }
}

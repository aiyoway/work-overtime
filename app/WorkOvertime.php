<?php

namespace App;

use Psr\Container\ContainerInterface;
use Illuminate\Database\Capsule\Manager as DB;

class WorkOvertime
{
    protected $thisYear;
    protected $thisMonth;
    protected $lastMonth;

    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
        $this->user = $this->ci->get('user');
        $this->thisYear = date('Y', time());
        $this->thisMonth = (int)date('m', time());
        $this->lastMonth = (int)date('m', strtotime('last month'));
    }

    public function index($req, $res)
    {
        $params = $req->getParsedBody();
        // 记录表插入数据
        DB::table('overtime')->insert([
            'user_id' => $this->user->id,
            'hours' => $params['hours'],
            'date' => empty($params['date']) ? time() : strtotime($params['date']),
            'created' => time()
        ]);
        $condition = [
            'user_id' => $this->user->id,
            'year' => $this->thisYear,
            'month' => $this->thisMonth
        ];
        // 如果当月没有余额记录则新建一条
        if (!DB::table('surplus')->where($condition)->first()) {
            DB::table('surplus')->insert($condition);
        }
        if ($params['hours'] > 0) {
            DB::table('surplus')->increment('surplus', $params['hours']);
        } else {
            // 核销逻辑
            $thisMonthSurplus = DB::table('surplus')->where($condition)->first();
            $condition['month'] = $this->lastMonth;
            $lastMonthSurplus = DB::table('surplus')->where($condition)->first();
            if ($lastMonthSurplus->surplus >= abs($params['hours'])) {
                DB::table('surplus')->where($condition)->update([
                    'surplus' => $lastMonthSurplus->surplus + $params['hours']
                ]);
            } else if ($lastMonthSurplus->surplus <= 0) {
                $condition['month'] = $this->thisMonth;
                DB::table('surplus')->where($condition)->update([
                    'surplus' => $thisMonthSurplus->surplus + $params['hours']
                ]);
            } else {
                DB::table('surplus')->where($condition)->update([
                    'surplus' => 0
                ]);
                $condition['month'] = $this->thisMonth;
                DB::table('surplus')->where($condition)->update([
                    'surplus' => $thisMonthSurplus->surplus + $lastMonthSurplus->surplus + $params['hours']
                ]);
            }
        }
        return $res->withStatus(201);
    }

    public function overtimeList($req, $res)
    {
        $lastMonthStart = strtotime(date('Ym', strtotime('last month')) . '01000000');
        $lastMonthEnd = strtotime(date('Ym', time()) . '01000000') - 1;
        $thisMonthStart = strtotime(date('Ym', time()) . '01000000');
        $thisMonthEnd = strtotime(date('Ym', strtotime('+1 month')) . '01000000') - 1;

        $lastMonthDetail = $this->surplus($lastMonthStart, $lastMonthEnd, true);
        $thisMonthDetail = $this->surplus($thisMonthStart, $thisMonthEnd, true);
        return $res->withJson([
            'lastMonth' => [
                'items' => $lastMonthDetail,
            ],
            'thisMonth' => [
                'items' => $thisMonthDetail
            ]
        ]);
    }

    public function overtimeSurplus($req, $res)
    {
        $condition = [
            'user_id' => $this->user->id,
            'year' => $this->thisYear,
            'month' => $this->lastMonth
        ];
        $lastMonthSurplus = DB::table('surplus')->where($condition)->first();
        $condition['month'] = $this->thisMonth;
        $thisMonthSurplus = DB::table('surplus')->where($condition)->first();

        return $res->withJson([
            'lastMonthSurplus' => $lastMonthSurplus ? $lastMonthSurplus->surplus : 0,
            'thisMonthSurplus' => $thisMonthSurplus ? $thisMonthSurplus->surplus : 0,
        ]);
    }

    protected function surplus($start, $end)
    {
        return DB::table('overtime')->where([
            ['user_id', '=', $this->user->id],
            ['date', '>=', $start],
            ['date', '<=', $end]
        ])->get();
    }
}
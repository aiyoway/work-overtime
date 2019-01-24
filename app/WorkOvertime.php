<?php

namespace App;

use Psr\Container\ContainerInterface;
use Illuminate\Database\Capsule\Manager as DB;

class WorkOvertime
{
    protected $lastMonthStart;
    protected $lastMonthEnd;
    protected $thisMonthStart;
    protected $thisMonthEnd;

    public function __construct(ContainerInterface $ci)
    {
        $this->ci = $ci;
        $this->user = $this->ci->get('user');

        $this->lastMonthStart = strtotime(date('Ym', strtotime('last month')) . '01000000');
        $this->lastMonthEnd = strtotime(date('Ym', time()) . '01000000') - 1;

        $this->thisMonthStart = strtotime(date('Ym', time()) . '01000000');
        $this->thisMonthEnd = strtotime(date('Ym', strtotime('+1 month')) . '01000000') - 1;
    }

    public function index($req, $res)
    {
        $params = $req->getParsedBody();
        DB::table('wo_times')->insert([
            'user_id' => $this->user->id,
            'hours' => $params['hours'],
            'date' => empty($params['date']) ? time() : strtotime($params['date']),
            'created' => time()
        ]);
        return $res->withStatus(201);
    }

    public function overtimeList($req, $res)
    {
        $lastMonthDetail = $this->surplus($this->lastMonthStart, $this->lastMonthEnd, true);
        $thisMonthDetail = $this->surplus($this->thisMonthStart, $this->thisMonthEnd, true);
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
        $lastMonthSurplus = $this->surplus($this->lastMonthStart, $this->lastMonthEnd);
        $thisMonthSurplus = $this->surplus($this->thisMonthStart, $this->thisMonthEnd);

        return $res->withJson([
            'lastMonthSurplus' => $lastMonthSurplus,
            'thisMonthSurplus' => $thisMonthSurplus,
        ]);
    }

    protected function surplus($start, $end, $needList = false)
    {
        $data = DB::table('wo_times')->where([
            ['user_id', '=', $this->user->id],
            ['date', '>=', $start],
            ['date', '<=', $end]
        ])->get();
        if ($needList) {
            return $data;
        }
        $surplus = 0;
        foreach ($data as $d) {
            $surplus += $d->hours;
        }
        return $surplus;
    }
}
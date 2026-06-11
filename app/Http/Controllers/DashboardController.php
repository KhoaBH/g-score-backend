<?php

namespace App\Http\Controllers;
use App\Services\ScoreService;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected $scoreService;

    public function __construct(ScoreService $scoreService)
    {
        $this->scoreService = $scoreService;
    }

    public function chart($subject = 'toan'){
        $subjects = ['toan','ngu_van','ngoai_ngu','vat_li','hoa_hoc','sinh_hoc','lich_su','dia_li','gdcd'];
        if (!in_array($subject, $subjects)) {
            $subject = 'toan';
        }
        $data = $this->scoreService->getChartData($subject);

        return view('dashboard', ['tab' => 'chart', 'data' => $data, 'subject' => $subject]);
    }
    public function chartApi($subject = 'toan'){
        $subjects = ['toan','ngu_van','ngoai_ngu','vat_li','hoa_hoc','sinh_hoc','lich_su','dia_li','gdcd'];
        if (!in_array($subject, $subjects)) {
            $subject = 'toan';
        }
        $data = $this->scoreService->getChartData($subject);

        return response()->json($data);
    }

    public function search(){
        return view('dashboard', ['tab' => 'search']);
    }

    public function ranking(){
        return view('dashboard', ['tab' => 'ranking']);
    }
}

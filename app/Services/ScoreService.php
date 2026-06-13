<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ScoreService
{
    public function findBySBD($sbd = null){
        $result =  DB::table('scores')->where('sbd', $sbd)->first();
        return $result;
    }
    public function ranking($group = 'A')
    {
        $formulas = [
            'A'   => 'toan + vat_li + hoa_hoc',
            'B'   => 'toan + hoa_hoc + sinh_hoc',
            'A01' => 'toan + vat_li + ngoai_ngu',
            'D'   => 'toan + ngu_van + ngoai_ngu',
        ];

        $subjects = [
            'A'   => ['toan', 'vat_li', 'hoa_hoc'],
            'B'   => ['toan', 'hoa_hoc', 'sinh_hoc'],
            'A01' => ['toan', 'vat_li', 'ngoai_ngu'],
            'D'   => ['toan', 'ngu_van', 'ngoai_ngu'],
        ];

        $formula = $formulas[$group] ?? $formulas['A'];

        $query = DB::table('scores')
            ->select('sbd', 'toan', 'ngu_van', 'ngoai_ngu', 'vat_li', 'hoa_hoc', 'sinh_hoc', 'lich_su', 'dia_li', 'gdcd')
            ->selectRaw("($formula) as total_score");

        foreach ($subjects[$group] ?? $subjects['A'] as $s) {
            $query->whereNotNull($s);
        }

        $results = $query->orderByDesc('total_score')->limit(10)->get();

        return response()->json($results);
    }
    public function getChartData($subject = 'toan'){
        $total = DB::table('scores')->count();
        $total_subject = DB::table('scores')->whereNotNull($subject)->count();
        $max_score = DB::table('scores')->where($subject, '>', 0)->orderByDesc($subject)->first()->$subject;
        $min_score = DB::table('scores')->where($subject, '>', 0)->orderBy($subject)->first()->$subject;
        $scoreDistribution = DB::table('scores')
        ->selectRaw("
            CASE 
                WHEN $subject >= 0 AND $subject <= 4 THEN '0-4'
                WHEN $subject > 4 AND $subject <= 6 THEN '4-6'
                WHEN $subject > 6 AND $subject <= 8 THEN '6-8'
                WHEN $subject >= 8 AND $subject <= 10 THEN '8-10'
            END as score_range,
            COUNT(*) as total
        ")
        ->whereNotNull($subject) 
        ->groupByRaw("
            CASE 
                WHEN $subject >= 0 AND $subject <= 4 THEN '0-4'
                WHEN $subject > 4 AND $subject <= 6 THEN '4-6'
                WHEN $subject > 6 AND $subject <= 8 THEN '6-8'
                WHEN $subject >= 8 AND $subject <= 10 THEN '8-10'
            END
        ")
        ->orderByRaw("MIN($subject) ASC") // Sắp xếp các khoảng từ nhỏ đến lớn cho biểu đồ
        ->get();
        $avg = DB::table('scores')->whereNotNull($subject)->avg($subject);
        $above_avg_count = DB::table('scores')
            ->where($subject, '>=', 5)
            ->whereNotNull($subject)
            ->count();
        $above_avg_percentage = $total_subject > 0 ? ($above_avg_count / $total_subject) * 100 : 0;
        $data = [
            'total' => $total,
            'total_subject' => $total_subject,
            'max_score' => $max_score,
            'min_score' => $min_score,
            'score_distribution' => $scoreDistribution,
            'average' => $avg,
            'above_avg_count' => $above_avg_count,
            'above_avg_percentage' => $above_avg_percentage
        ];
        return $data;
    }
}
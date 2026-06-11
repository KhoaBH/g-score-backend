<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

class ScoreService
{
    public function findBySBD($sbd = null){
        $result =  DB::table('scores')->where('sbd', $sbd)->first();
        return $result;
    }

    public function getChartData($subject = 'toan'){
        $total = DB::table('scores')->count();
        $total_subject = DB::table('scores')->whereNotNull($subject)->count();
        $max_score = DB::table('scores')->where($subject, '>', 0)->orderByDesc($subject)->first()->$subject;
        $min_score = DB::table('scores')->where($subject, '>', 0)->orderBy($subject)->first()->$subject;
        $scoreDistribution = DB::table('scores')
        ->selectRaw("
            CASE 
                WHEN $subject >= 0 AND $subject <= 1 THEN '0-1'
                WHEN $subject > 1 AND $subject <= 2 THEN '1-2'
                WHEN $subject > 2 AND $subject <= 3 THEN '2-3'
                WHEN $subject > 3 AND $subject <= 4 THEN '3-4'
                WHEN $subject > 4 AND $subject <= 5 THEN '4-5'
                WHEN $subject > 5 AND $subject <= 6 THEN '5-6'
                WHEN $subject > 6 AND $subject <= 7 THEN '6-7'
                WHEN $subject > 7 AND $subject <= 8 THEN '7-8'
                WHEN $subject > 8 AND $subject <= 9 THEN '8-9'
                when $subject > 9 AND $subject <= 9.5 THEN '9-9.5'
                WHEN $subject > 9.5 AND $subject <= 10 THEN '9.5-10'
            END as score_range,
            COUNT(*) as total
        ")
        ->whereNotNull($subject) 
        ->groupByRaw("
            CASE 
                WHEN $subject >= 0 AND $subject <= 1 THEN '0-1'
                WHEN $subject > 1 AND $subject <= 2 THEN '1-2'
                WHEN $subject > 2 AND $subject <= 3 THEN '2-3'
                WHEN $subject > 3 AND $subject <= 4 THEN '3-4'
                WHEN $subject > 4 AND $subject <= 5 THEN '4-5'
                WHEN $subject > 5 AND $subject <= 6 THEN '5-6'
                WHEN $subject > 6 AND $subject <= 7 THEN '6-7'
                WHEN $subject > 7 AND $subject <= 8 THEN '7-8'
                WHEN $subject > 8 AND $subject <= 9 THEN '8-9'
                when $subject > 9 AND $subject <= 9.5 THEN '9-9.5'
                WHEN $subject > 9.5 AND $subject <= 10 THEN '9.5-10'
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
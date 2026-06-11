<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\LazyCollection;

class ScoreSeeder extends Seeder
{
    public function run(): void
    {
        $filePath = database_path('seeders/diem_thi_thpt_2024.csv');
        
        if (!file_exists($filePath)) {
            $this->command->error("Không tìm thấy file CSV tại: $filePath");
            return;
        }

        $this->command->info('Đang mở file và chuẩn bị import dữ liệu lên Neon DB...');

        // Sử dụng LazyCollection để đọc file theo từng dòng, tránh tràn RAM
        LazyCollection::make(function () use ($filePath) {
            $handle = fopen($filePath, 'r');
            
            // Bỏ qua dòng tiêu đề đầu tiên
            fgetcsv($handle); 

            while (($row = fgetcsv($handle)) !== false) {
                yield $row;
            }
            
            fclose($handle);
        })
        ->chunk(500) // Gom cụm 500 dòng để Bulk Insert xuống Neon DB
        ->each(function ($chunk) {
            $batch = [];
            
            foreach ($chunk as $row) {
                if (count($row) < 11) {
                    continue;
                }

                $batch[] = [
                    'sbd'          => $row[0],
                    'toan'         => $row[1] !== '' ? (float)$row[1] : null,
                    'ngu_van'      => $row[2] !== '' ? (float)$row[2] : null, 
                    'ngoai_ngu'    => $row[3] !== '' ? (float)$row[3] : null,
                    'vat_li'       => $row[4] !== '' ? (float)$row[4] : null, 
                    'hoa_hoc'      => $row[5] !== '' ? (float)$row[5] : null,
                    'sinh_hoc'     => $row[6] !== '' ? (float)$row[6] : null,
                    'lich_su'      => $row[7] !== '' ? (float)$row[7] : null,
                    'dia_li'       => $row[8] !== '' ? (float)$row[8] : null, 
                    'gdcd'         => $row[9] !== '' ? (float)$row[9] : null,
                    'ma_ngoai_ngu' => $row[10] !== '' ? $row[10] : null,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            if (!empty($batch)) {
                DB::table('scores')->insert($batch);
            }
        });

        $this->command->info('Hoàn thành quá trình đổ dữ liệu!');
    }
}
{{-- resources/views/partials/chart.blade.php --}}

@php
$subjects = [
    'toan'      => 'Toán',
    'ngu_van'   => 'Ngữ văn',
    'ngoai_ngu' => 'Ngoại ngữ',
    'vat_li'    => 'Vật lý',
    'hoa_hoc'   => 'Hóa học',
    'sinh_hoc'  => 'Sinh học',
    'lich_su'   => 'Lịch sử',
    'dia_li'    => 'Địa lý',
    'gdcd'      => 'GDCD',
];

$mockData = [
    'toan'      => ['count' => 980000, 'avg' => 6.35, 'above_avg_rate' => 58.2, 'max' => 10, 'min' => 0,
                    'chart' => [12000, 18000, 35000, 72000, 120000, 185000, 210000, 175000, 98000, 42000, 13000]],
    'ngu_van'   => ['count' => 982000, 'avg' => 6.81, 'above_avg_rate' => 63.4, 'max' => 9.75, 'min' => 1,
                    'chart' => [2000, 5000, 18000, 55000, 130000, 220000, 245000, 180000, 95000, 28000, 4000]],
    'ngoai_ngu' => ['count' => 950000, 'avg' => 5.92, 'above_avg_rate' => 51.7, 'max' => 10, 'min' => 0,
                    'chart' => [25000, 42000, 68000, 95000, 140000, 165000, 148000, 120000, 80000, 45000, 22000]],
    'vat_li'    => ['count' => 420000, 'avg' => 6.12, 'above_avg_rate' => 55.1, 'max' => 10, 'min' => 0,
                    'chart' => [5000, 10000, 22000, 48000, 80000, 95000, 88000, 62000, 35000, 18000, 7000]],
    'hoa_hoc'   => ['count' => 415000, 'avg' => 6.44, 'above_avg_rate' => 59.3, 'max' => 10, 'min' => 0,
                    'chart' => [4000, 8000, 18000, 42000, 78000, 98000, 92000, 68000, 38000, 20000, 9000]],
    'sinh_hoc'  => ['count' => 380000, 'avg' => 5.78, 'above_avg_rate' => 48.6, 'max' => 9.75, 'min' => 0,
                    'chart' => [8000, 15000, 32000, 58000, 82000, 88000, 75000, 52000, 30000, 15000, 5000]],
    'lich_su'   => ['count' => 560000, 'avg' => 6.02, 'above_avg_rate' => 53.8, 'max' => 9.75, 'min' => 0,
                    'chart' => [6000, 12000, 28000, 62000, 105000, 125000, 115000, 78000, 42000, 18000, 6000]],
    'dia_li'    => ['count' => 555000, 'avg' => 6.58, 'above_avg_rate' => 61.2, 'max' => 9.75, 'min' => 0,
                    'chart' => [3000, 7000, 18000, 48000, 95000, 130000, 128000, 90000, 50000, 22000, 8000]],
    'gdcd'      => ['count' => 540000, 'avg' => 7.21, 'above_avg_rate' => 70.5, 'max' => 10, 'min' => 1.25,
                    'chart' => [1000, 3000, 10000, 32000, 72000, 128000, 155000, 118000, 65000, 30000, 12000]],
];

$chartLabels = ['0-1','1-2','2-3','3-4','4-5','5-6','6-7','7-8','8-9','9-9.5','9.5-10'];
$totalStudents = 1050000;
@endphp

<div class="space-y-5" x-data="{ subject: 'toan' }">

    {{-- Tổng số thí sinh --}}
    <div class="rounded-xl px-6 py-4" style="background:#ffffff; border:1px solid #cddaeb;">
        <div class="text-xs font-medium mb-1" style="color:#7a9ab8; letter-spacing:0.05em;">TỔNG SỐ THÍ SINH</div>
        <div class="text-3xl font-semibold" style="color:#1e3a5f;">{{ number_format($totalStudents) }}</div>
    </div>

    {{-- Panel phân tích --}}
    <div class="rounded-xl" style="background:#ffffff; border:1px solid #cddaeb;">

        {{-- Tab môn --}}
        <div class="flex flex-wrap gap-1 p-3" style="border-bottom:1px solid #cddaeb;">
            @foreach($subjects as $key => $label)
            <button @click="subject = '{{ $key }}'"
                    :style="subject === '{{ $key }}'
                        ? 'background:#dce8f5; color:#1a56a0; font-weight:500;'
                        : 'color:#5b7a99;'"
                    class="px-3 py-1.5 rounded-lg text-sm transition-colors cursor-pointer border-0"
                    style="background:transparent;">
                {{ $label }}
            </button>
            @endforeach
        </div>

        <div class="p-6 space-y-6">

            {{-- Stat cards --}}
            @foreach($mockData as $key => $stat)
            <div x-show="subject === '{{ $key }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="grid grid-cols-5 gap-3 mb-6">
                    <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                        <div class="text-xs mb-1.5" style="color:#7a9ab8;">Số bài thi</div>
                        <div class="text-xl font-semibold" style="color:#1e3a5f;">{{ number_format($stat['count']) }}</div>
                    </div>
                    <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                        <div class="text-xs mb-1.5" style="color:#7a9ab8;">Điểm TB</div>
                        <div class="text-xl font-semibold" style="color:#1e3a5f;">{{ number_format($stat['avg'], 2) }}</div>
                    </div>
                    <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                        <div class="text-xs mb-1.5" style="color:#7a9ab8;">Trên TB</div>
                        <div class="text-xl font-semibold" style="color:#1a56a0;">{{ number_format($stat['above_avg_rate'], 1) }}%</div>
                    </div>
                    <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                        <div class="text-xs mb-1.5" style="color:#7a9ab8;">Cao nhất</div>
                        <div class="text-xl font-semibold" style="color:#166534;">{{ number_format($stat['max'], 2) }}</div>
                    </div>
                    <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                        <div class="text-xs mb-1.5" style="color:#7a9ab8;">Thấp nhất</div>
                        <div class="text-xl font-semibold" style="color:#991b1b;">{{ number_format($stat['min'], 2) }}</div>
                    </div>
                </div>
            </div>
            @endforeach

            {{-- Chart --}}
            <div>
                <div class="text-xs font-medium mb-3" style="color:#7a9ab8; letter-spacing:0.05em;">PHỔ ĐIỂM</div>
                <canvas id="scoreChart" height="80"></canvas>
            </div>

        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const mockData = @json($mockData);
    const chartLabels = @json($chartLabels);

    const ctx = document.getElementById('scoreChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                data: mockData['toan'].chart,
                backgroundColor: '#bfdbfe',
                borderColor: '#3b82f6',
                borderWidth: 1,
                borderRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                x: { grid: { display: false }, ticks: { color: '#7a9ab8', font: { size: 12 } } },
                y: { grid: { color: '#e8eef5' }, ticks: { color: '#7a9ab8', font: { size: 12 } } }
            }
        }
    });

    // Cập nhật chart khi đổi môn
    document.addEventListener('alpine:init', () => {
        Alpine.effect(() => {
            const component = document.querySelector('[x-data]').__x.$data;
            // watch subject change
        });
    });

    // Dùng MutationObserver để bắt Alpine x-show changes
    // Đơn giản hơn: expose function để Alpine gọi
    window.updateChart = function(subject) {
        chart.data.datasets[0].data = mockData[subject].chart;
        chart.update();
    }
</script>
@endpush
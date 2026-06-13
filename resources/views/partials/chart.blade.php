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

$chartLabels = ['0-4','4-6','6-8','8-10'];
@endphp

<style>
    .subject-btn { padding:6px 12px; border-radius:8px; font-size:13px; cursor:pointer; border:none; background:transparent; color:#5b7a99; transition:background 0.15s, color 0.15s; text-decoration:none; display:inline-block; }
    .subject-btn:hover { background:#e8eef5; color:#1e3a5f; }
    .subject-btn.active { background:#dce8f5; color:#1a56a0; font-weight:500; }
</style>

<div class="space-y-5">

    {{-- Tổng số thí sinh --}}
    <div class="rounded-xl px-6 py-4" style="background:#ffffff; border:1px solid #cddaeb;">
        <div class="text-xs font-medium mb-1" style="color:#7a9ab8; letter-spacing:0.05em;">TỔNG SỐ THÍ SINH</div>
        <div class="text-3xl font-semibold" style="color:#1e3a5f;">{{ number_format($data['total']) }}</div>
    </div>

    {{-- Panel phân tích --}}
    <div class="rounded-xl" style="background:#ffffff; border:1px solid #cddaeb;">

        {{-- Tab môn --}}
        <div class="flex flex-wrap gap-1 p-3" style="border-bottom:1px solid #cddaeb;">
            @foreach($subjects as $key => $label)
            <a href="#" onclick="setActive(this); fetchSubject('{{ $key }}'); return false;"
               class="subject-btn {{ $subject === $key ? 'active' : '' }}">
                {{ $label }}
            </a>
            @endforeach
        </div>

        <div id="stats-area" class="p-6 space-y-6">

            {{-- Stat cards --}}
            <div class="grid grid-cols-5 gap-3 mb-6">
                <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                    <div class="text-xs mb-1.5" style="color:#7a9ab8;">Số bài thi</div>
                    <div id="stat-total" class="text-xl font-semibold" style="color:#1e3a5f;">{{ number_format($data['total_subject']) }}</div>
                </div>
                <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                    <div class="text-xs mb-1.5" style="color:#7a9ab8;">Điểm TB</div>
                    <div id="stat-avg" class="text-xl font-semibold" style="color:#1e3a5f;">{{ number_format($data['average'], 2) }}</div>
                </div>
                <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                    <div class="text-xs mb-1.5" style="color:#7a9ab8;">Trên TB</div>
                    <div id="stat-above" class="text-xl font-semibold" style="color:#1a56a0;">{{ number_format($data['above_avg_percentage'], 1) }}%</div>
                </div>
                <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                    <div class="text-xs mb-1.5" style="color:#7a9ab8;">Cao nhất</div>
                    <div id="stat-max" class="text-xl font-semibold" style="color:#166534;">{{ number_format($data['max_score'], 2) }}</div>
                </div>
                <div class="rounded-lg px-4 py-3" style="background:#f4f7fb; border:1px solid #cddaeb;">
                    <div class="text-xs mb-1.5" style="color:#7a9ab8;">Thấp nhất</div>
                    <div id ="stat-min" class="text-xl font-semibold" style="color:#991b1b;">{{ number_format($data['min_score'], 2) }}</div>
                </div>
            </div>

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
    const chartData = @json($data['score_distribution']->pluck('total', 'score_range'));
    const chartLabels = @json($chartLabels);
    const orderedData = chartLabels.map(label => chartData[label] ?? 0);

    const ctx = document.getElementById('scoreChart').getContext('2d');
    window.setActive = function(el) {
        document.querySelectorAll('.subject-btn').forEach(btn => btn.classList.remove('active'));
        el.classList.add('active');
    }
    window.fetchSubject = function(subject) {
        ['stat-total','stat-avg','stat-above','stat-max','stat-min'].forEach(id => {
            document.getElementById(id).innerHTML = '<span class="skeleton" style="height:24px;width:70%;display:block;"></span>';
        });

        fetch(`/api/chart/${subject}`)
            .then(res => res.json())
            .then(data => {
                chart.data.datasets[0].data = chartLabels.map(label => {
                    const found = data.score_distribution.find(d => d.score_range === label);
                    return found ? found.total : 0;
                });
                chart.update();

                document.getElementById('stat-total').textContent = data.total_subject.toLocaleString();
                document.getElementById('stat-avg').textContent = parseFloat(data.average).toFixed(2);
                document.getElementById('stat-above').textContent = parseFloat(data.above_avg_percentage).toFixed(1) + '%';
                document.getElementById('stat-max').textContent = parseFloat(data.max_score).toFixed(2);
                document.getElementById('stat-min').textContent = parseFloat(data.min_score).toFixed(2);
            });
    }
    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [{
                data: orderedData,
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
</script>
@endpush
<style>
    .skeleton { background: linear-gradient(90deg, #e8eef5 25%, #d0dcea 50%, #e8eef5 75%); background-size: 200% 100%; animation: shimmer 1.2s infinite; border-radius: 6px; display: inline-block; }
    @keyframes shimmer { 0% { background-position: 200% 0; } 100% { background-position: -200% 0; } }
</style>
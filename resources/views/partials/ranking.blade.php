{{-- resources/views/partials/ranking.blade.php --}}

@php
$groups = [
    'A'   => ['label' => 'Khối A (Toán, Lý, Hóa)',    'subjects' => ['toan','vat_li','hoa_hoc']],
    'A01' => ['label' => 'Khối A01 (Toán, Lý, Anh)',  'subjects' => ['toan','vat_li','ngoai_ngu']],
    'B'   => ['label' => 'Khối B (Toán, Hóa, Sinh)',  'subjects' => ['toan','hoa_hoc','sinh_hoc']],
    'D'   => ['label' => 'Khối D (Toán, Văn, Anh)',   'subjects' => ['toan','ngu_van','ngoai_ngu']],
];

$subjectLabels = [
    'toan'      => 'Toán',
    'ngu_van'   => 'Ngữ văn',
    'ngoai_ngu' => 'Ngoại ngữ',
    'vat_li'    => 'Vật lý',
    'hoa_hoc'   => 'Hóa học',
    'sinh_hoc'  => 'Sinh học',
];

$selectedGroup = $group ?? 'A';
@endphp

<style>
    .group-btn { padding:6px 16px; border-radius:8px; font-size:13px; cursor:pointer; border:none; background:transparent; color:#5b7a99; transition:background 0.15s, color 0.15s; text-decoration:none; display:inline-block; font-weight:500; }
    .group-btn:hover { background:#e8eef5; color:#1e3a5f; }
    .group-btn.active { background:#dce8f5; color:#1a56a0; }
</style>

<div class="space-y-5">

    {{-- Panel --}}
    <div class="rounded-xl bg-white overflow-hidden" style="border:1px solid #cddaeb;">

        {{-- Group tabs --}}
        <div class="flex flex-wrap gap-1 p-3" style="border-bottom:1px solid #cddaeb;">
            @foreach($groups as $key => $g)
            <a  href="#" onclick="setActive(this); fetchSubject('{{ $key }}'); return false;"
               class="group-btn {{ $selectedGroup === $key ? 'active' : '' }}">
                {{ $key }}
            </a>
            @endforeach
        </div>

        <div class="px-6 py-4" style="border-bottom:1px solid #cddaeb;">
            <div id="group-name" class="text-sm font-semibold" style="color:#1e3a5f;">{{ $groups[$selectedGroup]['label'] }}</div>
        </div>

        {{-- Table --}}
        <table class="w-full">
            <thead id="ranking-table-head">
                <tr style="background:#f4f7fb;">
                    <th class="px-6 py-3 text-left text-xs font-medium" style="color:#7a9ab8; width:60px;">Hạng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium" style="color:#7a9ab8;">SBD</th>
                    @foreach($groups[$selectedGroup]['subjects'] as $subj)
                    <th class="px-6 py-3 text-right text-xs font-medium" style="color:#7a9ab8;">{{ $subjectLabels[$subj] }}</th>
                    @endforeach
                    <th class="px-6 py-3 text-right text-xs font-medium" style="color:#7a9ab8;">Tổng điểm</th>
                </tr>
            </thead>
            <tbody id="ranking-table-body">
            </tbody>
        </table>

    </div>
</div>
@push('scripts')
<script>
    const groupConfig = @json($groups);
    const subjectLabels = @json($subjectLabels);
    window.setActive = function(el) {
        const siblings = el.parentElement.children;
        for(let sib of siblings) {
            sib.classList.remove('active');
        }
        el.classList.add('active');
    }
    window.fetchSubject = function(group) {
        document.getElementById('ranking-table-body').innerHTML = `
        <tr><td colspan="10" class="px-6 py-12 text-center text-sm" style="color:#7a9ab8;">
            Đang tải dữ liệu...
        </td></tr>
    `;
        fetch(`/api/ranking/${group}`)
            .then(res => res.json())
            .then(data => {
                data = data.original;
                const subjects = groupConfig[group].subjects;
                document.getElementById('group-name').textContent = groupConfig[group].label;
                let theadHtml = `<tr style="background:#f4f7fb;">
                    <th class="px-6 py-3 text-left text-xs font-medium" style="color:#7a9ab8; width:60px;">Hạng</th>
                    <th class="px-6 py-3 text-left text-xs font-medium" style="color:#7a9ab8;">SBD</th>`;
                    subjects.forEach(subj => {
                        theadHtml += `<th class="px-6 py-3 text-right text-xs font-medium" style="color:#7a9ab8;">${subjectLabels[subj]}</th>`;
                    });
                    theadHtml += `<th class="px-6 py-3 text-right text-xs font-medium" style="color:#7a9ab8;">Tổng điểm</th></tr>`;
                document.getElementById('ranking-table-head').innerHTML = theadHtml;
                 let tbodyHtml = '';
                data.forEach((row, i) => {
                    tbodyHtml += `<tr style="border-top:1px solid #f0f5fb;">
                        <td class="px-6 py-3 text-sm font-semibold" style="color:${i < 3 ? '#1a56a0' : '#7a9ab8'};">#${i + 1}</td>
                        <td class="px-6 py-3 text-sm font-medium" style="color:#1e3a5f;">${row.sbd}</td>`;
                    subjects.forEach(subj => {
                        tbodyHtml += `<td class="px-6 py-3 text-right text-sm" style="color:#5b7a99;">${parseFloat(row[subj]).toFixed(2)}</td>`;
                    });
                    tbodyHtml += `<td class="px-6 py-3 text-right text-sm font-bold" style="color:#166534;">${parseFloat(row.total_score).toFixed(2)}</td></tr>`;
                });
                document.getElementById('ranking-table-body').innerHTML = tbodyHtml;
            });
    }
    fetchSubject('{{ $selectedGroup }}');
</script>

@endpush
<style>
    .skeleton { background: linear-gradient(90deg, #e0e0e0 25%, #f0f0f0 37%, #e0e0e0 63%); background-size: 400% 100%; animation: shimmer 1.4s ease infinite; }
    @keyframes shimmer {
        0% { background-position: -400px 0; }
        100% { background-position: 400px 0; }
    }
</style>
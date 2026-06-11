{{-- resources/views/partials/search.blade.php --}}

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
@endphp

<style>
    .search-input:focus { outline:none; border-color:#7ab3e0; }
</style>

<div class="space-y-5">

    {{-- Search box --}}
    <div class="rounded-xl px-6 py-5 bg-white" style="border:1px solid #cddaeb;">
        <div class="text-xs font-medium mb-3 tracking-wider" style="color:#7a9ab8;">TRA CỨU ĐIỂM</div>
        <div class="flex gap-2.5">
            <input type="text"
                   class="search-input flex-1 h-11 px-4 text-sm rounded-xl bg-white"
                   style="border:1px solid #cddaeb; color:#1e3a5f;"
                   placeholder="Nhập số báo danh..." />
            <button class="h-11 px-6 text-sm rounded-xl text-white font-medium cursor-pointer" 
                    style="background:#1a56a0;"
                    onclick="performSearch(document.querySelector('.search-input').value)">
                Tra cứu
            </button>
        </div>
    </div>

    {{-- Result --}}
    <div id="result-area" class="rounded-xl bg-white overflow-hidden" style="border:1px solid #cddaeb; display:none;">

        <div class="px-6 py-4" style="border-bottom:1px solid #cddaeb;">
            <div class="text-xs mb-1" style="color:#7a9ab8;">Số báo danh</div>
            <div id="sbd-result" class="text-lg font-semibold" style="color:#1e3a5f;"></div>
        </div>

        <table class="w-full">
            <thead>
                <tr style="background:#f4f7fb;">
                    <th class="px-6 py-3 text-left text-xs font-medium" style="color:#7a9ab8;">Môn thi</th>
                    <th class="px-6 py-3 text-right text-xs font-medium" style="color:#7a9ab8;">Điểm</th>
                </tr>
            </thead>
            <tbody>
                @foreach($subjects as $key => $label)
                <tr style="border-top:1px solid #f0f5fb;">
                    <td class="px-6 py-3 text-sm" style="color:#5b7a99;">
                        {{ $label }}
                        @if($key === 'ngoai_ngu')
                            <span id="ma-ngoai-ngu" class="text-xs ml-1.5" style="color:#7a9ab8; display:none;"></span>
                        @endif
                    </td>
                    <td class="px-6 py-3 text-right text-sm font-semibold">
                        <span id="score-{{ $key }}" style="color:#cddaeb;">—</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </div>
</div>
@push('scripts')
<script>
    // Placeholder for search functionality
    // In a real implementation, this would make an AJAX request to the backend API
    function performSearch(sbd) {
        console.log('Searching for SBD:', sbd);
        window.fetch(`/api/search/${sbd}`)
            .then(res => res.json())
            .then(data => {
                const subjects = Object.keys(@json($subjects));
                subjects.forEach(subject => {
                    const el = document.getElementById(`score-${subject}`);
                    if(!el) return;
                    const score = data[subject];
                    if(score !== null && score !== undefined) {
                        el.textContent = parseFloat(score).toFixed(2) || score.toFixed(2);
                        el.style.color = score >= 8 ? '#166534' : (score >= 5 ? '#1e3a5f' : '#991b1b');
                    } else {
                        el.textContent = 'Không thi';
                        el.style.color = '#7a9ab8';
                    }
                });
                document.getElementById('sbd-result').textContent = data.sbd || 'Không tìm thấy';
                document.getElementById('result-area').style.display = 'block';
            })
            .catch(error => {
                console.error('Error searching for SBD:', error);
            });
    }

</script>
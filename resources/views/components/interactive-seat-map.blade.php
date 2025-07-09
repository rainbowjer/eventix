@props(['seats', 'mySeatIds' => []])
<div class="interactive-seat-map">
    <div class="stage">STAGE</div>
    @php
        $rows = ['A','B','C','D','E'];
        $types = ['A' => 'vip', 'B' => 'general', 'C' => 'general', 'D' => 'economy', 'E' => 'economy'];
    @endphp
    @foreach($rows as $row)
        <div class="seat-row">
            <span class="row-label">{{ $row }}</span>
            @foreach($seats->where('row', $row) as $seat)
                <button
                    class="seat-btn {{ $types[$row] }} {{ $seat->is_booked ? 'booked' : '' }} {{ in_array($seat->id, $mySeatIds) ? 'my-seat' : '' }}"
                    data-seat-id="{{ $seat->id }}"
                    data-seat-label="{{ $seat->label }}"
                    data-seat-type="{{ $seat->type }}"
                    data-seat-price="{{ $seat->price }}"
                    @if($seat->is_booked) disabled @endif
                    title="{{ $seat->label }} ({{ $seat->type }}) @ RM{{ $seat->price }}"
                >
                    {{ $seat->label }}
                    @if(in_array($seat->id, $mySeatIds))
                        <span class="badge bg-success ms-1">You</span>
                    @endif
                </button>
            @endforeach
        </div>
    @endforeach
    <div class="legend mt-3">
        <span class="legend-item vip"></span> VIP
        <span class="legend-item general"></span> General
        <span class="legend-item economy"></span> Economy
        <span class="legend-item my-seat"></span> Your Seat
        <span class="legend-item booked"></span> Booked
    </div>
</div>
<style>
.interactive-seat-map { background: #fff; border-radius: 1rem; padding: 2rem; box-shadow: 0 4px 24px #0001; max-width: 600px; margin: 0 auto; }
.stage { text-align: center; font-weight: bold; margin-bottom: 2rem; font-size: 1.3rem; letter-spacing: 2px; }
.seat-row { display: flex; align-items: center; margin-bottom: 1.2rem; gap: 0.7rem; }
.row-label { font-weight: bold; margin-right: 1.2rem; font-size: 1.1rem; color: #ffe066; min-width: 2.5rem; text-align: right; }
.seat-btn { width: 2.5rem; height: 2.5rem; border-radius: 50%; border: none; font-weight: 600; font-size: 1rem; margin: 0 0.2rem; background: #444; color: #fff; cursor: pointer; transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.1s; box-shadow: 0 2px 8px #0003; outline: none; position: relative; }
.seat-btn.vip { background: #ffe066; color: #222; }
.seat-btn.general { background: #a5d8ff; color: #222; }
.seat-btn.economy { background: #ffd6a5; color: #222; }
.seat-btn.booked { background: #888 !important; color: #fff !important; cursor: not-allowed; opacity: 0.6; position: relative; }
.seat-btn.booked::after { content: "\1F512"; position: absolute; top: 0.2rem; right: 0.2rem; font-size: 1rem; }
.seat-btn.my-seat { border: 3px solid #28a745 !important; background: #d4edda !important; color: #155724 !important; }
.seat-btn:hover:not(.booked) { background: #4caf50; color: #fff; transform: scale(1.1); z-index: 2; }
.legend { display: flex; gap: 1.2rem; align-items: center; margin-top: 2rem; justify-content: center; }
.legend-item { display: inline-block; width: 1.5rem; height: 1.5rem; border-radius: 50%; margin-right: 0.3rem; vertical-align: middle; }
.legend-item.vip { background: #ffe066; border: 2px solid #ffe066; }
.legend-item.general { background: #a5d8ff; border: 2px solid #a5d8ff; }
.legend-item.economy { background: #ffd6a5; border: 2px solid #ffd6a5; }
.legend-item.my-seat { background: #d4edda; border: 2px solid #28a745; }
.legend-item.booked { background: #888; border: 2px solid #888; }
</style>
<script>
document.querySelectorAll('.seat-btn').forEach(btn => {
    btn.addEventListener('mouseenter', function() {
        btn.style.boxShadow = '0 0 0 4px #4caf5044';
    });
    btn.addEventListener('mouseleave', function() {
        btn.style.boxShadow = '';
    });
});
</script> 
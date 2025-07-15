@extends('layouts.app')
@section('content')
<div class="container py-4 d-flex flex-column align-items-center justify-content-center" style="min-height:60vh;">
    <h2 class="mb-4 text-center" style="font-size:2rem;">Scan Ticket QR</h2>
    <div id="reader" class="rounded shadow" style="width:100%;max-width:340px;"></div>
    <div id="result" class="mt-3 text-center"></div>
</div>
<script src="https://unpkg.com/html5-qrcode"></script>
<script>
const resultDiv = document.getElementById('result');
function onScanSuccess(decodedText) {
    fetch("{{ route('organizer.validateTicket') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ qr: decodedText })
    })
    .then(res => res.json())
    .then(data => {
        if(data.valid) {
            resultDiv.innerHTML = '<span style="color:green;font-weight:bold;">Valid Ticket! Check-in successful.</span>';
        } else {
            resultDiv.innerHTML = '<span style="color:red;font-weight:bold;">' + data.message + '</span>';
        }
    });
}
new Html5Qrcode('reader').start(
    { facingMode: 'environment' },
    { fps: 10, qrbox: 250 },
    onScanSuccess
);
</script>
<style>
@media (max-width: 600px) {
    #reader { width: 100% !important; max-width: 100vw !important; }
    h2 { font-size: 1.3rem !important; }
}
</style>
@endsection 
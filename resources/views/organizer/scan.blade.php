@extends('layouts.app')
@section('content')
<h2>Scan Ticket QR</h2>
<div id="reader" style="width:300px"></div>
<div id="result" style="margin-top:1rem;"></div>
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
@endsection 
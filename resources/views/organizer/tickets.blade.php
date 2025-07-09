@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    body {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
    }
    .modern-card {
        background: #fff;
        border-radius: 22px;
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.10);
        padding: 2.5rem 2rem 2rem 2rem;
        margin: 2rem auto 1.5rem auto;
        max-width: 900px;
        transition: box-shadow 0.18s, transform 0.18s, border 0.25s;
        border: 1.5px solid #e0e7ff;
        position: relative;
        overflow: hidden;
    }
    .modern-card:hover {
        box-shadow: 0 16px 48px 0 #a259f744, 0 4px 0 #ff6a8844;
        transform: translateY(-6px) scale(1.025);
        border: 1.5px solid #a259f7;
        z-index: 2;
    }
    .modern-header {
        font-size: 2.2rem;
        font-weight: 800;
        color: #7c3aed;
        margin-bottom: 0.5rem;
        letter-spacing: 0.5px;
        text-shadow: 0 2px 12px #a259f722;
        display: flex;
        align-items: center;
        gap: 0.5em;
        justify-content: center;
    }
    .modern-header .bi {
        color: #a259f7;
        font-size: 1.3em;
    }
    .chart-legend {
        display: inline-block;
        background: linear-gradient(90deg, #6366f1 0%, #06b6d4 100%);
        color: #fff;
        border-radius: 999px;
        font-size: 1em;
        font-weight: 600;
        padding: 0.3em 1.2em;
        margin-left: 1em;
        box-shadow: 0 1.5px 6px #a259f722;
        border: none;
        letter-spacing: 0.2px;
    }
    .section-title {
        font-size: 1.3rem;
        font-weight: 700;
        color: #6366f1;
        margin-bottom: 1.2rem;
        letter-spacing: 0.5px;
        text-align: left;
    }
    .analytics-card {
        border-radius: 1.2rem;
        box-shadow: 0 2px 12px 0 #a259f722;
        transition: box-shadow 0.18s, transform 0.18s;
        background: #f8fafc;
        border: none;
        margin-bottom: 1.2rem;
    }
    .analytics-card:hover {
        box-shadow: 0 8px 32px 0 #a259f744;
        transform: translateY(-3px) scale(1.03);
    }
    .analytics-value {
        font-size: 2.1rem;
        font-weight: 800;
        margin-bottom: 0.2rem;
    }
    .analytics-label {
        font-size: 1.05rem;
        color: #7c3aed;
        font-weight: 600;
        letter-spacing: 0.2px;
    }
    .top-buyers-list li {
        margin-bottom: 0.3rem;
        font-size: 1.08rem;
    }
    .btn-action {
        border-radius: 999px;
        font-weight: 600;
        transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.13s;
        box-shadow: 0 2px 12px 0 #a259f733;
    }
    .btn-action:active, .btn-action:focus {
        background: #a259f7;
        color: #fff;
        box-shadow: 0 4px 18px 0 #a259f799, 0 2px 0 #ff6a8888;
        outline: 2px solid #a259f7;
    }
    @media (max-width: 900px) {
        .modern-header { font-size: 1.5rem; }
        .modern-card { padding: 1.2rem 0.5rem; }
        .analytics-value { font-size: 1.3rem; }
    }
    @media (max-width: 768px) {
        .modern-header { font-size: 1.2rem; }
        .modern-card { padding: 1rem 0.5rem; }
        .analytics-value { font-size: 1.1rem; }
        .section-title { font-size: 1.1rem; }
    }
</style>
<div class="container py-5" style="min-height: 100vh;">
    <h2 class="modern-header mb-5"><i class="bi bi-bar-chart"></i> Organizer Ticket Dashboard</h2>
    <div class="section-title">Key Analytics</div>
    <div class="row mb-4 justify-content-center">
        <div class="col-md-3 mb-2">
            <div class="card analytics-card text-center">
                <div class="card-body">
                    <div class="analytics-label mb-1">Total Revenue</div>
                    <div class="analytics-value text-success">RM {{ number_format($totalRevenue, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-2">
            <div class="card analytics-card text-center">
                <div class="card-body">
                    <div class="analytics-label mb-1">Average Ticket Price</div>
                    <div class="analytics-value text-primary">RM {{ number_format($averagePrice, 2) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-2">
            <div class="card analytics-card text-center">
                <div class="card-body">
                    <div class="analytics-label mb-1">Top Buyers</div>
                    @if($topBuyers->count())
                        <ul class="list-unstyled mb-0 top-buyers-list">
                            @foreach($topBuyers as $buyer)
                                <li>
                                    <span class="fw-semibold">{{ $buyer['user']?->name ?? 'Unknown' }}</span>
                                    <span class="text-muted">({{ $buyer['count'] }} tickets)</span>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <span class="text-muted">No buyers yet</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="section-title">Ticket Sales Chart</div>
    <div class="modern-card mb-4">
        <div class="modern-header mb-2"><i class="bi bi-ticket-perforated"></i> Ticket Sales Overview <span class="chart-legend">Tickets Sold</span></div>
        <p class="mb-4 text-muted">Overview of tickets sold for your events.</p>
        @if ($events->count())
            <canvas id="ticketChart" height="100"></canvas>
        @else
            <div class="alert alert-info mt-4">No ticket data available yet.</div>
        @endif
    </div>
    <div class="d-flex justify-content-end mb-3" style="gap: 0.5rem;">
        <button class="btn btn-outline-primary btn-action" id="exportCsvBtn"><i class="bi bi-file-earmark-spreadsheet"></i> Export CSV</button>
        <button class="btn btn-outline-danger btn-action" id="exportPdfBtn"><i class="bi bi-file-earmark-pdf"></i> Export PDF</button>
        <button class="btn btn-outline-secondary btn-action" id="printReportBtn"><i class="bi bi-printer"></i> Print</button>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.stat-value').forEach(function(el) {
        const target = parseInt(el.textContent.replace(/[^0-9]/g, '')) || 0;
        if (target > 0) {
            let count = 0;
            const step = Math.ceil(target / 40);
            const update = () => {
                count += step;
                if (count >= target) {
                    el.textContent = target;
                } else {
                    el.textContent = count;
                    requestAnimationFrame(update);
                }
            };
            el.textContent = '0';
            update();
        }
    });
});
// CSV Export logic
function downloadCSV(csv, filename) {
    var csvFile = document.createElement('a');
    csvFile.href = 'data:text/csv;charset=utf-8,' + encodeURIComponent(csv);
    csvFile.target = '_blank';
    csvFile.download = filename;
    csvFile.click();
}
function exportTableToCSV(filename) {
    const labels = @json($events->pluck('event_name'));
    const data = @json($events->pluck('tickets_count'));
    let csv = 'Event Name,Tickets Sold\n';
    for (let i = 0; i < labels.length; i++) {
        csv += '"' + labels[i].replace(/"/g, '""') + '",' + data[i] + '\n';
    }
    downloadCSV(csv, filename);
}
document.getElementById('exportCsvBtn').addEventListener('click', function() {
    exportTableToCSV('ticket_report.csv');
});
// Print logic
function printReport() {
    window.print();
}
document.getElementById('printReportBtn').addEventListener('click', printReport);
</script>
<script>
    const ctx = document.getElementById('ticketChart').getContext('2d');

    const chart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: @json($events->pluck('event_name')),
            datasets: [{
                label: 'Tickets Sold',
                data: @json($events->pluck('tickets_count')),
                backgroundColor: 'rgba(99, 102, 241, 0.7)', // blue-violet
                borderColor: 'rgba(99, 102, 241, 1)',
                borderRadius: 12,
                borderSkipped: false,
                maxBarThickness: 60,
            }]
        },
        options: {
            responsive: true,
            animation: {
                duration: 1200,
                easing: 'easeOutQuart'
            },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#6366f1',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#a259f7',
                    borderWidth: 1,
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#222', font: { weight: 600 } }
                },
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                    grid: { color: '#e0e7ff' },
                    ticks: { color: '#222', font: { weight: 600 } }
                }
            }
        }
    });
</script>
<script>
document.getElementById('exportPdfBtn').addEventListener('click', function() {
    const chartCanvas = document.getElementById('ticketChart');
    html2canvas(chartCanvas).then(function(canvas) {
        const imgData = canvas.toDataURL('image/png');
        const pdf = new window.jspdf.jsPDF({ orientation: 'landscape' });
        const pageWidth = pdf.internal.pageSize.getWidth();
        const pageHeight = pdf.internal.pageSize.getHeight();
        // Fit image to page
        pdf.addImage(imgData, 'PNG', 10, 10, pageWidth-20, (canvas.height * (pageWidth-20)) / canvas.width);
        pdf.save('ticket_sales_chart.pdf');
    });
});
</script>
@endpush
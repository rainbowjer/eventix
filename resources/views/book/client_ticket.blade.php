@extends('layouts.app')

@section('content')
<div class="container py-5 d-flex flex-column align-items-center">
    <div class="mb-4 text-center">
        <h2><i class="bi bi-ticket-perforated"></i> Your Ticket</h2>
        <p id="loading-message">Your ticket is being generated. Please wait...</p>
    </div>
    
    <div id="ticket-container" class="w-100" style="display: none; max-width: 500px;">
        <!-- Ticket will be rendered here -->
    </div>
    
    <button id="download-pdf" class="btn btn-primary mt-4 w-100 w-md-auto" style="display: none;">
        <i class="bi bi-download"></i> Download Ticket
    </button>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        try {
            // Ticket data from backend
            const ticketData = @json($ticketData);
            
            // Format ticket HTML
        const ticketHTML = `
            <div class="ticket-card" style="background: #fff; border-radius: 18px; box-shadow: 0 4px 24px rgba(60,72,88,0.10); padding: 32px 28px 24px 28px; position: relative; width: 100%; max-width: 500px;">
                <div class="header" style="text-align: center; margin-bottom: 18px;">
                    <img src="/eventix-logo.png" style="max-height: 48px; margin-bottom: 8px; max-width: 100%; height: auto;" alt="EventiX Logo">
                    <div style="font-size: 1.6rem; font-weight: 700; color: #22223b; margin-bottom: 6px;">${ticketData.event_name}</div>
                </div>
                <div style="display: flex; flex-wrap: wrap; justify-content: space-between; align-items: flex-start; margin-top: 18px;">
                    <div style="flex: 1 1 180px; min-width: 180px;">
                        <div style="font-size: 1.05rem; color: #444; margin-bottom: 4px;"><strong>Date:</strong> ${ticketData.event_date}</div>
                        <div style="font-size: 1.05rem; color: #444; margin-bottom: 4px;"><strong>Time:</strong> ${ticketData.event_time}</div>
                        <div style="font-size: 1.05rem; color: #444; margin-bottom: 4px;"><strong>Venue:</strong> ${ticketData.location}</div>
                        <div style="font-size: 1.05rem; color: #444; margin-bottom: 4px;"><strong>Seat:</strong> ${ticketData.seat_label}</div>
                        <div style="font-size: 1.05rem; color: #444; margin-bottom: 4px;"><strong>Price:</strong> RM${ticketData.amount}</div>
                        <div style="font-size: 1.05rem; color: #444; margin-bottom: 4px;"><strong>Ticket ID:</strong> ${ticketData.ticket_id}</div>
                    </div>
                    <div style="flex: 0 0 140px; text-align: center; width: 100%; max-width: 140px; margin: 0 auto;">
                        <div id="qrcode" style="border: 4px solid #e0e7ff; border-radius: 12px; background: #f8fafc; width: 140px; height: 140px; margin: 0 auto;"></div>
                    </div>
                </div>
                <div style="text-align: center; margin-top: 28px; font-size: 0.98rem; color: #888;">
                    Please present this ticket at the event entrance.<br>
                    <strong>Thank you for choosing EventiX!</strong>
                </div>
            </div>
        `;
        
        // Render ticket
        document.getElementById('ticket-container').innerHTML = ticketHTML;
        document.getElementById('ticket-container').style.display = 'block';
        
        // Hide loading message
        document.getElementById('loading-message').style.display = 'none';
        
        // Generate QR code
        new QRCode(document.getElementById("qrcode"), {
            text: ticketData.qr_payload,
            width: 132,
            height: 132,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
        
        // Show download button
        document.getElementById('download-pdf').style.display = 'block';
        
        // PDF download handler
        document.getElementById('download-pdf').addEventListener('click', function() {
            // Use html2canvas to capture the ticket
            html2canvas(document.querySelector('.ticket-card'), {
                scale: 2, // Higher quality
                useCORS: true,
                logging: false
            }).then(canvas => {
                const imgData = canvas.toDataURL('image/png');
                
                // Create PDF with jsPDF
                const { jsPDF } = window.jspdf;
                const pdf = new jsPDF('p', 'mm', 'a4');
                
                // Calculate dimensions to fit on A4
                const imgProps = pdf.getImageProperties(imgData);
                const pdfWidth = pdf.internal.pageSize.getWidth();
                const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                
                // Add image to PDF
                pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                
                // Download PDF
                pdf.save(`ticket-${ticketData.id}.pdf`);
            });
        });
        } catch (error) {
            console.error('Error generating ticket:', error);
            document.getElementById('loading-message').innerHTML = '<span class="text-danger">Unable to generate ticket. Please try again later.</span>';
        }
    });
</script>
@endpush
@endsection

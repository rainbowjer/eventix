<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>

@if(isset($qrDataUri))
---
**Scan this QR code at the event entrance:**

<img src="{{ $qrDataUri }}" alt="Ticket QR Code" style="max-width:200px; margin: 16px 0;">
@endif

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

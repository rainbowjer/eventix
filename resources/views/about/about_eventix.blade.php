@extends('layouts.app')
@section('content')
<style>
.text-gradient {
    background: linear-gradient(90deg, #d68de1, #883aff);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}
</style>
<div class="bg-gradient p-5 mb-4 rounded-3" style="background: linear-gradient(90deg, #d68de1 0%, #883aff 100%); min-height: 220px; display: flex; align-items: center; justify-content: center;">
    <div class="text-center" style="color: #111;">
        <img src="{{ asset('images/eventixlogo.png') }}" alt="EventiX Logo" class="mb-3" style="height: 100px; filter: drop-shadow(0 0 16px #fff6);">
        <h1 class="display-4 fw-bold" style="color: #111;">About EventiX</h1>
        <p class="lead mb-0" style="color: #111;">Connecting People with Great Experiences</p>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h2 class="h4 fw-semibold mb-3 text-primary">Our Mission</h2>
                    <p>EventiX is a modern event management and ticketing platform designed to make event discovery, booking, and management easy for users and organizers. Our mission is to connect people with great experiences and empower organizers to reach their audience efficiently.</p>
                    <h2 class="h5 fw-semibold mt-4 mb-2 text-success">Why Choose EventiX?</h2>
                    <ul class="list-unstyled mb-0">
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Seamless event discovery and booking</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Secure and easy ticket management</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Powerful tools for event organizers</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> No hidden fees for users</li>
                        <li class="mb-2"><i class="bi bi-check-circle-fill text-success me-2"></i> Dedicated support team</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
</div>
@endsection 
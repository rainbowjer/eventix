@extends('layouts.app')
@section('content')
<div class="bg-gradient p-5 mb-4 rounded-3" style="background: linear-gradient(90deg, #d68de1 0%, #883aff 100%); min-height: 180px; display: flex; align-items: center; justify-content: center;">
    <div class="text-center w-100" style="color: #111;">
        <h1 class="display-5 fw-bold mb-0" style="color: #111;">Contact Us</h1>
        <p class="lead" style="color: #111;">We'd love to hear from you!</p>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-body p-4">
                    <h2 class="h5 fw-semibold mb-3 text-primary">Get in Touch</h2>
                    <ul class="list-unstyled fs-5 mb-4">
                        <li class="mb-3"><i class="bi bi-envelope-fill text-primary me-2"></i> <strong>Email:</strong> support@eventix.com</li>
                        <li class="mb-3"><i class="bi bi-telephone-fill text-success me-2"></i> <strong>Phone:</strong> +60 111 8882950 </li>
                        <li class="mb-3"><i class="bi bi-geo-alt-fill text-danger me-2"></i> <strong>Address:</strong> Shah Alam, Selangor</li>
                    </ul>
                    <!-- Optional: Contact form placeholder -->
                    <!--
                    <form>
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Name</label>
                            <input type="text" class="form-control" id="name" placeholder="Enter your name">
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Your Email</label>
                            <input type="email" class="form-control" id="email" placeholder="Enter your email">
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control" id="message" rows="4" placeholder="Type your message..."></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Send Message</button>
                    </form>
                    -->
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
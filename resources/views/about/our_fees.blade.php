@extends('layouts.app')
@section('content')
<div class="bg-gradient p-5 mb-4 rounded-3" style="background: linear-gradient(90deg, #d68de1 0%, #883aff 100%); min-height: 180px; display: flex; align-items: center; justify-content: center;">
    <div class="text-center w-100" style="color: #111;">
        <h1 class="display-5 fw-bold mb-0" style="color: #111;">Our Fees</h1>
        <p class="lead" style="color: #111;">Enjoy EventiX with absolutely no extra charges!</p>
    </div>
</div>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-7">
            <div class="card shadow-lg border-0 text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="bi bi-cash-stack text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h2 class="h4 fw-bold text-success mb-3">No Fees for Users</h2>
                    <p class="fs-5 mb-0">EventiX is proud to offer a <span class="fw-bold text-success">no-fee</span> experience for all users. Book, manage, and enjoy your events without worrying about hidden charges or extra costs. 100% transparent, always!</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
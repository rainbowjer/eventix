@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
    .profile-bg {
        background: linear-gradient(135deg, #f8fafc 0%, #e0e7ff 100%);
        min-height: 100vh;
        padding-top: 2rem;
        padding-bottom: 2rem;
    }
    .profile-card {
        background: linear-gradient(120deg, #f8f8ff 0%, #f3eaff 100%);
        border-radius: 22px;
        box-shadow: 0 8px 32px 0 rgba(162,89,247,0.10), 0 1.5px 0 #ff6a8822;
        padding: 2rem 1.5rem;
        margin-bottom: 2rem;
        backdrop-filter: blur(8px) saturate(1.2);
        border: 2.5px solid transparent;
        background-clip: padding-box;
        transition: box-shadow 0.2s, transform 0.2s, border 0.3s, background 1.2s linear;
        position: relative;
        overflow: visible;
    }
    .profile-card:hover {
        box-shadow: 0 12px 40px 0 #a259f744, 0 2px 0 #ff6a8844;
        transform: translateY(-2px) scale(1.01);
        border: 2.5px solid #a259f7;
        background: linear-gradient(120deg, #f3eaff 0%, #f8f8ff 100%);
    }
    .profile-avatar {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 50%;
        border: 4px solid #a259f7;
        box-shadow: 0 2px 8px #a259f722;
        margin-bottom: 1rem;
        background: #f3eaff;
    }
    .role-badge {
        background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
        color: #fff;
        border-radius: 12px;
        font-size: 1rem;
        padding: 0.4em 1em;
        font-weight: 600;
        margin-bottom: 1rem;
        display: inline-block;
        box-shadow: 0 2px 8px #a259f722;
        letter-spacing: 0.5px;
        cursor: pointer;
        position: relative;
    }
    .role-badge[tabindex] { outline: none; }
    .role-badge:hover .role-tooltip, .role-badge:focus .role-tooltip {
        opacity: 1;
        pointer-events: auto;
        transform: translateY(-110%) scale(1);
    }
    .role-tooltip {
        position: absolute;
        left: 50%;
        top: 0;
        transform: translate(-50%, -120%) scale(0.98);
        background: #fff;
        color: #222;
        border-radius: 8px;
        box-shadow: 0 2px 12px #a259f733;
        padding: 0.5em 1em;
        font-size: 0.98em;
        font-weight: 500;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s, transform 0.2s;
        z-index: 10;
        min-width: 120px;
    }
    .profile-info-list i {
        color: #a259f7;
        margin-right: 0.5em;
        font-size: 1.2em;
        vertical-align: middle;
        transition: color 0.2s;
    }
    .profile-info-list .bi-envelope { color: #6366f1; }
    .profile-info-list .bi-calendar-check { color: #06b6d4; }
    .profile-info-list .bi-clock-history { color: #fbbf24; }
    .profile-upload-btn {
        background: linear-gradient(90deg, #4f2cc6 0%, #a259f7 100%);
        color: #fff;
        border: none;
        border-radius: 2rem;
        font-weight: 700;
        font-size: 1.08rem;
        padding: 0.5rem 2rem;
        box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #ff6a8888;
        letter-spacing: 1px;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
        margin-top: 0.5rem;
        outline: none;
        position: relative;
        overflow: hidden;
    }
    .profile-upload-btn:active, .profile-upload-btn:focus {
        background: linear-gradient(90deg, #a259f7 0%, #4f2cc6 100%);
        color: #fff;
        box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
        transform: scale(1.04);
        outline: 2px solid #a259f7;
    }
    .profile-upload-btn .ripple {
        position: absolute;
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s linear;
        background: rgba(162,89,247,0.25);
        pointer-events: none;
        z-index: 2;
    }
    @keyframes ripple {
        to { transform: scale(2.5); opacity: 0; }
    }
    .profile-progress {
        background: #f3eaff;
        border-radius: 12px;
        height: 16px;
        margin-bottom: 1.2rem;
        overflow: hidden;
        box-shadow: 0 2px 8px #a259f722;
        position: relative;
        cursor: pointer;
    }
    .profile-progress-bar {
        background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
        height: 100%;
        border-radius: 12px;
        transition: width 0.8s cubic-bezier(.4,0,.2,1);
    }
    .progress-tooltip {
        position: absolute;
        left: 50%;
        top: -38px;
        transform: translateX(-50%) scale(0.98);
        background: #fff;
        color: #222;
        border-radius: 8px;
        box-shadow: 0 2px 12px #a259f733;
        padding: 0.5em 1em;
        font-size: 0.98em;
        font-weight: 500;
        opacity: 0;
        pointer-events: none;
        transition: opacity 0.2s, transform 0.2s;
        z-index: 10;
        min-width: 120px;
    }
    .profile-progress:hover .progress-tooltip, .profile-progress:focus .progress-tooltip {
        opacity: 1;
        pointer-events: auto;
        transform: translateX(-50%) scale(1);
    }
    .form-control:focus {
        border-color: #a259f7;
        box-shadow: 0 0 0 2px #a259f755;
        outline: none;
        transition: box-shadow 0.2s, border-color 0.2s;
    }
    .password-card .form-label {
        font-weight: 600;
    }
    .password-card .btn {
        border-radius: 2rem;
        font-weight: 700;
        padding: 0.5rem 2rem;
        background: linear-gradient(90deg, #ff6a88 0%, #a259f7 100%);
        color: #fff;
        border: none;
        box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #a259f788;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
        outline: none;
        position: relative;
        overflow: hidden;
    }
    .password-card .btn:active, .password-card .btn:focus {
        background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
        color: #fff;
        box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
        transform: scale(1.04);
        outline: 2px solid #a259f7;
    }
    .password-card .btn .ripple {
        position: absolute;
        border-radius: 50%;
        transform: scale(0);
        animation: ripple 0.6s linear;
        background: rgba(255,106,136,0.18);
        pointer-events: none;
        z-index: 2;
    }
    .btn[aria-label], .profile-upload-btn[aria-label] {
        outline: 2px solid #a259f7;
    }
    .toast {
        position: fixed;
        top: 1.5rem;
        right: 1.5rem;
        min-width: 220px;
        z-index: 9999;
        background: #fff;
        border-radius: 1rem;
        box-shadow: 0 4px 24px #a259f733;
        padding: 1rem 1.5rem;
        color: #222;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.7em;
        opacity: 0.98;
        border-left: 6px solid #a259f7;
        animation: toastIn 0.5s;
    }
    @keyframes toastIn {
        0% { opacity: 0; transform: translateY(-30px); }
        100% { opacity: 0.98; transform: translateY(0); }
    }
    @media (max-width: 900px) {
        .profile-card { padding: 1rem 0.5rem; }
        .profile-avatar { width: 80px; height: 80px; }
    }
    @media (max-width: 768px) {
        .profile-bg { padding-top: 1rem; padding-bottom: 1rem; }
        .profile-card { padding: 1rem 0.5rem; }
        .profile-avatar { width: 80px; height: 80px; }
        .profile-upload-btn, .btn, .form-control { width: 100%; margin-bottom: 0.5em; }
        .row.g-4 { flex-direction: column; }
        .col-md-4, .col-md-8, .col-md-7 { max-width: 100%; flex: 0 0 100%; }
    }
</style>
<script>
// Avatar upload preview
function previewAvatar(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
// Button ripple effect
function addRipple(e) {
    const btn = e.currentTarget;
    const circle = document.createElement('span');
    circle.className = 'ripple';
    const rect = btn.getBoundingClientRect();
    circle.style.width = circle.style.height = Math.max(rect.width, rect.height) + 'px';
    circle.style.left = (e.clientX - rect.left - rect.width/2) + 'px';
    circle.style.top = (e.clientY - rect.top - rect.height/2) + 'px';
    btn.appendChild(circle);
    setTimeout(()=>circle.remove(), 600);
}
window.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.profile-upload-btn, .password-card .btn').forEach(btn => {
        btn.addEventListener('click', addRipple);
    });
});
</script>
<div class="profile-bg">
<div class="container py-5">
    @if(session('status'))
        <div class="toast" id="profileToast" role="alert" aria-live="assertive" aria-atomic="true">
            <i class="bi bi-check-circle-fill text-success"></i> {{ session('status') }}
        </div>
        <script>setTimeout(()=>{document.getElementById('profileToast').style.display='none'}, 3500);</script>
    @endif
    <div class="text-center mb-5">
        <img src="{{ asset('images/eventixlogo.png') }}" class="rounded-circle shadow" width="100" alt="Profile Avatar">
        <h2 class="fw-bold text-dark mt-3">Welcome, {{ $user->name }} <span class="wave">👋</span></h2>
        <p class="text-muted">Manage your profile information, password, and account settings below.</p>
    </div>
    <ul class="nav nav-tabs mb-4" id="profileTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile" type="button" role="tab" aria-label="Profile Tab"><i class="bi bi-person"></i> Profile</button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link text-danger" id="delete-tab" data-bs-toggle="tab" data-bs-target="#delete" type="button" role="tab" aria-label="Delete Tab"><i class="bi bi-trash"></i> Delete</button>
        </li>
    </ul>
    <div class="tab-content border-0" id="profileTabsContent">
        {{-- Profile Tab --}}
        <div class="tab-pane fade show active" id="profile" role="tabpanel">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="profile-card text-center">
                        @if ($user->profile_picture)
                            <img id="avatarPreview" src="{{ asset('storage/' . $user->profile_picture) }}" class="profile-avatar" alt="Profile Picture" aria-label="Profile Picture">
                        @else
                            <img id="avatarPreview" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=a259f7&color=fff&size=120" class="profile-avatar" alt="No Profile Picture" aria-label="No Profile Picture">
                        @endif
                        <div class="role-badge" tabindex="0">
                            <i class="bi bi-person-badge"></i> {{ ucfirst($user->role) }}
                            <span class="role-tooltip">This is your account role: {{ ucfirst($user->role) }}</span>
                        </div>
                        <form action="{{ route('user.profile.upload') }}" method="POST" enctype="multipart/form-data" class="mt-3">
                            @csrf
                            <input type="file" name="profile_picture" class="form-control mb-2" required aria-label="Upload Profile Picture" onchange="previewAvatar(this)">
                            <button type="submit" class="profile-upload-btn" aria-label="Upload">Upload</button>
                        </form>
                        @if ($user->profile_picture)
                            <form action="{{ route('user.profile.deletePicture') }}" method="POST" class="mt-2">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" aria-label="Remove Picture">Remove Picture</button>
                            </form>
                        @endif
                    </div>
                    <div class="profile-card mt-4">
                        <h3 class="fw-bold mb-2">{{ $user->name }}</h3>
                        <ul class="list-unstyled profile-info-list mb-3">
                            <li><i class="bi bi-envelope"></i> <strong>Email:</strong> {{ $user->email }}</li>
                            <li><i class="bi bi-calendar-check"></i> <strong>Registered:</strong> {{ $user->created_at->format('d M Y') }}</li>
                            @if($user->last_login_at)
                                <li><i class="bi bi-clock-history"></i> <strong>Last Login:</strong> {{ \Carbon\Carbon::parse($user->last_login_at)->format('d M Y, h:i A') }}</li>
                            @endif
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <div class="profile-card mb-4 password-card">
                        <div class="card-header bg-white border-0 fw-semibold mb-2" style="font-size:1.1rem;">Update Profile Information</div>
                        <form method="POST" action="{{ route('user.profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="mb-3">
                                <label for="name">Name</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required aria-label="Edit Name">
                            </div>
                            <div class="mb-3">
                                <label for="email">Email</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required aria-label="Edit Email">
                            </div>
                            <div class="mb-3">
                                <label for="phone_number">Phone Number</label>
                                <input type="text" name="phone_number" class="form-control" value="{{ old('phone_number', $user->phone_number) }}" required aria-label="Edit Phone Number" placeholder="+60123456789">
                            </div>
                            <button type="submit" class="btn" aria-label="Update Info"><i class="bi bi-key"></i> Update Info</button>
                        </form>
                    </div>
                    <div class="profile-card mb-4 password-card">
                        <div class="card-header bg-white border-0 fw-semibold mb-2" style="font-size:1.1rem;">Change Password</div>
                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PATCH')
                            <div class="mb-3">
                                <label for="current_password" class="form-label"><i class="bi bi-lock"></i> Current Password</label>
                                <input id="current_password" type="password" name="current_password" class="form-control" required aria-label="Current Password">
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label"><i class="bi bi-shield-lock"></i> New Password</label>
                                <input id="password" type="password" name="password" class="form-control" required aria-label="New Password">
                            </div>
                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label"><i class="bi bi-shield-check"></i> Confirm Password</label>
                                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required aria-label="Confirm Password">
                            </div>
                            <button type="submit" class="btn" aria-label="Update Password"><i class="bi bi-key"></i> Update Password</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- Delete Tab --}}
        <div class="tab-pane fade" id="delete" role="tabpanel">
            <div class="row justify-content-center">
                <div class="col-md-7">
                    <div class="profile-card mb-4">
                        <div class="card-header bg-white border-0 fw-semibold mb-2 text-danger" style="font-size:1.1rem;">Delete Account</div>
                        <p>Once your account is deleted, all of its resources and data will be permanently removed. Please back up anything important before proceeding.</p>
                        <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteAccountModal" aria-label="Delete My Account">Delete My Account</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteAccountModalLabel">Confirm Account Deletion</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        Are you sure you want to delete your account permanently?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <form action="{{ route('profile.destroy') }}" method="POST" class="d-inline">
          @csrf
          @method('DELETE')
          <button type="submit" class="btn btn-danger">Yes, Delete</button>
        </form>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
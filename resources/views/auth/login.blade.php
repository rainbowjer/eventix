@php
// Cloud/wave split login: white card with wavy SVG, purple/blue gradient bg, icons in inputs, glowing button, responsive
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EventiX Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700&family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            font-family: 'Inter', Arial, sans-serif;
            background: transparent;
            min-height: 100vh;
        }
        .split-modern-outer {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f3f0ff;
        }
        .split-modern-card {
            display: flex;
            flex-direction: row;
            width: 98vw;
            max-width: 950px;
            min-height: 480px;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.13);
            overflow: hidden;
            background: rgba(255,255,255,0.18);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .split-modern-left {
            flex: 1;
            background: linear-gradient(120deg, #a259f7 0%, #ff6a88 100%);
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            padding: 3.2rem 2.5rem 3.2rem 2.8rem;
            color: #fff;
        }
        .split-modern-abstract {
            position: absolute;
            left: 0; top: 0; width: 100%; height: 100%;
            z-index: 0;
            pointer-events: none;
        }
        .split-modern-abstract ellipse, .split-modern-abstract rect {
            animation: floatY 6s ease-in-out infinite alternate;
        }
        .split-modern-abstract ellipse:nth-child(2), .split-modern-abstract rect:nth-child(2) {
            animation-delay: 2s;
        }
        .split-modern-abstract ellipse:nth-child(3), .split-modern-abstract rect:nth-child(3) {
            animation-delay: 4s;
        }
        @keyframes floatY {
            0% { transform: translateY(0); }
            100% { transform: translateY(18px); }
        }
        .split-modern-welcome {
            position: relative;
            z-index: 1;
            max-width: 420px;
        }
        .split-modern-welcome h1 {
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 2.3rem;
            font-weight: 700;
            margin-bottom: 1.1rem;
            color: #fff;
            letter-spacing: 1px;
        }
        .split-modern-welcome p {
            font-size: 1.08rem;
            font-weight: 400;
            color: #f3f0ff;
            margin-bottom: 0;
        }
        .split-modern-right {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            padding: 2.5rem 2rem;
        }
        .split-modern-login-card {
            width: 100%;
            max-width: 340px;
            margin: 0 auto;
        }
        .split-modern-login-title {
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 1.1rem;
            font-weight: 700;
            color: #a259f7;
            letter-spacing: 1px;
            margin-bottom: 2.2rem;
            text-align: center;
        }
        .split-modern-form-group {
            position: relative;
            margin-bottom: 1.3rem;
            display: flex;
            align-items: center;
        }
        .split-modern-input {
            width: 100%;
            background: #f3f0ff;
            border: 2px solid #a259f7;
            border-radius: 2.2rem;
            color: #2d1764;
            font-size: 1.08rem;
            padding: 0.9rem 1.2rem 0.9rem 3.2rem;
            outline: none;
            box-shadow: 0 2px 12px 0 rgba(123,47,242,0.10);
            transition: box-shadow 0.2s, background 0.2s, border 0.2s;
        }
        .split-modern-input:focus {
            background: #fff;
            box-shadow: 0 0 0 4px #a259f7cc, 0 2px 12px rgba(123,47,242,0.18);
            border-color: #ff6a88;
        }
        .split-modern-icon {
            position: absolute;
            left: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a259f7;
            font-size: 1.2rem;
            pointer-events: none;
            background: transparent;
            z-index: 2;
        }
        .split-modern-login-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.1rem;
            font-size: 0.98rem;
        }
        .split-modern-login-link {
            color: #a259f7;
            text-decoration: underline;
            font-size: 0.98rem;
        }
        .split-modern-login-link:hover {
            color: #ff6a88;
        }
        .split-modern-login-btn {
            width: 100%;
            background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
            color: #fff;
            border: none;
            border-radius: 1.5rem;
            font-weight: 700;
            font-size: 1.08rem;
            padding: 0.9rem 0;
            margin-top: 0.2rem;
            box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #ff6a8888;
            letter-spacing: 1px;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
        }
        .split-modern-login-btn:hover, .split-modern-login-btn:focus {
            background: linear-gradient(90deg, #ff6a88 0%, #a259f7 100%);
            color: #fff;
            box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
            transform: scale(1.04);
        }
        .split-modern-login-btn:active {
            transform: scale(0.98);
        }
        .split-modern-login-register-row {
            text-align: center;
            margin-top: 1.2rem;
            font-size: 0.98rem;
            color: #2d1764;
        }
        .split-modern-login-register-row a {
            color: #a259f7;
            text-decoration: underline;
            margin-left: 0.2rem;
        }
        .split-modern-login-register-row a:hover {
            color: #ff6a88;
        }
        .split-modern-eye {
            position: absolute;
            right: 1.1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a259f7;
            font-size: 1.2rem;
            cursor: pointer;
            z-index: 2;
            background: transparent;
        }
        @media (max-width: 900px) {
            .split-modern-card {
                flex-direction: column;
                min-width: 0;
                max-width: 99vw;
                border-radius: 1.2rem;
            }
            .split-modern-left, .split-modern-right {
                padding: 1.2rem 0.7rem;
            }
        }
        @media (max-width: 600px) {
            .split-modern-card {
                padding: 0.1rem 0.05rem;
                margin: 0.3rem 0 0.3rem 0;
                min-height: 0;
                max-width: 92vw;
                border-radius: 10px;
            }
            .split-modern-left, .split-modern-right {
                padding: 0.3rem 0.05rem;
            }
            .split-modern-welcome h1 {
                font-size: 1rem;
                margin-bottom: 0.5rem;
            }
            .split-modern-welcome p {
                font-size: 0.93rem;
            }
            .split-modern-login-card {
                max-width: 92vw;
                padding: 0.5rem 0.05rem;
            }
            .split-modern-login-title {
                font-size: 0.98rem;
                margin-bottom: 1.2rem;
            }
            .login-logo, .split-modern-welcome img {
                height: 48px !important;
                max-width: 90vw !important;
                margin-bottom: 1rem !important;
            }
            .split-modern-form-group {
                margin-bottom: 0.7rem;
            }
            .split-modern-input {
                font-size: 0.89rem;
                padding: 0.6rem 0.8rem 0.6rem 2.4rem;
            }
            .split-modern-login-btn {
                font-size: 0.98rem;
                padding: 0.7rem 0;
                margin-top: 0.1rem;
            }
            .split-modern-login-register-row {
                font-size: 0.93rem;
                margin-top: 0.7rem;
            }
        }
        body {
            background: linear-gradient(120deg, #d68de1 0%, #883aff 100%) !important;
        }
        .login-card {
            width: 100%;
            max-width: 370px;
            margin: 5vh auto;
            border-radius: 28px;
            padding: 2.2rem 1.2rem 1.5rem 1.2rem;
            box-shadow: 0 8px 32px 0 rgba(162,89,247,0.18);
            background: #fff;
            border: none;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .login-logo {
            height: 60px;
            margin-bottom: 1.2rem;
            max-width: 80vw;
            width: auto;
        }
        .login-card h1, .login-card h2 {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.7rem;
            text-align: center;
        }
        .form-control {
            border-radius: 1.5rem;
            border: 1.2px solid #d1c4e9;
            font-size: 1.08rem;
            padding: 0.85rem 1.1rem;
            margin-bottom: 1.1rem;
            background: #fafaff;
            box-shadow: none;
            width: 100%;
            min-width: 0;
        }
        .form-control:focus {
            border-color: #a259f7;
            box-shadow: 0 0 0 2px #a259f733;
        }
        .btn-gradient {
            background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
            color: #fff;
            border: none;
            border-radius: 2rem;
            font-weight: 700;
            font-size: 1.13rem;
            padding: 0.9rem 1.2rem;
            width: 100%;
            margin-bottom: 0.7rem;
            box-shadow: 0 4px 18px 0 #a259f733;
            transition: background 0.18s, color 0.18s, box-shadow 0.18s, transform 0.13s;
        }
        .btn-gradient:active, .btn-gradient:focus {
            background: linear-gradient(90deg, #ff6a88 0%, #a259f7 100%);
            color: #fff;
            box-shadow: 0 6px 24px 0 #a259f799;
            outline: none;
        }
        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 1.2rem 0 0.7rem 0;
        }
        .divider::before, .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1.2px solid #e0e0e0;
        }
        .divider:not(:empty)::before {
            margin-right: .75em;
        }
        .divider:not(:empty)::after {
            margin-left: .75em;
        }
        .social-login {
            display: flex;
            justify-content: center;
            gap: 1.2rem;
            margin-top: 0.5rem;
        }
        .social-login a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: #f3f0ff;
            color: #fff;
            font-size: 1.3rem;
            box-shadow: 0 2px 8px #a259f722;
            transition: background 0.18s, transform 0.13s;
        }
        .social-login a.facebook { background: #3b5998; }
        .social-login a.google { background: #db4437; }
        .social-login a.twitter { background: #1da1f2; }
        .social-login a:hover { transform: scale(1.08); }
        @media (max-width: 991.98px) {
            .login-card {
                max-width: 95vw;
                padding: 1.5rem 0.7rem 1rem 0.7rem;
                border-radius: 20px;
            }
            .login-logo {
                height: 48px !important;
            }
            .login-card h1, .login-card h2 {
                font-size: 1.08rem !important;
            }
            .form-control, .btn-gradient {
                font-size: 0.98rem !important;
                padding: 0.7rem 1rem !important;
            }
        }
        @media (max-width: 600px) {
            .login-card {
                max-width: 99vw;
                padding: 1.1rem 0.3rem 0.7rem 0.3rem !important;
                margin: 2vh auto;
                border-radius: 16px;
            }
            .login-logo {
                height: 38px !important;
            }
            .login-card h1, .login-card h2 {
                font-size: 0.98rem !important;
            }
            .form-control, .btn-gradient {
                font-size: 0.93rem !important;
                padding: 0.6rem 0.7rem !important;
            }
            .social-login a {
                width: 32px;
                height: 32px;
                font-size: 1.1rem;
            }
        }
        /* --- FORCE FULL HEIGHT AND CENTERING --- */
        html, body {
            height: 100% !important;
            min-height: 100vh !important;
        }
        .container-fluid {
            min-height: 100vh !important;
            height: 100vh !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }
        @media (max-width: 600px) {
            html, body {
                overflow-y: auto !important;
            }
        }
    </style>
</head>
<body>
<!-- Concert Photo Background -->
<div class="concert-bg-overlay"></div>
<style>
.concert-bg-overlay {
  position: fixed;
  top: 0; left: 0; width: 100vw; height: 100vh;
  z-index: 0;
  pointer-events: none;
  background: url('https://images.unsplash.com/photo-1464983953574-0892a716854b?auto=format&fit=crop&w=1600&q=80') center center/cover no-repeat;
}
.concert-bg-overlay::after {
  content: '';
  position: absolute;
  top: 0; left: 0; width: 100%; height: 100%;
  background: linear-gradient(120deg, rgba(162,89,247,0.55) 0%, rgba(243,123,241,0.45) 100%);
  z-index: 1;
}
</style>
<div class="container-fluid px-0 min-vh-100 d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center align-items-center g-0">
        <div class="col-12 col-lg-10">
            <div class="split-modern-card flex-column flex-md-row">
                <div class="split-modern-left col-12 col-md-6">
                    
                    <div class="split-modern-welcome">
                        <div style="display:flex;justify-content:center;align-items:center;margin-bottom:1.2rem;">
                            <img src="{{ asset('images/eventixlogo.png') }}" alt="EventiX Logo" style="height:80px;max-width:90vw;border-radius:12px;box-shadow:0 2px 12px rgba(123,47,242,0.10);background:rgba(255, 255, 255, 0);">
                        </div>
                        <h1>Welcome to EventiX</h1>
                        <p>Book, manage, and resell your event tickets with ease. Join the EventiX community and experience seamless ticketing!</p>
                    </div>
                </div>
                <div class="split-modern-right col-12 col-md-6 d-flex align-items-center justify-content-center">
                    <div class="split-modern-login-card w-100">
                        <div class="split-modern-login-title">USER LOGIN</div>
                        <x-auth-session-status class="mb-4" :status="session('status')" />
                        <form method="POST" action="{{ route('login') }}" autocomplete="off">
                            @csrf
                            <div class="split-modern-form-group">
                                <span class="split-modern-icon"><i class="bi bi-person"></i></span>
                                <input id="email" class="split-modern-input" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Username or email">
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>
                            <div class="split-modern-form-group" style="position:relative;">
                                <span class="split-modern-icon"><i class="bi bi-lock"></i></span>
                                <input id="password" class="split-modern-input" type="password" name="password" required autocomplete="current-password" placeholder="Password">
                                <span class="split-modern-eye" id="togglePassword"><i class="bi bi-eye"></i></span>
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>
                            <div class="split-modern-login-links">
                                <div class="form-check" style="margin-bottom:0;">
                                    <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                                    <label for="remember_me" class="form-check-label">Remember</label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="split-modern-login-link" href="{{ route('password.request') }}">Forgot password?</a>
                                @endif
                            </div>
                            <button type="submit" class="split-modern-login-btn">LOGIN</button>
                        </form>
                        <div class="split-modern-login-register-row">
                            Don't have an account? <a href="{{ route('register') }}">Create Account</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
// Show/hide password toggle
const togglePassword = document.getElementById('togglePassword');
const passwordInput = document.getElementById('password');
if (togglePassword && passwordInput) {
    togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.innerHTML = type === 'password' ? '<i class="bi bi-eye"></i>' : '<i class="bi bi-eye-slash"></i>';
    });
}
</script>
</body>
</html>

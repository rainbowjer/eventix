<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - EventiX</title>
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
        .reset-outer {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }
        .reset-card {
            width: 98vw;
            max-width: 400px;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.13);
            overflow: hidden;
            background: rgba(255,255,255,0.18);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            padding: 2.5rem 2rem 2.2rem 2rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .reset-logo {
            margin-bottom: 1.2rem;
        }
        .reset-logo img {
            height: 80px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(123,47,242,0.10);
            background: rgba(255,255,255,0);
        }
        .reset-title {
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: rgb(115, 18, 227);
            margin-bottom: 0.7rem;
            text-align: center;
            letter-spacing: 1px;
        }
        .reset-desc {
            font-size: 1.05rem;
            color: #2d1764;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .reset-form {
            width: 100%;
        }
        .reset-form-group {
            position: relative;
            margin-bottom: 1.3rem;
            display: flex;
            align-items: center;
        }
        .reset-input {
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
        .reset-input:focus {
            background: #fff;
            box-shadow: 0 0 0 4px #a259f7cc, 0 2px 12px rgba(123,47,242,0.18);
            border-color: #ff6a88;
        }
        .reset-icon {
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
        .reset-icon-eye {
            left: auto;
            right: 1.1rem;
            cursor: pointer;
            pointer-events: auto;
        }
        .reset-btn {
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
        .reset-btn:hover, .reset-btn:focus {
            background: linear-gradient(90deg, #ff6a88 0%, #a259f7 100%);
            color: #fff;
            box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
            transform: scale(1.04);
        }
        .reset-btn:active {
            transform: scale(0.98);
        }
        @media (max-width: 600px) {
            .reset-card {
                max-width: 99vw;
                padding: 1.2rem 0.5rem 1.2rem 0.5rem;
            }
        }
    </style>
</head>
<body>
<div class="concert-bg-overlay"></div>
<div class="reset-outer">
    <div class="reset-card">
        <div class="reset-logo">
            <img src="{{ asset('images/eventixlogo.png') }}" alt="EventiX Logo">
        </div>
        <div class="reset-title">Reset Password</div>
        <div class="reset-desc">
            {{ __('Enter your new password below to reset your account password.') }}
        </div>
        <form method="POST" action="{{ route('password.store') }}" class="reset-form">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}">
            <div class="reset-form-group">
                <span class="reset-icon"><i class="bi bi-envelope"></i></span>
                <input id="email" class="reset-input" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" placeholder="Email">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
            <div class="reset-form-group" style="position:relative;">
                <span class="reset-icon"><i class="bi bi-lock"></i></span>
                <input id="password" class="reset-input" type="password" name="password" required autocomplete="new-password" placeholder="Password">
                <span class="reset-icon-eye reset-icon" onclick="toggleResetPassword('password', this)"><i class="bi bi-eye"></i></span>
        </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
            <div class="reset-form-group" style="position:relative;">
                <span class="reset-icon"><i class="bi bi-lock"></i></span>
                <input id="password_confirmation" class="reset-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                <span class="reset-icon-eye reset-icon" onclick="toggleResetPassword('password_confirmation', this)"><i class="bi bi-eye"></i></span>
        </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            <button type="submit" class="reset-btn">
                {{ __('Reset Password') }}
            </button>
        </form>
    </div>
        </div>
<script>
function toggleResetPassword(id, el) {
    const input = document.getElementById(id);
    if (!input) return;
    const isPwd = input.getAttribute('type') === 'password';
    input.setAttribute('type', isPwd ? 'text' : 'password');
    el.innerHTML = isPwd ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
}
</script>
</body>
</html>

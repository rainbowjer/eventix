<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - EventiX</title>
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
        .split-modern-outer {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }
        .split-modern-card {
            display: flex;
            flex-direction: row;
            width: 98vw;
            max-width: 950px;
            min-height: 520px;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(31,38,135,0.13);
            overflow: hidden;
            background: rgba(255,255,255,0.18);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
        }
        .split-modern-left {
            flex: 1;
            background: transparent;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            position: relative;
            padding: 3.2rem 2.5rem 3.2rem 2.8rem;
            color: #fff;
        }
        .split-modern-welcome {
            position: relative;
            z-index: 1;
            max-width: 420px;
        }
        .split-modern-welcome .logo {
            display:flex;justify-content:center;align-items:center;margin-bottom:1.2rem;
        }
        .split-modern-welcome .logo img {
            height:100px;border-radius:12px;box-shadow:0 2px 12px rgba(123,47,242,0.10);background:rgba(255,255,255,0);
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
            max-width: 370px;
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
        .split-modern-input, .split-modern-select {
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
        .split-modern-input:focus, .split-modern-select:focus {
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
        .split-modern-icon-eye {
            left: auto;
            right: 1.1rem;
            cursor: pointer;
            pointer-events: auto;
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
                padding: 0.2rem 0.1rem;
                margin: 0.7rem 0 0.7rem 0;
            }
            .split-modern-left, .split-modern-right {
                padding: 0.7rem 0.1rem;
            }
            .split-modern-welcome h1 {
                font-size: 1.1rem;
            }
            .split-modern-login-card {
                max-width: 99vw;
            }
        }
        .account-type-btn.selected {
            background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%) !important;
            color: #fff !important;
            border: 2.5px solid #ff6a88 !important;
            box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #ff6a8888 !important;
        }
        .account-type-btn:not(.selected):hover, .account-type-btn:not(.selected):focus {
            background: #f3f0ff !important;
            color: #a259f7 !important;
            border: 2.5px solid #a259f7 !important;
            box-shadow: 0 4px 24px 0 #a259f733 !important;
        }
    </style>
</head>
<body>
<div class="concert-bg-overlay"></div>
<div class="split-modern-outer">
    <div class="split-modern-card">
        <div class="split-modern-left">
            <div class="split-modern-welcome">
                <div class="logo">
                    <img src="{{ asset('images/eventixlogo.png') }}" alt="EventiX Logo">
                </div>
                <h1>Join EventiX</h1>
                <p>Create your account to book, manage, and resell event tickets with ease!</p>
            </div>
        </div>
        <div class="split-modern-right">
            <div class="split-modern-login-card">
                <div class="split-modern-login-title">REGISTER</div>
                <form method="POST" action="{{ route('register') }}" autocomplete="off">
                    @csrf
                    <!-- Modern Account Type Button Group -->
                    <div style="margin-bottom:2rem;text-align:center;">
                        <span style="font-size:0.98rem;color:#a259f7;font-weight:600;margin-bottom:0.5rem;letter-spacing:0.5px;display:block;text-align:center;">Choose YourAccount Type</span>
                        <div class="account-type-btn-group" style="display:flex;gap:1.2rem;justify-content:center;flex-wrap:wrap;">
                            <button type="button" class="account-type-btn" data-value="user" tabindex="0" style="flex:1 1 120px;min-width:120px;max-width:160px;display:flex;align-items:center;justify-content:center;padding:0.7rem 1.2rem;border-radius:2rem;background:#fff;border:2.5px solid #a259f7;color:#a259f7;font-weight:700;font-size:1.08rem;box-shadow:0 2px 12px 0 rgba(123,47,242,0.10);transition:box-shadow 0.18s,border 0.18s,background 0.18s,color 0.18s;outline:none;gap:0.6rem;cursor:pointer;">
                                <i class="bi bi-person" style="font-size:1.3rem;"></i> User
                            </button>
                            <button type="button" class="account-type-btn" data-value="organizer" tabindex="0" style="flex:1 1 120px;min-width:120px;max-width:160px;display:flex;align-items:center;justify-content:center;padding:0.7rem 1.2rem;border-radius:2rem;background:#fff;border:2.5px solid #a259f7;color:#a259f7;font-weight:700;font-size:1.08rem;box-shadow:0 2px 12px 0 rgba(123,47,242,0.10);transition:box-shadow 0.18s,border 0.18s,background 0.18s,color 0.18s;outline:none;gap:0.6rem;cursor:pointer;">
                                <i class="bi bi-mic" style="font-size:1.3rem;"></i> Organizer
                            </button>
                        </div>
                        <input type="hidden" name="role" id="role" value="{{ old('role') }}">
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>
                    <div class="split-modern-form-group">
                        <span class="split-modern-icon"><i class="bi bi-person"></i></span>
                        <input id="name" class="split-modern-input" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Full Name">
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>
                    <div class="split-modern-form-group">
                        <span class="split-modern-icon"><i class="bi bi-envelope"></i></span>
                        <input id="email" class="split-modern-input" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="Email">
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                    <div class="split-modern-form-group" style="position:relative;">
                        <span class="split-modern-icon"><i class="bi bi-lock"></i></span>
                        <input id="password" class="split-modern-input" type="password" name="password" required autocomplete="new-password" placeholder="Password">
                        <span class="split-modern-icon-eye split-modern-icon" onclick="toggleRegisterPassword('password', this)"><i class="bi bi-eye"></i></span>
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>
                    <div class="split-modern-form-group" style="position:relative;">
                        <span class="split-modern-icon"><i class="bi bi-lock"></i></span>
                        <input id="password_confirmation" class="split-modern-input" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Confirm Password">
                        <span class="split-modern-icon-eye split-modern-icon" onclick="toggleRegisterPassword('password_confirmation', this)"><i class="bi bi-eye"></i></span>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                    <button type="submit" class="split-modern-login-btn">Register</button>
                </form>
                <div class="split-modern-login-register-row">
                    Already registered? <a href="{{ route('login') }}">Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function toggleRegisterPassword(id, el) {
    const input = document.getElementById(id);
    if (!input) return;
    const isPwd = input.getAttribute('type') === 'password';
    input.setAttribute('type', isPwd ? 'text' : 'password');
    el.innerHTML = isPwd ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
}
// Button group selection logic
const btns = document.querySelectorAll('.account-type-btn');
const roleInputBtn = document.getElementById('role');
let selectedBtn = roleInputBtn ? roleInputBtn.value : '';
function updateBtnSelection(val) {
    btns.forEach(btn => {
        if(btn.getAttribute('data-value') === val) {
            btn.classList.add('selected');
        } else {
            btn.classList.remove('selected');
        }
    });
}
btns.forEach(btn => {
    btn.addEventListener('click', function() {
        const val = this.getAttribute('data-value');
        if(roleInputBtn) roleInputBtn.value = val;
        updateBtnSelection(val);
    });
    btn.addEventListener('keydown', function(e) {
        if(e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
        }
    });
});
if(selectedBtn) updateBtnSelection(selectedBtn);
</script>
</body>
</html>

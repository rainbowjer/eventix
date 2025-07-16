<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - EventiX</title>
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
        .verify-outer {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: transparent;
        }
        .verify-card {
            width: 98vw;
            max-width: 420px;
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
        .verify-logo {
            margin-bottom: 1.2rem;
        }
        .verify-logo img {
            height: 80px;
            border-radius: 12px;
            box-shadow: 0 2px 12px rgba(123,47,242,0.10);
            background: rgba(255,255,255,0);
        }
        .verify-title {
            font-family: 'Montserrat', Arial, sans-serif;
            font-size: 1.5rem;
            font-weight: 700;
            color: rgb(115, 18, 227);
            margin-bottom: 0.7rem;
            text-align: center;
            letter-spacing: 1px;
        }
        .verify-desc {
            font-size: 1.05rem;
            color: #2d1764;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .verify-success {
            font-size: 1.01rem;
            color: #1db954;
            margin-bottom: 1.1rem;
            text-align: center;
            font-weight: 600;
        }
        .verify-actions {
            width: 100%;
            display: flex;
            gap: 1.2rem;
            justify-content: center;
            margin-top: 1.2rem;
            flex-wrap: wrap;
        }
        .verify-btn, .verify-logout-btn {
            background: linear-gradient(90deg, #a259f7 0%, #ff6a88 100%);
            color: #fff;
            border: none;
            border-radius: 1.5rem;
            font-weight: 700;
            font-size: 1.08rem;
            padding: 0.9rem 1.5rem;
            box-shadow: 0 4px 24px 0 #a259f755, 0 1.5px 0 #ff6a8888;
            letter-spacing: 1px;
            transition: background 0.2s, color 0.2s, box-shadow 0.2s, transform 0.15s;
            cursor: pointer;
        }
        .verify-btn:hover, .verify-btn:focus, .verify-logout-btn:hover, .verify-logout-btn:focus {
            background: linear-gradient(90deg, #ff6a88 0%, #a259f7 100%);
            color: #fff;
            box-shadow: 0 6px 32px 0 #a259f799, 0 2px 0 #ff6a8888;
            transform: scale(1.04);
        }
        .verify-btn:active, .verify-logout-btn:active {
            transform: scale(0.98);
        }
        .verify-logout-btn {
            background: #fff;
            color: #a259f7;
            border: 2px solid #a259f7;
            box-shadow: 0 2px 12px 0 rgba(123,47,242,0.10);
        }
        .verify-logout-btn:hover, .verify-logout-btn:focus {
            background: #f3f0ff;
            color: #a259f7;
            border: 2px solid #ff6a88;
        }
        body {
            background: linear-gradient(120deg, #d68de1 0%, #883aff 100%) !important;
        }
        .auth-card {
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
        .auth-logo {
            height: 60px;
            margin-bottom: 1.2rem;
            max-width: 80vw;
            width: auto;
        }
        .auth-card h1, .auth-card h2 {
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
        @media (max-width: 991.98px) {
            .auth-card {
                max-width: 95vw;
                padding: 1.5rem 0.7rem 1rem 0.7rem;
                border-radius: 20px;
            }
            .auth-logo {
                height: 48px !important;
            }
            .auth-card h1, .auth-card h2 {
                font-size: 1.08rem !important;
            }
            .form-control, .btn-gradient {
                font-size: 0.98rem !important;
                padding: 0.7rem 1rem !important;
            }
        }
        @media (max-width: 600px) {
            .auth-card {
                max-width: 99vw;
                padding: 1.1rem 0.3rem 0.7rem 0.3rem !important;
                margin: 2vh auto;
                border-radius: 16px;
            }
            .auth-logo {
                height: 38px !important;
            }
            .auth-card h1, .auth-card h2 {
                font-size: 0.98rem !important;
            }
            .form-control, .btn-gradient {
                font-size: 0.93rem !important;
                padding: 0.6rem 0.7rem !important;
            }
        }
    </style>
</head>
<body>
<div class="concert-bg-overlay"></div>
<div class="verify-outer">
    <div class="verify-card">
        <div class="verify-logo">
            <img src="{{ asset('images/eventixlogo.png') }}" alt="EventiX Logo">
        </div>
        <div class="verify-title">Verify Your Email</div>
        <div class="verify-desc">
            {{ __("Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.") }}
    </div>
    @if (session('status') == 'verification-link-sent')
            <div class="verify-success">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif
        <div class="verify-actions">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
                <button type="submit" class="verify-btn">
                    {{ __('Resend Verification Email') }}
                </button>
        </form>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
                <button type="submit" class="verify-logout-btn">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
    </div>
</div>
</body>
</html>

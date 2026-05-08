<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>403 — Access Denied | Apo Life</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet"/>
  @vite(['resources/css/app.css'])
  <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
  <style>
    body {
      background: linear-gradient(135deg, #1a1a2e 0%, #16213e 40%, #0f3460 70%, #533483 100%);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'DM Sans', sans-serif;
      color: #f0f0f5;
      padding: 2rem;
    }
    .mesh {
      position: fixed; inset: 0;
      background:
        radial-gradient(ellipse 70% 50% at 80% 20%, rgba(192,57,43,0.18) 0%, transparent 60%),
        radial-gradient(ellipse 50% 70% at 20% 80%, rgba(83,52,131,0.2) 0%, transparent 60%);
      pointer-events: none;
    }
    .error-code {
      font-family: 'Playfair Display', serif;
      font-size: clamp(7rem, 20vw, 12rem);
      font-weight: 800;
      line-height: 1;
      background: linear-gradient(135deg, #E74C3C, #922B21);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.05em;
    }
    @keyframes shake {
      0%,100% { transform: translateX(0); }
      20%,60% { transform: translateX(-6px); }
      40%,80% { transform: translateX(6px); }
    }
  </style>
</head>
<body>
  <div class="mesh"></div>
  <div style="position:relative; z-index:1; text-align:center; max-width:520px;">

    {{-- Lock icon --}}
    <div style="display:inline-flex; align-items:center; justify-content:center; width:88px; height:88px; border-radius:50%; background:rgba(192,57,43,0.15); border:2px solid rgba(192,57,43,0.3); margin-bottom:1.25rem; animation: shake 0.6s 1s ease both;">
      <svg width="40" height="40" fill="none" stroke="#E74C3C" stroke-width="1.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
      </svg>
    </div>

    <div class="error-code">403</div>

    <h1 style="font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; color:#fff; margin:0.5rem 0 0.85rem; letter-spacing:-0.01em;">
      Access Denied
    </h1>

    <p style="color:rgba(160,160,184,0.85); font-size:0.9rem; line-height:1.75; margin-bottom:2rem; max-width:380px; margin-left:auto; margin-right:auto;">
      You don't have permission to access this page.
      If you believe this is a mistake, please contact the administrator.
    </p>

    <div style="background:rgba(192,57,43,0.08); border:1px solid rgba(192,57,43,0.2); border-radius:12px; padding:1rem; margin-bottom:2rem; font-size:0.82rem; color:rgba(240,160,160,0.85);">
      <strong>Why am I seeing this?</strong><br>
      This area is restricted to authorized roles only. Make sure you're logged in with the correct account.
    </div>

    <div style="display:flex; gap:0.85rem; justify-content:center; flex-wrap:wrap;">
      <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
        style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.4rem; border-radius:9999px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:#f0f0f5; text-decoration:none; font-size:0.875rem; font-weight:600; transition:all 0.2s;"
        onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Go Back
      </a>
      @auth
        @php
          $role = auth()->user()->role->value;
          $dashRoute = match($role) {
            'admin' => 'admin.dashboard', 'staff' => 'staff.dashboard',
            'donor' => 'donor.dashboard', 'hospital' => 'hospital.dashboard',
            default => 'home',
          };
        @endphp
        <a href="{{ route($dashRoute) }}"
          style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.4rem; border-radius:9999px; background:linear-gradient(135deg,#C0392B,#e94560); color:#fff; text-decoration:none; font-size:0.875rem; font-weight:600; box-shadow:0 4px 15px rgba(192,57,43,0.4); transition:all 0.2s;"
          onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
          My Dashboard
        </a>
      @else
        <a href="{{ route('login') }}"
          style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.4rem; border-radius:9999px; background:linear-gradient(135deg,#C0392B,#e94560); color:#fff; text-decoration:none; font-size:0.875rem; font-weight:600; box-shadow:0 4px 15px rgba(192,57,43,0.4); transition:all 0.2s;"
          onmouseover="this.style.transform='translateY(-2px)'" onmouseout="this.style.transform='translateY(0)'">
          Sign In
        </a>
      @endauth
    </div>

    <p style="margin-top:2.5rem; font-size:0.75rem; color:rgba(160,160,184,0.5);">
      Apo Life — info@bloodbankniga.ph
    </p>

  </div>
</body>
</html>
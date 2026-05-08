<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>404 — Page Not Found | Apo Life</title>
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
        radial-gradient(ellipse 70% 50% at 20% 20%, rgba(233,69,96,0.15) 0%, transparent 60%),
        radial-gradient(ellipse 50% 70% at 80% 80%, rgba(83,52,131,0.2) 0%, transparent 60%);
      pointer-events: none;
    }
    .error-code {
      font-family: 'Playfair Display', serif;
      font-size: clamp(7rem, 20vw, 12rem);
      font-weight: 800;
      line-height: 1;
      background: linear-gradient(135deg, #e94560, #533483);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      letter-spacing: -0.05em;
      margin-bottom: 0;
    }
    @keyframes float {
      0%,100% { transform: translateY(0); }
      50%      { transform: translateY(-12px); }
    }
  </style>
</head>
<body>
  <div class="mesh"></div>
  <div style="position:relative; z-index:1; text-align:center; max-width:520px;">

    {{-- Animated blood drop --}}
    <div style="animation: float 4s ease-in-out infinite; margin-bottom:1rem;">
      <svg viewBox="0 0 100 120" width="80" height="96" style="filter:drop-shadow(0 0 20px rgba(233,69,96,0.5));">
        <defs>
          <linearGradient id="dg" x1="0" y1="0" x2="0" y2="1">
            <stop offset="0%" stop-color="#e94560"/>
            <stop offset="100%" stop-color="#922B21"/>
          </linearGradient>
        </defs>
        <path d="M50 5 C50 5, 85 50, 85 72 C85 91, 69 105, 50 105 C31 105, 15 91, 15 72 C15 50, 50 5, 50 5Z" fill="url(#dg)"/>
        <path d="M35 40 C35 40, 45 52, 43 62" stroke="rgba(255,255,255,0.35)" stroke-width="4" stroke-linecap="round" fill="none"/>
        <path d="M44 76 h12 M50 70 v12" stroke="rgba(255,255,255,0.85)" stroke-width="3" stroke-linecap="round"/>
      </svg>
    </div>

    <div class="error-code">404</div>

    <h1 style="font-family:'Playfair Display',serif; font-size:1.6rem; font-weight:700; color:#fff; margin:0.5rem 0 0.85rem; letter-spacing:-0.01em;">
      Page Not Found
    </h1>

    <p style="color:rgba(160,160,184,0.85); font-size:0.9rem; line-height:1.75; margin-bottom:2rem; max-width:380px; margin-left:auto; margin-right:auto;">
      The page you're looking for doesn't exist or has been moved.
      Let's get you back on track.
    </p>

    <div style="display:flex; gap:0.85rem; justify-content:center; flex-wrap:wrap;">
      <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}"
        style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.4rem; border-radius:9999px; background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.15); color:#f0f0f5; text-decoration:none; font-size:0.875rem; font-weight:600; transition:all 0.2s;"
        onmouseover="this.style.background='rgba(255,255,255,0.15)'" onmouseout="this.style.background='rgba(255,255,255,0.08)'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Go Back
      </a>
      <a href="{{ route('home') }}"
        style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.7rem 1.4rem; border-radius:9999px; background:linear-gradient(135deg,#C0392B,#e94560); color:#fff; text-decoration:none; font-size:0.875rem; font-weight:600; box-shadow:0 4px 15px rgba(192,57,43,0.4); transition:all 0.2s;"
        onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 6px 20px rgba(192,57,43,0.5)'"
        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 4px 15px rgba(192,57,43,0.4)'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Home
      </a>
    </div>

    <p style="margin-top:2.5rem; font-size:0.75rem; color:rgba(160,160,184,0.5);">
      Apo Life — Bajada, Davao City
    </p>

  </div>
</body>
</html>
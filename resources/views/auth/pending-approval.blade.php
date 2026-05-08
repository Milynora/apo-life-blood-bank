<x-guest-layout title="Registration Submitted">
  <div style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:6rem 1.5rem 3rem;">
    <div style="max-width:520px; width:100%; text-align:center;" data-aos="fade-up">

      {{-- Animated check circle --}}
      <div style="margin-bottom:2rem; position:relative; display:inline-block;">
        <svg viewBox="0 0 120 120" width="120" height="120">
          <defs>
            <linearGradient id="checkGrad" x1="0" y1="0" x2="1" y2="1">
              <stop offset="0%" stop-color="#27AE60"/>
              <stop offset="100%" stop-color="#2ECC71"/>
            </linearGradient>
          </defs>
          <circle cx="60" cy="60" r="54" fill="rgba(39,174,96,0.12)" stroke="rgba(39,174,96,0.3)" stroke-width="1.5"/>
          <circle cx="60" cy="60" r="44" fill="url(#checkGrad)" style="animation: scaleIn 0.6s cubic-bezier(0.4,0,0.2,1) both;"/>
          <path d="M36 60 L52 76 L84 44" stroke="white" stroke-width="5" stroke-linecap="round" stroke-linejoin="round" fill="none"
            style="stroke-dasharray:70; stroke-dashoffset:70; animation: drawCheck 0.5s 0.4s ease forwards;"/>
        </svg>
      </div>

      <div class="glass" style="padding:2.5rem; border-radius:28px; text-align:left;">
        <div style="text-align:center; margin-bottom:1.75rem;">
          <h1 style="font-family:var(--font-display); font-size:1.8rem; font-weight:800; color:#fff; margin-bottom:0.75rem;">
            Registration Submitted!
          </h1>
          <p style="color:var(--text-muted); font-size:0.925rem; line-height:1.7;">
            Thank you for registering with <strong style="color:rgba(240,240,245,0.9);">Apo Life</strong>.
            Your account is currently under review by our administrators.
          </p>
        </div>

        {{-- What happens next --}}
        <div style="background:rgba(255,255,255,0.04); border:1px solid rgba(255,255,255,0.08); border-radius:16px; padding:1.25rem; margin-bottom:1.5rem;">
          <div style="font-size:0.78rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-muted); margin-bottom:1rem;">What happens next?</div>
          <div style="display:flex; flex-direction:column; gap:0.85rem;">
            @foreach([
              ['Your registration details will be reviewed by our admin team.'],
              ['You will receive an email notification once your account is approved.'],
              ['Approval typically takes 1–2 business days.'],
              ['Once approved, you can log in and access your dashboard.'],
            ] as [$idx => $step])
              <div style="display:flex; gap:0.85rem; align-items:flex-start;">
                <div style="width:22px; height:22px; background:rgba(233,69,96,0.2); border:1px solid rgba(233,69,96,0.35); border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:0.68rem; font-weight:700; color:#e94560; font-family:var(--font-mono);">{{ $idx + 1 }}</div>
                <span style="font-size:0.85rem; color:rgba(240,240,245,0.75); line-height:1.6;">{{ $step }}</span>
              </div>
            @endforeach
          </div>
        </div>

        {{-- Contact note --}}
        <div style="display:flex; gap:0.75rem; align-items:flex-start; padding:1rem; background:rgba(41,128,185,0.08); border:1px solid rgba(41,128,185,0.2); border-radius:12px; margin-bottom:1.75rem;">
          <svg width="18" height="18" fill="none" stroke="#3498DB" stroke-width="1.8" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
            <path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span style="font-size:0.82rem; color:rgba(160,200,230,0.9); line-height:1.6;">
            For inquiries, contact us at <strong>info@bloodbankniga.ph</strong> or call <strong>+63 (82) 123-4567</strong>.
          </span>
        </div>

        <div style="display:flex; flex-direction:column; gap:0.75rem;">
          <a href="{{ route('home') }}" class="btn btn-primary" style="justify-content:center;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Back to Home
          </a>
          <a href="{{ route('login') }}" class="btn btn-glass" style="justify-content:center;">
            Already approved? Sign In
          </a>
        </div>
      </div>

    </div>
  </div>

  <style>
    @keyframes scaleIn {
      from { transform: scale(0); opacity: 0; }
      to   { transform: scale(1); opacity: 1; }
    }
    @keyframes drawCheck {
      to { stroke-dashoffset: 0; }
    }
  </style>
</x-guest-layout>
<x-guest-layout title="Forgot Password">
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:6rem 1.5rem 3rem;">
  <div style="max-width:440px; width:100%;">

    <div style="text-align:center; margin-bottom:2rem;">
      <a href="{{ route('home') }}">
      </a>
      <h1 style="font-family:var(--font-display); font-size:1.7rem; font-weight:800; color:#fff; margin-bottom:0.5rem;">
        Forgot Password?
      </h1>
      <p style="color:var(--text-muted); font-size:0.875rem; line-height:1.6; max-width:340px; margin:0 auto;">
        Enter your email and we'll send you a reset link.
      </p>
    </div>

    <div class="glass" style="padding:2.25rem; border-radius:24px;">

      @if(session('status'))
        <div style="display:flex; gap:0.75rem; align-items:flex-start; padding:1rem; background:rgba(39,174,96,0.1); border:1px solid rgba(39,174,96,0.3); border-radius:12px; margin-bottom:1.5rem;">
          <svg width="18" height="18" fill="none" stroke="#27AE60" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;">
            <path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          <span style="font-size:0.875rem; color:#a0e0b0;">{{ session('status') }}</span>
        </div>
      @endif

      <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div class="form-group">
          <label class="form-label form-label-dark" for="email">Email Address</label>
          <div style="position:relative;">
            <div style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); color:rgba(255,255,255,0.3); pointer-events:none;">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            </div>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
              class="form-input form-input-glass" style="padding-left:2.75rem;" placeholder="your@email.com"/>
          </div>
          @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.85rem;">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
          Send Reset Link
        </button>
      </form>

      <div style="text-align:center; margin-top:1.25rem;">
        <a href="{{ route('login') }}" style="font-size:0.82rem; color:var(--text-muted); text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; transition:color 0.2s;"
           onmouseover="this.style.color='#e94560'" onmouseout="this.style.color='var(--text-muted)'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
          Back to Sign In
        </a>
      </div>
    </div>

  </div>
</div>
</x-guest-layout>
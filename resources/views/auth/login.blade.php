<x-guest-layout title="Sign In">
  <div style="min-height:100vh; display:flex; align-items:center; padding-top:40px;">
    <div class="container" style="display:grid; grid-template-columns:1.2fr 1fr; gap:4rem; align-items:center; padding:0rem 1.5rem;">

      {{-- Left: Info panel --}}
      <div>
        <div class="hero-badge" style="margin-bottom:1.75rem;">
          Apo Life
        </div>

        <h1 style="font-family:var(--font-display); font-size:clamp(2rem,5vw,3.2rem); font-weight:800; color:#fff; line-height:1.1; letter-spacing:-0.02em; margin-bottom:1.25rem;">
          Welcome<br>Back, <span style="background:linear-gradient(135deg,#e94560,#ff8fa3); -webkit-background-clip:text; -webkit-text-fill-color:transparent; background-clip:text;">Apo Lifer's.</span>
        </h1>

        <p style="color:var(--text-muted); font-size:0.975rem; line-height:1.75; max-width:420px; margin-bottom:2.5rem;">
          Your generosity has already made a difference. Sign in to manage your donations, check your appointment status, and see the lives you've helped save.
        </p>

      </div>

      {{-- Right: Login form --}}
        <div class="glass" style="padding:2.5rem; border-radius:28px;">

          {{-- Logo --}}
          <div style="text-align:center; margin-bottom:2rem;">
            <h2 style="font-family:var(--font-display); font-size:1.35rem; font-weight:700; color:#fff;">Sign In to Your Account</h2>
            <p style="font-size:0.82rem; color:var(--text-muted); margin-top:0.3rem;">Enter your credentials below</p>
          </div>

          {{-- Session status (e.g. after registration) --}}
          @if(session('status'))
            <div class="alert alert-info" style="margin-bottom:1.25rem; background:rgba(41,128,185,0.15); border-color:rgba(41,128,185,0.4); color:#a0c8e8;">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              {{ session('status') }}
            </div>
          @endif

          <form method="POST" action="{{ route('login') }}">
            @csrf

            {{-- Email --}}
            <div class="form-group">
              <label class="form-label form-label-dark" for="email">Email Address</label>
              <div style="position:relative;">
                <div style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); color:rgba(255,255,255,0.35); pointer-events:none;">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                  class="form-input form-input-glass"
                  style="padding-left:2.75rem;"
                  placeholder="your@email.com"/>
              </div>
              @error('email')
                <div class="form-error">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ $message }}
                </div>
              @enderror
            </div>

            {{-- Password --}}
            <div class="form-group" x-data="{ show: false }">
              <label class="form-label form-label-dark" for="password">Password</label>
              <div style="position:relative;">
                <div style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); color:rgba(255,255,255,0.35); pointer-events:none;">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                  class="form-input form-input-glass"
                  style="padding-left:2.75rem; padding-right:3rem;"
                  placeholder="••••••••"/>
                <button type="button" @click="show = !show"
                  style="position:absolute; right:0.9rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.4); transition:color 0.2s; padding:0;">
                  <svg x-show="!show" width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                  <svg x-show="show"  width="16" height="16" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                </button>
              </div>
              @error('password')
                <div class="form-error">
                  <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  {{ $message }}
                </div>
              @enderror
            </div>

            {{-- Remember me + Forgot --}}
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:1.5rem;">
              <label class="toggle-wrap">
                <input type="checkbox" name="remember" class="toggle-input" id="remember"/>
                <span class="toggle-track"><span class="toggle-thumb"></span></span>
                <span class="toggle-label" style="color:rgba(240,240,245,0.7); font-size:0.82rem;">Remember me</span>
              </label>
              @if(Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="font-size:0.82rem; color:#e94560; text-decoration:none; font-weight:600;" onmouseover="this.style.textDecoration='underline'" onmouseout="this.style.textDecoration='none'">
                  Forgot password?
                </a>
              @endif
            </div>

            {{-- Submit --}}
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.85rem;">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
              Sign In
            </button>
          </form>

          {{-- Divider --}}
          <div style="display:flex; align-items:center; gap:1rem; margin:1.5rem 0;">
            <div style="flex:1; height:1px; background:rgba(255,255,255,0.1);"></div>
            <span style="font-size:0.78rem; color:var(--text-muted);">Don't have an account?</span>
            <div style="flex:1; height:1px; background:rgba(255,255,255,0.1);"></div>
          </div>

          {{-- Register links --}}
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem;">
            <a href="{{ route('register') }}?role=donor"
              style="display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.7rem; border-radius:12px; border:1px solid rgba(255,255,255,0.15); color:rgba(240,240,245,0.8); text-decoration:none; font-size:0.83rem; font-weight:600; transition:all 0.2s; background:rgba(255,255,255,0.04);"
              onmouseover="this.style.background='rgba(233,69,96,0.12)'; this.style.borderColor='rgba(233,69,96,0.35)'; this.style.color='#e94560'"
              onmouseout="this.style.background='rgba(255,255,255,0.04)'; this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='rgba(240,240,245,0.8)'">
              <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M12 12a5 5 0 100-10 5 5 0 000 10z" />
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M4 20a8 8 0 0116 0" />
  </svg>
              Register as Donor
            </a>
            <a href="{{ route('register') }}?role=hospital"
              style="display:flex; align-items:center; justify-content:center; gap:0.5rem; padding:0.7rem; border-radius:12px; border:1px solid rgba(255,255,255,0.15); color:rgba(240,240,245,0.8); text-decoration:none; font-size:0.83rem; font-weight:600; transition:all 0.2s; background:rgba(255,255,255,0.04);"
              onmouseover="this.style.background='rgba(83,52,131,0.2)'; this.style.borderColor='rgba(83,52,131,0.45)'; this.style.color='#9B59B6'"
              onmouseout="this.style.background='rgba(255,255,255,0.04)'; this.style.borderColor='rgba(255,255,255,0.15)'; this.style.color='rgba(240,240,245,0.8)'">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
              Register as Hospital
            </a>
          </div>

        </div>
      </div>

    </div>
  </div>

<style>
input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear,
input[type="password"]::-webkit-credentials-auto-fill-button,
input[type="password"]::-webkit-textfield-decoration-container { display: none !important; }
</style>

</x-guest-layout>

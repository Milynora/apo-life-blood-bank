<x-guest-layout title="Reset Password">
<div style="min-height:100vh; display:flex; align-items:center; justify-content:center; padding:6rem 1.5rem 3rem;">
  <div style="max-width:440px; width:100%;" data-aos="fade-up">

    <div style="text-align:center; margin-bottom:2rem;">
      <a href="{{ route('home') }}">
        <img src="{{ asset('images/logo.jpg') }}" alt="Logo" style="height:48px; margin-bottom:1rem;"/>
      </a>
      <h1 style="font-family:var(--font-display); font-size:1.7rem; font-weight:800; color:#fff; margin-bottom:0.5rem;">
        Set New Password
      </h1>
      <p style="color:var(--text-muted); font-size:0.875rem;">
        Choose a strong password for your account.
      </p>
    </div>

    <div class="glass" style="padding:2.25rem; border-radius:24px;" x-data="{ showA: false, showB: false }">
      <form method="POST" action="{{ route('password.store') }}">
        @csrf
        <input type="hidden" name="token" value="{{ $request->route('token') }}"/>

        <div class="form-group">
          <label class="form-label form-label-dark" for="email">Email Address</label>
          <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus
            class="form-input form-input-glass" placeholder="your@email.com"/>
          @error('email')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group" >
          <label class="form-label form-label-dark" for="password">New Password</label>
          <div style="position:relative;">
            <input id="password" :type="showA ? 'text' : 'password'" name="password" required autocomplete="new-password"
              class="form-input form-input-glass" style="padding-right:3rem;" placeholder="Min. 8 characters"/>
            <button type="button" @click="showA=!showA"
              style="position:absolute; right:0.9rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.35); padding:0;">
              <svg x-show="!showA" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <svg x-show="showA"  width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
            </button>
          </div>
          @error('password')<div class="form-error">{{ $message }}</div>@enderror
        </div>

        <div class="form-group">
          <label class="form-label form-label-dark" for="password_confirmation">Confirm Password</label>
          <div style="position:relative;">
            <input id="password_confirmation" :type="showB ? 'text' : 'password'" name="password_confirmation" required
              class="form-input form-input-glass" style="padding-right:3rem;" placeholder="Repeat new password"/>
            <button type="button" @click="showB=!showB"
              style="position:absolute; right:0.9rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:rgba(255,255,255,0.35); padding:0;">
              <svg x-show="!showB" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
              <svg x-show="showB"  width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
            </button>
          </div>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.85rem;">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
          Reset Password
        </button>
      </form>
    </div>

  </div>
</div>
</x-guest-layout>
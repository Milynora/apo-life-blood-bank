<x-app-layout title="Add Staff">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('admin.users.index') }}">Users</a>
        <span class="breadcrumb-sep">›</span>
        <span>Add Staff</span>
      </div>
      <h1 class="page-title">Add Staff Member</h1>
      <p class="page-subtitle">Staff accounts are auto-approved and can record donations and screenings.</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="max-width:700px;">
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Staff Account Details</h3>
      </div>
      <div class="dash-card-body">

        <form method="POST" action="{{ route('admin.users.store-staff') }}">
          @csrf

          {{-- Account credentials --}}
          <div style="margin-bottom:1.5rem;">
            <div style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#bbb; margin-bottom:1rem; padding-bottom:0.5rem; border-bottom:1px solid var(--border-light);">
              Login Credentials
            </div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="name">Full Name</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required
                  class="form-input form-input-light" placeholder="Ana Santos"/>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label" for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required
                  class="form-input form-input-light" placeholder="staff@apolife.ph"/>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group" style="margin-bottom:0;" x-data="{ show: false }">
                <label class="form-label" for="password">Password</label>
                <div style="position:relative;">
                  <input id="password" :type="show ? 'text' : 'password'" name="password" required
                    class="form-input form-input-light" style="padding-right:3rem;" placeholder="Min. 8 characters"/>
                  <button type="button" @click="show=!show"
                    style="position:absolute; right:0.9rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#aaa; padding:0;">
                    <svg x-show="!show" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show"  width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                  </button>
                </div>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div class="form-group" style="margin-bottom:0;" x-data="{ show: false }">
                <label class="form-label" for="password_confirmation">Confirm Password</label>
                <div style="position:relative;">
                  <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                    class="form-input form-input-light" style="padding-right:3rem;" placeholder="Repeat password"/>
                  <button type="button" @click="show=!show"
                    style="position:absolute; right:0.9rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#aaa; padding:0;">
                    <svg x-show="!show" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    <svg x-show="show"  width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
                  </button>
                </div>
              </div>
            </div>
          </div>

          {{-- Info note --}}
          <div class="alert alert-info" style="margin-bottom:1.5rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:0.82rem;">Share the credentials securely with the staff member.</span>
          </div>

          {{-- Actions --}}
          <div style="display:flex; gap:0.85rem; justify-content:flex-end;">
            <a href="{{ route('admin.users.index') }}"
              style="padding:0.7rem 1.4rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:inline-flex; align-items:center; transition:all 0.2s;">
              Cancel
            </a>
            <button type="submit" class="btn btn-dash-primary">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
              Create Staff Account
            </button>
          </div>
        </form>

      </div>
    </div>
  </div>

  <style>
    @media (max-width: 640px) {
      div[style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr"]     { grid-template-columns: 1fr !important; }
    }
  </style>

  <style>
  input[type="password"]::-ms-reveal,
  input[type="password"]::-webkit-credentials-auto-fill-button { display: none !important; }
</style>

</x-app-layout>
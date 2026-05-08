<x-app-layout title="Add Hospital">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('admin.users.index') }}">Users</a>
        <span class="breadcrumb-sep">›</span>
        <span>Add Hospital</span>
      </div>
      <h1 class="page-title">Add Hospital Partner</h1>
      <p class="page-subtitle">Encode hospital information. A system account will be created automatically.</p>
    </div>
    <a href="{{ route('admin.users.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <form method="POST" action="{{ route('admin.users.store-hospital') }}">
    @csrf

    <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:1.5rem; align-items:start; margin-bottom:1.25rem;">

      {{-- LEFT: Hospital Information --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Hospital Information</h3>
        </div>
        <div class="dash-card-body">

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Hospital Name <span style="color:#E74C3C;">*</span></label>
              <input type="text" name="hospital_name" value="{{ old('hospital_name') }}" required
                class="form-input form-input-light" placeholder="e.g. Davao Medical Center"
                id="hospital_name_input"
                oninput="document.getElementById('name_preview').textContent = this.value || 'Filled from hospital name'"/>
              @error('hospital_name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Email Address <span style="color:#E74C3C;">*</span></label>
              <input type="email" name="email" value="{{ old('email') }}" required
                class="form-input form-input-light" placeholder="hospital@email.com"
                id="email_input"
                oninput="document.getElementById('email_preview').textContent = this.value || 'Filled from email field'"/>
              @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">License Number <span style="color:#E74C3C;">*</span></label>
              <input type="text" name="license_number" value="{{ old('license_number') }}" required
                class="form-input form-input-light" placeholder="e.g. DOH-2024-00123"/>
              @error('license_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                class="form-input form-input-light" placeholder="09XXXXXXXXX or 082XXXXXXX"
                maxlength="11"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"/>
              @error('contact_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>

          </div>

          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Complete Address</label>
            <input type="text" name="address" value="{{ old('address') }}"
              class="form-input form-input-light"
              placeholder="Street, Barangay, City/Municipality"/>
            @error('address')<div class="form-error">{{ $message }}</div>@enderror
          </div>

        </div>
      </div>

      {{-- RIGHT: System Account --}}
      <div class="dash-card" style="align-self:start;">
        <div class="dash-card-header">
          <h3 class="dash-card-title">System Account</h3>
          <span style="font-size:0.72rem; color:#27AE60; font-weight:600; background:rgba(39,174,96,0.1); border:1px solid rgba(39,174,96,0.25); border-radius:6px; padding:0.2rem 0.65rem;">
            Auto-generated
          </span>
        </div>
        <div class="dash-card-body">

          <div class="form-group" style="margin-bottom:1rem;">
            <label class="form-label">Username / Email</label>
            <div style="font-size:0.875rem; padding:0.7rem 0.9rem; background:#f8f8f8; border:1.5px solid var(--border-light); border-radius:10px; min-height:42px; display:flex; align-items:center; gap:0.5rem;">
              <svg width="14" height="14" fill="none" stroke="#aaa" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
              <span id="email_preview" style="color:{{ old('email') ? '#1a1a2e' : '#aaa' }}; font-style:{{ old('email') ? 'normal' : 'italic' }}; font-size:0.85rem; word-break:break-all;">
                {{ old('email') ?: 'Filled from email field' }}
              </span>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:1rem;">
            <label class="form-label">Default Password</label>
            <div style="font-size:0.875rem; color:#1a1a2e; padding:0.7rem 0.9rem; background:#f8f8f8; border:1.5px solid var(--border-light); border-radius:10px; min-height:42px; display:flex; align-items:center; gap:0.5rem; font-family:var(--font-mono); font-weight:600; letter-spacing:0.1em;">
              <svg width="14" height="14" fill="none" stroke="#aaa" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0;"><path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              hospital1234
            </div>
          </div>

          <div style="background:rgba(41,128,185,0.06); border:1px solid rgba(41,128,185,0.2); border-radius:10px; padding:0.85rem 1rem; font-size:0.8rem; color:#1a3a5a; line-height:1.6; display:flex; gap:0.65rem; align-items:flex-start;">
            <svg width="15" height="15" fill="none" stroke="#2980B9" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <div>
              The hospital can <strong>change their password</strong> upon login. Please inform the hospital of their credentials.
            </div>
          </div>

        </div>
      </div>

    </div>

    <div style="display:flex; justify-content:flex-end; gap:0.85rem;">
      <a href="{{ route('admin.users.index') }}"
        style="padding:0.7rem 1.4rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:inline-flex; align-items:center; transition:all 0.2s;">
        Cancel
      </a>
      <button type="submit" class="btn btn-dash-primary" style="border-radius:10px; padding:0.7rem 1.75rem;">
        <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        Add Hospital Partner
      </button>
    </div>

  </form>

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1.4fr 1fr"] { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 768px) {
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
<x-app-layout title="Register Donor">

@php
  $isStaff     = auth()->user()->isStaff();
  $routePrefix = $isStaff ? 'staff' : 'admin';
  $dashRoute   = $isStaff ? route('staff.dashboard') : route('admin.dashboard');
@endphp

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ $dashRoute }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route($routePrefix . '.donors.index') }}">Donors & Hospitals</a>
        <span class="breadcrumb-sep">›</span>
        <span>Register Donor</span>
      </div>
      <h1 class="page-title">Register New Donor</h1>
      <p class="page-subtitle">Encode donor information. A system account will be created automatically.</p>
    </div>
    <a href="{{ route($routePrefix . '.donors.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <form method="POST" action="{{ route($routePrefix . '.donors.store') }}">
    @csrf

    <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:1.5rem; align-items:start;">

      {{-- LEFT: Personal Information --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Personal Information</h3>
        </div>
        <div class="dash-card-body">

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Full Name <span style="color:#E74C3C;">*</span></label>
              <input type="text" name="name" id="name_input" value="{{ old('name') }}" required
                class="form-input form-input-light" placeholder="Juan Dela Cruz"/>
              @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Email Address <span style="color:#E74C3C;">*</span></label>
              <input type="email" name="email" id="email_input" value="{{ old('email') }}" required
                class="form-input form-input-light" placeholder="juan@example.com"/>
              @error('email')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Contact Number</label>
              <input type="text" name="contact_number" value="{{ old('contact_number') }}"
                class="form-input form-input-light" placeholder="09XXXXXXXXX"
                maxlength="11"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"/>
              @error('contact_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Gender <span style="color:#E74C3C;">*</span></label>
              <select name="gender" required class="form-input form-input-light">
                <option value="">— Select —</option>
                <option value="male"   {{ old('gender') === 'male'   ? 'selected' : '' }}>Male</option>
                <option value="female" {{ old('gender') === 'female' ? 'selected' : '' }}>Female</option>
                <option value="other"  {{ old('gender') === 'other'  ? 'selected' : '' }}>Other</option>
              </select>
              @error('gender')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Date of Birth <span style="color:#E74C3C;">*</span></label>
              <input type="text" name="date_of_birth"
                value="{{ old('date_of_birth') }}"
                class="form-input form-input-light dob-picker"
                placeholder="MM/DD/YYYY" autocomplete="off" readonly/>
              <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Must be 18–65 years old</div>
              @error('date_of_birth')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">
                Blood Type
                <span style="font-size:0.68rem; font-weight:400; color:#aaa; margin-left:4px;">(optional)</span>
              </label>
              <select name="blood_type_id" class="form-input form-input-light">
                <option value="">Unknown / Not sure yet</option>
                @foreach($bloodTypes as $bt)
                  <option value="{{ $bt->blood_type_id }}"
                    {{ old('blood_type_id') == $bt->blood_type_id ? 'selected' : '' }}>
                    {{ $bt->type_name }}
                  </option>
                @endforeach
              </select>
              <div style="font-size:0.7rem; color:#bbb; margin-top:0.25rem;">Confirmed after first donation.</div>
              @error('blood_type_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

          </div>

          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Complete Address</label>
            <input type="text" name="address" value="{{ old('address') }}"
              class="form-input form-input-light"
              placeholder="House/Unit No., Street, Barangay, City/Municipality"/>
            @error('address')<div class="form-error">{{ $message }}</div>@enderror
          </div>

        </div>
      </div>

      {{-- RIGHT: System Account + Buttons stacked --}}
      <div style="display:flex; flex-direction:column; gap:1.5rem;">

        <div class="dash-card">
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
                donor1234
              </div>
            </div>

            <div style="background:rgba(41,128,185,0.06); border:1px solid rgba(41,128,185,0.2); border-radius:10px; padding:0.85rem 1rem; font-size:0.8rem; color:#1a3a5a; line-height:1.6; display:flex; gap:0.65rem; align-items:flex-start;">
              <svg width="15" height="15" fill="none" stroke="#2980B9" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              <div>
                The donor can <strong>change their password</strong> upon login. Please inform the donor of their credentials.
              </div>
            </div>

          </div>
        </div>

        <br>
        
        {{-- Actions --}}
        <div style="display:flex; gap:0.85rem; justify-content:flex-end;">
          <a href="{{ route($routePrefix . '.donors.index') }}"
            style="padding:0.7rem 1.4rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:inline-flex; align-items:center; transition:all 0.2s;">
            Cancel
          </a>
          <button type="submit" class="btn btn-dash-primary" style="border-radius:10px; padding:0.7rem 1.75rem;">
            <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
            Register Donor
          </button>
        </div>

      </div>

    </div>

  </form>

  @push('scripts')
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const dobEl = document.querySelector('.dob-picker');
      if (dobEl && typeof flatpickr !== 'undefined') {
        flatpickr(dobEl, {
          dateFormat: 'm/d/Y',
          minDate: new Date(new Date().getFullYear() - 65, new Date().getMonth(), new Date().getDate()),
          maxDate: new Date(new Date().getFullYear() - 18, new Date().getMonth(), new Date().getDate()),
          allowInput: true,
          disableMobile: true,
          onOpen: function(selectedDates, dateStr, instance) {
            if (!dateStr) {
              const d = new Date();
              d.setFullYear(d.getFullYear() - 42);
              instance.jumpToDate(d);
            }
          }
        });
      }

      const emailInput   = document.getElementById('email_input');
      const emailPreview = document.getElementById('email_preview');
      if (emailInput && emailPreview) {
        emailInput.addEventListener('input', () => {
          const val = emailInput.value.trim();
          emailPreview.textContent     = val || 'Filled from email field';
          emailPreview.style.color     = val ? '#1a1a2e' : '#aaa';
          emailPreview.style.fontStyle = val ? 'normal' : 'italic';
        });
      }
    });
  </script>
  @endpush

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1.4fr 1fr"] { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 768px) {
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
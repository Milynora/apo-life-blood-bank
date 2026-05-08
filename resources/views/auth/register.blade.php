<x-guest-layout title="Create Account">
<div style="min-height:100vh; padding-top:40px; padding-bottom:3rem;">
  <div class="container" style="max-width:860px;">

    {{-- Header --}}
    <div style="text-align:center; margin-bottom:1.75rem;">
      <a href="{{ route('home') }}" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none; margin-bottom:1rem;">
      </a>
      <h1 style="font-family:var(--font-display); font-size:1.8rem; font-weight:800; color:#fff; letter-spacing:-0.02em; margin-bottom:0.4rem;">
        Join Apo Life
      </h1>
      <p style="color:var(--text-muted); font-size:0.88rem;">
  Already have an account?
  <a href="{{ route('login') }}"
     style="color:#e94560; font-weight:600; text-decoration:none;"
     onmouseover="this.style.textDecoration='underline'"
     onmouseout="this.style.textDecoration='none'">
     Sign in here
  </a>
</p>
    </div>

    <div class="glass" style="padding:2rem 2.25rem; border-radius:24px;"
      x-data="registerForm()">

      {{-- Role tabs --}}
      <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1.75rem;">
        <button type="button" @click="role='donor'"
          :class="role==='donor' ? 'role-tab-active-red' : 'role-tab-inactive'"
          class="role-tab">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M12 12a5 5 0 100-10 5 5 0 000 10z" />
    <path stroke-linecap="round" stroke-linejoin="round"
      d="M4 20a8 8 0 0116 0" />
  </svg>
          Register as Donor
        </button>
        <button type="button" @click="role='hospital'"
          :class="role==='hospital' ? 'role-tab-active-purple' : 'role-tab-inactive'"
          class="role-tab">
          <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          Register as Hospital
        </button>
      </div>

      <form method="POST" action="{{ route('register') }}">
        @csrf
        <input type="hidden" name="role" :value="role"/>

        {{-- ── ACCOUNT INFO ─────────────────────────────────── --}}
        <div class="reg-section-title">Account Information</div>
        <div class="reg-grid-3" style="margin-bottom:1.25rem;">

          <div class="form-group" style="margin-bottom:0;">
            <label class="reg-label" for="email">Email Address</label>
            <input id="email" type="email" name="email" value="{{ old('email') }}" required
              class="reg-input" placeholder="juan@email.com"/>
            @error('email')<div class="reg-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group" style="margin-bottom:0;">
            <label class="reg-label" for="password">Password</label>
            <div class="reg-pass-wrap" x-data="{ show: false }">
              <input id="password" :type="show ? 'text' : 'password'" name="password" required
                class="reg-input" placeholder="Min. 8 characters"/>
              <button type="button" @click="show = !show" class="reg-eye-btn" tabindex="-1">
                <svg x-show="!show" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg x-show="show"  width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
              </button>
            </div>
            @error('password')<div class="reg-error">{{ $message }}</div>@enderror
          </div>

          <div class="form-group" style="margin-bottom:0;">
            <label class="reg-label" for="password_confirmation">Confirm Password</label>
            <div class="reg-pass-wrap" x-data="{ show: false }">
              <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required
                class="reg-input" placeholder="Repeat password"/>
              <button type="button" @click="show = !show" class="reg-eye-btn" tabindex="-1">
                <svg x-show="!show" width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                <svg x-show="show"  width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/></svg>
              </button>
            </div>
          </div>

        </div>

        {{-- ── DONOR FIELDS ──────────────────────────────────── --}}
<div x-show="role === 'donor'" x-cloak>
  <div class="reg-section-title">Personal Information</div>
  <div class="reg-grid-2" style="margin-bottom:1.25rem;">

    <div class="form-group" style="margin-bottom:0;">
      <label class="reg-label" for="name">Full Name</label>
      <input id="name" type="text" name="name" value="{{ old('name') }}"
        class="reg-input" placeholder="e.g. Juan dela Cruz"
        :disabled="role !== 'donor'"
        :required="role === 'donor'"/>
      @error('name')<div class="reg-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group" style="margin-bottom:0;">
      <label class="reg-label" for="gender">Gender</label>
      <select id="gender" name="gender" class="reg-input reg-select"
        :disabled="role !== 'donor'"
        :required="role === 'donor'">
        <option value="">Select Gender</option>
        <option value="male"   {{ old('gender')==='male'   ? 'selected' : '' }}>Male</option>
        <option value="female" {{ old('gender')==='female' ? 'selected' : '' }}>Female</option>
        <option value="other"  {{ old('gender')==='other'  ? 'selected' : '' }}>Other</option>
      </select>
      @error('gender')<div class="reg-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group" style="margin-bottom:0;">
  <label class="reg-label" for="date_of_birth">Date of Birth
    <span style="font-size:0.68rem; font-weight:400; color:rgba(160,160,190,0.5); margin-left:4px;">(18–65 years old)</span>
  </label>

  <input id="date_of_birth" type="text" name="date_of_birth"
    value="{{ old('date_of_birth') }}"
    class="reg-input dob-picker" placeholder="MM/DD/YYYY"
    :disabled="role !== 'donor'"
    :required="role === 'donor'"/>

  @error('date_of_birth')
    <div class="reg-error">{{ $message }}</div>
  @enderror
</div>

    <div class="form-group" style="margin-bottom:0;">
      <label class="reg-label">Contact Number</label>
      <input type="text" name="donor_contact_number"
        value="{{ old('donor_contact_number') }}"
        class="reg-input" placeholder="e.g. 09XXXXXXXXX"
        maxlength="11" inputmode="numeric"
        :disabled="role !== 'donor'"
        :required="role === 'donor'"
        @input="$el.value = $el.value.replace(/[^0-9]/g,'').slice(0,11)"/>
      @error('donor_contact_number')<div class="reg-error">{{ $message }}</div>@enderror
    </div>

    {{-- Blood type — nullable, Unknown as default --}}
    <div class="form-group" style="margin-bottom:0;">
      <label class="reg-label" for="blood_type_id">
        Blood Type
        <span style="font-size:0.68rem; font-weight:400; color:rgba(160,160,190,0.5); margin-left:4px;">(optional)</span>
      </label>
      <select id="blood_type_id" name="blood_type_id" class="reg-input reg-select"
        :disabled="role !== 'donor'">
        <option value="">Unknown / Not sure yet</option>
        @foreach(\App\Models\BloodType::orderBy('type_name')->get() as $bt)
          <option value="{{ $bt->blood_type_id }}"
            {{ old('blood_type_id') == $bt->blood_type_id ? 'selected' : '' }}>
            {{ $bt->type_name }}
          </option>
        @endforeach
      </select>
      @error('blood_type_id')<div class="reg-error">{{ $message }}</div>@enderror
    </div>

    <div class="form-group" style="margin-bottom:0;">
      <label class="reg-label" for="d_address">Complete Address</label>
      <input id="d_address" type="text" name="d_address" value="{{ old('d_address') }}"
        class="reg-input" placeholder="e.g. 123 Rizal St., Brgy. Obrero, Davao City"
        :disabled="role !== 'donor'"
        :required="role === 'donor'"/>
      @error('d_address')<div class="reg-error">{{ $message }}</div>@enderror
    </div>

  </div>
</div>

        {{-- ── HOSPITAL FIELDS ─────────────────────────────── --}}
        <div x-show="role === 'hospital'" x-cloak>
          <div class="reg-section-title">Hospital Information</div>
          <div class="reg-grid-2" style="margin-bottom:1rem;">
            <div class="form-group" style="margin-bottom:0;">
              <label class="reg-label" for="hospital_name">Hospital Name</label>
              <input id="hospital_name" type="text" name="hospital_name" value="{{ old('hospital_name') }}"
                class="reg-input" placeholder="e.g. Davao Medical Center"
                :disabled="role !== 'hospital'"
                :required="role === 'hospital'"/>
              @error('hospital_name')<div class="reg-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="reg-label" for="license_number">DOH License Number</label>
              <input id="license_number" type="text" name="license_number" value="{{ old('license_number') }}"
                class="reg-input" placeholder="DOH License to Operate (LTO) number"
                :disabled="role !== 'hospital'"
                :required="role === 'hospital'"/>
              @error('license_number')<div class="reg-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="reg-label">Contact Number</label>
              <div style="display:flex; align-items:stretch;">
                <input type="text" name="hospital_contact_number" value="{{ old('hospital_contact_number') }}"
                  class="reg-input"
                  placeholder="e.g. 09XXXXXXXXX or 082XXXXXXX" maxlength="11" inputmode="numeric"
                  @input="$el.value = $el.value.replace(/[^0-9]/g,'').slice(0,11)"
                  :disabled="role !== 'hospital'"
:required="role === 'hospital'"/>
              </div>
              @error('hospital_contact_number')<div class="reg-error">{{ $message }}</div>@enderror
            </div>
            <div class="form-group" style="margin-bottom:0;">
              <label class="reg-label" for="h_address">Complete Address</label>
              <input id="h_address" type="text" name="h_address" value="{{ old('h_address') }}"
                class="reg-input" placeholder="e.g. Davao Medical Center, J.P. Laurel Ave., Davao City"
                :disabled="role !== 'hospital'"
:required="role === 'hospital'"/>
              @error('h_address')<div class="reg-error">{{ $message }}</div>@enderror
            </div>
          </div>
          <div style="display:flex; align-items:flex-start; gap:0.65rem; padding:0.85rem 1rem; background:rgba(41,128,185,0.1); border:1px solid rgba(41,128,185,0.3); border-radius:10px; margin-bottom:1.25rem;">
            <svg width="16" height="16" fill="none" stroke="#3498DB" stroke-width="2" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <span style="font-size:0.8rem; color:rgba(160,200,230,0.95); line-height:1.5;">Hospital accounts require admin approval. You will be notified by email once approved.</span>
          </div>
        </div>

        {{-- Terms --}}
        <div style="display:flex; justify-content:center; margin-bottom:1.25rem;">
          <label style="display:flex; align-items:flex-start; gap:0.75rem; cursor:pointer; max-width:560px;">
            <input type="checkbox" name="terms" required
              style="margin-top:2px; width:15px; height:15px; accent-color:#e94560; flex-shrink:0; cursor:pointer;"/>
            <span style="font-size:0.82rem; color:rgba(200,200,220,0.85); line-height:1.6; text-align:center;">
              I agree to the
              <a href="#" style="color:#e94560; text-decoration:none; font-weight:600;">Terms of Service</a>
              and
              <a href="#" style="color:#e94560; text-decoration:none; font-weight:600;">Privacy Policy</a>
              of Apo Life.
            </span>
          </label>
        </div>

        <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:0.875rem; font-size:0.95rem;">
          <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
          Create My Account
        </button>

      </form>
    </div>
  </div>
</div>

<style>
  [x-cloak] { display:none !important; }
/* ── Register form styles ──────────────────────────────── */
.role-tab {
  display: flex; align-items: center; justify-content: center; gap: 0.65rem;
  padding: 0.9rem 1rem; border-radius: 14px; border: 2px solid;
  cursor: pointer; font-family: var(--font-body); font-size: 0.9rem;
  font-weight: 600; transition: all 0.25s; width: 100%;
}
.role-tab-active-red {
  background: linear-gradient(135deg, rgba(233,69,96,0.2), rgba(192,57,43,0.12));
  border-color: rgba(233,69,96,0.55) !important;
  color: #ff8fa3 !important;
  box-shadow: 0 0 0 3px rgba(233,69,96,0.12);
}
.role-tab-active-purple {
  background: linear-gradient(135deg, rgba(83,52,131,0.25), rgba(155,89,182,0.12));
  border-color: rgba(155,89,182,0.55) !important;
  color: #c084fc !important;
  box-shadow: 0 0 0 3px rgba(83,52,131,0.12);
}
.role-tab-inactive {
  background: rgba(255,255,255,0.04);
  border-color: rgba(255,255,255,0.12) !important;
  color: rgba(160,160,184,0.7) !important;
}
.role-tab-inactive:hover {
  background: rgba(255,255,255,0.08);
  border-color: rgba(255,255,255,0.22) !important;
  color: rgba(240,240,245,0.85) !important;
}

.reg-section-title {
  font-size: 0.68rem; font-weight: 700; text-transform: uppercase;
  letter-spacing: 0.12em; color: rgba(255,255,255,0.3);
  margin-bottom: 0.85rem;
  display: flex; align-items: center; gap: 0.75rem;
}
.reg-section-title::before,
.reg-section-title::after {
  content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.07);
}

.reg-grid-2 {
  display: grid; grid-template-columns: 1fr 1fr; gap: 0.85rem;
}
.reg-grid-3 {
  display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.85rem;
}

.reg-label {
  display: block; font-size: 0.78rem; font-weight: 600;
  color: rgba(200,200,220,0.75); margin-bottom: 0.35rem;
  text-transform: uppercase; letter-spacing: 0.04em;
}

.reg-input {
  width: 100%; padding: 0.7rem 0.9rem;
  background: rgba(15,15,30,0.55);
  border: 1.5px solid rgba(255,255,255,0.14);
  border-radius: 10px; color: #f0f0f5;
  font-family: var(--font-body); font-size: 0.875rem;
  transition: border-color 0.2s, box-shadow 0.2s;
  outline: none; appearance: none;
  -webkit-appearance: none;
}
.reg-input:focus {
  border-color: rgba(233,69,96,0.65);
  box-shadow: 0 0 0 3px rgba(233,69,96,0.12);
  background: rgba(15,15,30,0.75);
}
.reg-input::placeholder { color: rgba(160,160,190,0.45); }
.reg-input:disabled, .reg-input[readonly] {
  opacity: 0.55; cursor: not-allowed;
  background: rgba(255,255,255,0.03);
}

/* Select arrow */
.reg-select {
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='rgba(160,160,200,0.6)' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 0.9rem center;
  padding-right: 2.5rem;
  cursor: pointer;
}
.reg-select option {
  background: #1a1a2e;
  color: #f0f0f5;
}

.reg-pass-wrap { position: relative; }
.reg-pass-wrap .reg-input { padding-right: 2.8rem; width: 100%; }
.reg-eye-btn {
  position: absolute; right: 0.8rem; top: 50%;
  transform: translateY(-50%);
  background: none; border: none; cursor: pointer;
  color: rgba(160,160,200,0.5); padding: 0; display: flex;
  transition: color 0.2s;
}
.reg-eye-btn:hover { color: rgba(200,200,220,0.85); }

.reg-prefix {
  display: flex; align-items: center; justify-content: center;
  padding: 0 0.85rem;
  background: rgba(255,255,255,0.07);
  border: 1.5px solid rgba(255,255,255,0.14);
  border-right: none;
  border-radius: 10px 0 0 10px;
  color: rgba(200,200,220,0.8);
  font-size: 0.85rem; font-weight: 700;
  white-space: nowrap; flex-shrink: 0;
  font-family: var(--font-mono);
}

.reg-input-suffix {
  border-radius: 0 10px 10px 0 !important;
  border-left: none !important;
}
.reg-input-suffix:focus {
  border-left: none !important;
}

.reg-hint {
  font-size: 0.7rem; color: rgba(160,160,190,0.45);
  margin-top: 0.25rem;
}

.reg-error {
  font-size: 0.75rem; color: #ff6b6b;
  margin-top: 0.3rem;
  display: flex; align-items: center; gap: 0.3rem;
}

@media (max-width: 640px) {
  .reg-grid-3 { grid-template-columns: 1fr 1fr !important; }
  .reg-grid-2 { grid-template-columns: 1fr !important; }
}

input[type="password"]::-ms-reveal,
input[type="password"]::-ms-clear,
input[type="password"]::-webkit-credentials-auto-fill-button,
input[type="password"]::-webkit-textfield-decoration-container { display: none !important; }

</style>

@push('scripts')
<script>
function registerForm() {
  return {
    role: '{{ old('role', request('role', 'donor')) }}'
  }
}

document.addEventListener('DOMContentLoaded', () => {
  const dobEl = document.querySelector('.dob-picker');

  if (dobEl && typeof flatpickr !== 'undefined') {
    flatpickr(dobEl, {
      dateFormat: 'm/d/Y',

      // 18–65 rule (more accurate than fp_incr with 365 days)
      minDate: new Date(new Date().getFullYear() - 65, new Date().getMonth(), new Date().getDate()),
      maxDate: new Date(new Date().getFullYear() - 18, new Date().getMonth(), new Date().getDate()),

      allowInput: true,
      disableMobile: true,

      onOpen: function(selectedDates, dateStr, instance) {
        if (!dateStr) {
          const now = new Date();

          // midpoint of 18–65 range
          const midAge = 42;

          const d = new Date();
          d.setFullYear(now.getFullYear() - midAge);

          instance.jumpToDate(d);
        }
      }
    });
  }
});
</script>
@endpush

</x-guest-layout>
<x-app-layout title="Hospital Profile">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('hospital.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>Profile</span>
      </div>
      <h1 class="page-title">Hospital Profile</h1>
      <p class="page-subtitle">Keep your hospital information accurate and up to date.</p>
    </div>
  </div>

  <div x-data="{ tab: 'hospital' }">

    {{-- Profile header --}}
<div class="dash-card" style="margin-bottom:1.5rem; position:relative; overflow:hidden;">

  {{-- Diagonal stripes --}}
  <div style="position:absolute; top:-80px; left:-80px; width:350px; height:350px; background:rgba(83,52,131,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
  <div style="position:absolute; top:-50px; left:-50px; width:250px; height:250px; background:rgba(83,52,131,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
  <div style="position:absolute; bottom:-80px; left:200px; width:300px; height:300px; background:rgba(142,68,173,0.04); transform:rotate(45deg); border-radius:45px; pointer-events:none;"></div>
  <div style="position:absolute; top:-60px; right:-60px; width:350px; height:350px; background:rgba(83,52,131,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
  <div style="position:absolute; top:-40px; right:-40px; width:250px; height:250px; background:rgba(142,68,173,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
  <div style="position:absolute; bottom:-60px; right:200px; width:280px; height:280px; background:rgba(83,52,131,0.04); transform:rotate(45deg); border-radius:40px; pointer-events:none;"></div>
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) rotate(45deg); width:200px; height:200px; background:rgba(142,68,173,0.03); border-radius:30px; pointer-events:none;"></div>

  <div style="padding:1.75rem 2rem; display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
  <div style="width:72px; height:72px; border-radius:16px; background:linear-gradient(135deg,#533483,#9B59B6); display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 6px 20px rgba(83,52,131,0.3);">
    <svg width="32" height="32" fill="none" stroke="#fff" stroke-width="1.5" viewBox="0 0 24 24">
      <path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
    </svg>
  </div>
  {{-- Name + meta --}}
      <div style="flex:1; min-width:0;">
        <h2 style="font-family:var(--font-display); font-size:1.5rem; font-weight:700; color:#1a1a2e; margin-bottom:0.3rem;">
          {{ $hospital->hospital_name }}
        </h2>
        <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap; margin-bottom:0.35rem;">
          <span style="font-size:0.82rem; color:#888;">{{ $hospital->user->email }}</span>
        </div>
        <div style="display:flex; align-items:center; gap:1rem; flex-wrap:wrap;">
          <span style="font-size:0.78rem; color:#aaa;">
            <strong style="color:#555;">License:</strong> {{ $hospital->license_number }}
          </span>
        </div>
      </div>
  <div style="text-align:right;">
    <div style="font-size:0.72rem; color:#bbb; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.3rem;">Registered</div>
    <div style="font-size:0.9rem; font-weight:600; color:#1a1a2e;">{{ auth()->user()->created_at->format('M d, Y') }}</div>
  </div>
</div>

</div>

    {{-- Tab nav --}}
    <div class="tab-nav" style="margin-bottom:1.5rem;">
      <button class="tab-btn" :class="tab === 'hospital' ? 'active' : ''" @click="tab = 'hospital'">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        Hospital Information
      </button>
      <button class="tab-btn" :class="tab === 'account' ? 'active' : ''" @click="tab = 'account'">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Account Settings
      </button>
    </div>

    {{-- ── Hospital Information ──────────────────────────── --}}
    <div x-show="tab === 'hospital'" x-data="{ editing: false }">
      <form method="POST" action="{{ route('hospital.profile.update') }}">
        @csrf @method('PATCH')

        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Hospital Details</h3>
            <div style="display:flex; gap:0.65rem;">
              <button type="button" x-show="!editing" @click="editing = true"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
                onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
                onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button type="button" x-show="editing" @click="editing = false"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#888; font-size:0.8rem; font-weight:600; cursor:pointer;">
                Cancel
              </button>
            </div>
          </div>

          <div class="dash-card-body">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">

              {{-- Hospital Name --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Hospital Name <span style="color:#E74C3C;">*</span></label>
                <template x-if="!editing">
                  <div class="profile-value">{{ old('hospital_name', $hospital->hospital_name) }}</div>
                </template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="hospital_name"
                      value="{{ old('hospital_name', $hospital->hospital_name) }}" required
                      class="form-input form-input-light"/>
                    @error('hospital_name')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

              {{-- License Number --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">License Number <span style="color:#E74C3C;">*</span></label>
                <template x-if="!editing">
                  <div class="profile-value">{{ old('license_number', $hospital->license_number) }}</div>
                </template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="license_number"
                      value="{{ old('license_number', $hospital->license_number) }}" required
                      class="form-input form-input-light"/>
                    @error('license_number')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

              {{-- Contact Number --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Contact Number</label>
                <template x-if="!editing">
                  <div class="profile-value">{{ old('contact_number', $hospital->contact_number) ?: '—' }}</div>
                </template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="contact_number"
                value="{{ old('contact_number', $hospital->contact_number) }}"
                class="form-input form-input-light" placeholder="09XXXXXXXXX or 082XXXXXXX"
                maxlength="11"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"/>
              @error('contact_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>
                </template>
              </div>

              {{-- Address --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Complete Address</label>
                <template x-if="!editing">
                  <div class="profile-value">{{ old('address', $hospital->address) ?: '—' }}</div>
                </template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="address"
                      value="{{ old('address', $hospital->address) }}"
                      class="form-input form-input-light"
                      placeholder="Full hospital address"/>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

            </div>

            {{-- Save button — no divider, right aligned --}}
            <div x-show="editing" x-cloak style="margin-top:1rem; text-align:right;">
  <button type="submit" class="btn btn-dash-primary">
    <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
    Save Changes
  </button>
</div>

          </div>
        </div>

      </form>
    </div>

    {{-- ── Account Settings ────────────────────────────────── --}}
    <div x-show="tab === 'account'">

      {{-- Email --}}
      <div class="dash-card" style="margin-bottom:1.25rem;" x-data="{ editing: false }">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Email Address</h3>
          <div style="display:flex; gap:0.65rem;">
            <button type="button" x-show="!editing" @click="editing = true"
              style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
              onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
              onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit
            </button>
            <button type="button" x-show="editing" @click="editing = false"
              style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#888; font-size:0.8rem; font-weight:600; cursor:pointer;">
              Cancel
            </button>
          </div>
        </div>
        <div class="dash-card-body">
          <template x-if="!editing">
            <div>
              <label class="form-label">Email</label>
              <div class="profile-value">{{ auth()->user()->email }}</div>
            </div>
          </template>
          <template x-if="editing">
            <form method="POST" action="{{ route('hospital.profile.email') }}">
              @csrf @method('PATCH')
              <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Email</label>
                <input type="email" name="email"
                  value="{{ old('email', auth()->user()->email) }}"
                  class="form-input form-input-light"/>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
              </div>
              <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn btn-dash-primary">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                  Update Email
                </button>
              </div>
            </form>
          </template>
        </div>
      </div>

      {{-- Password --}}
      <div class="dash-card" x-data="{ editing: false }">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Change Password</h3>
          <div style="display:flex; gap:0.65rem;">
            <button type="button" x-show="!editing" @click="editing = true"
              style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
              onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
              onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit
            </button>
            <button type="button" x-show="editing" @click="editing = false"
              style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#888; font-size:0.8rem; font-weight:600; cursor:pointer;">
              Cancel
            </button>
          </div>
        </div>
        <div class="dash-card-body">
          <template x-if="!editing">
            <div style="font-size:0.875rem; color:#aaa; display:flex; align-items:center; gap:0.5rem;">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
              Password is set. Click Edit to change it.
            </div>
          </template>
          <template x-if="editing">
            <form method="POST" action="{{ route('hospital.profile.password') }}"
              x-data="{ show1:false, show2:false, show3:false }">
              @csrf @method('PATCH')
              <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Current Password</label>
                  <div style="position:relative;">
                    <input :type="show1 ? 'text' : 'password'" name="current_password"
                      class="form-input form-input-light" style="padding-right:3rem;" placeholder="Current password"/>
                    <button type="button" @click="show1=!show1"
                      style="position:absolute; right:0.85rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#aaa; padding:0; display:flex;">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path x-show="!show1" stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        <path x-show="show1" stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                      </svg>
                    </button>
                  </div>
                  @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">New Password</label>
                  <div style="position:relative;">
                    <input :type="show2 ? 'text' : 'password'" name="password"
                      class="form-input form-input-light" style="padding-right:3rem;" placeholder="Min. 8 characters"/>
                    <button type="button" @click="show2=!show2"
                      style="position:absolute; right:0.85rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#aaa; padding:0; display:flex;">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path x-show="!show2" stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        <path x-show="show2" stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                      </svg>
                    </button>
                  </div>
                  @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Confirm Password</label>
                  <div style="position:relative;">
                    <input :type="show3 ? 'text' : 'password'" name="password_confirmation"
                      class="form-input form-input-light" style="padding-right:3rem;" placeholder="Repeat new password"/>
                    <button type="button" @click="show3=!show3"
                      style="position:absolute; right:0.85rem; top:50%; transform:translateY(-50%); background:none; border:none; cursor:pointer; color:#aaa; padding:0; display:flex;">
                      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path x-show="!show3" stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        <path x-show="show3" stroke-linecap="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                      </svg>
                    </button>
                  </div>
                </div>

              </div>
              <div style="display:flex; justify-content:flex-end;">
                <button type="submit" class="btn btn-dash-primary">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                  Update Password
                </button>
              </div>
            </form>
          </template>
        </div>
      </div>

    </div>{{-- end account tab --}}

  </div>{{-- end x-data tabs --}}

  <style>
    .profile-value {
      font-size: 0.875rem;
      color: #1a1a2e;
      padding: 0.7rem 0.9rem;
      background: #f8f8f8;
      border: 1.5px solid var(--border-light);
      border-radius: 10px;
      min-height: 42px;
      display: flex;
      align-items: center;
    }
    @media (max-width: 768px) {
      div[style*="grid-template-columns:1fr 1fr"]     { grid-template-columns: 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr 1fr !important; }
    }
  </style>

</x-app-layout>
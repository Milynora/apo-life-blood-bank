<x-app-layout title="My Profile">

@php
  $donor        = auth()->user()->donor;
  $hasDonations = $donor->donations()->exists();
@endphp

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('donor.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>My Profile</span>
      </div>
      <h1 class="page-title">My Profile</h1>
      <p class="page-subtitle">Keep your information up to date for accurate records.</p>
    </div>
  </div>

  <div x-data="{ tab: 'personal' }">

    {{-- ── Profile header with avatar upload ─────────────── --}}
<div class="dash-card" style="margin-bottom:1.5rem; position:relative; overflow:hidden;">

  {{-- Diagonal stripes --}}
  <div style="position:absolute; top:-80px; left:-80px; width:350px; height:350px; background:rgba(192,57,43,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
  <div style="position:absolute; top:-50px; left:-50px; width:250px; height:250px; background:rgba(192,57,43,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
  <div style="position:absolute; bottom:-80px; left:200px; width:300px; height:300px; background:rgba(233,69,96,0.04); transform:rotate(45deg); border-radius:45px; pointer-events:none;"></div>
  <div style="position:absolute; top:-60px; right:-60px; width:350px; height:350px; background:rgba(192,57,43,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
  <div style="position:absolute; top:-40px; right:-40px; width:250px; height:250px; background:rgba(233,69,96,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
  <div style="position:absolute; bottom:-60px; right:200px; width:280px; height:280px; background:rgba(192,57,43,0.04); transform:rotate(45deg); border-radius:40px; pointer-events:none;"></div>
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) rotate(45deg); width:200px; height:200px; background:rgba(233,69,96,0.03); border-radius:30px; pointer-events:none;"></div>

  <div style="padding:1.75rem 2rem; display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">
       
        {{-- Avatar upload --}}
        <form method="POST" action="{{ route('donor.profile.avatar') }}"
          enctype="multipart/form-data" id="avatarForm">
          @csrf
          <div style="position:relative; flex-shrink:0;" x-data="avatarPreview()">

            {{-- Avatar circle --}}
            <div style="width:72px; height:72px; border-radius:50%; overflow:hidden; background:linear-gradient(135deg,var(--primary),#e94560); display:flex; align-items:center; justify-content:center; font-family:var(--font-display); font-size:1.5rem; font-weight:800; color:#fff; box-shadow:0 6px 20px rgba(192,57,43,0.3); border:3px solid #fff; outline:2px solid rgba(192,57,43,0.15); cursor:pointer;"
              @click="$refs.avatarInput.click()">
              <template x-if="preview">
                <img :src="preview" style="width:100%; height:100%; object-fit:cover;"/>
              </template>
              <template x-if="!preview">
                @if($donor->avatar)
                  <img src="{{ asset($donor->avatar) }}" style="width:100%; height:100%; object-fit:cover;"/>
                @else
                  <span>{{ strtoupper(substr($donor->name, 0, 1)) }}</span>
                @endif
              </template>
            </div>

            {{-- Camera overlay --}}
            <div style="position:absolute; bottom:0; right:0; width:24px; height:24px; border-radius:50%; background:var(--primary); border:2px solid #fff; display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 6px rgba(192,57,43,0.4);"
              @click="$refs.avatarInput.click()">
              <svg width="11" height="11" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>

            {{-- Remove photo button --}}
@if($donor->avatar)
  <form method="POST" action="{{ route('donor.profile.avatar.remove') }}" style="position:absolute; top:-6px; left:-6px;">
    @csrf @method('DELETE')
    <button type="submit"
      title="Remove photo"
      style="width:24px; height:24px; border-radius:50%; background:#fff; border:1.5px solid rgba(192,57,43,0.4); display:flex; align-items:center; justify-content:center; cursor:pointer; box-shadow:0 2px 6px rgba(0,0,0,0.15); padding:0;"
      onmouseover="this.style.background='rgba(192,57,43,0.1)'"
      onmouseout="this.style.background='#fff'">
      <svg width="11" height="11" fill="none" stroke="#E74C3C" stroke-width="2.5" viewBox="0 0 24 24">
        <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </form>
@endif

            {{-- Hidden file input --}}
            <input type="file" name="avatar" accept="image/jpg,image/jpeg,image/png,image/webp"
              x-ref="avatarInput" style="display:none;"
              @change="onFileChange($event)"/>

            {{-- Auto-submit button (hidden, shown when file selected) --}}
            <button type="submit" x-ref="submitBtn" style="display:none;"></button>
          </div>
        </form>

        <div style="flex:1;">
          <h2 style="font-family:var(--font-display); font-size:1.5rem; font-weight:700; color:#1a1a2e; margin-bottom:0.3rem;">
            {{ $donor->name }}
          </h2>
          <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">
            <span style="font-size:0.82rem; color:#888;">{{ auth()->user()->email }}</span>
          </div>
          <div style="font-size:0.72rem; color:#888; margin-top:0.4rem;">
  Click your photo to change it
  @if($donor->avatar)
    · <span style="color:#E74C3C;">Click × to remove</span>
  @endif
</div>
        </div>

        <div style="text-align:right;">
          <div style="font-size:0.72rem; color:#888; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.3rem;">Member since</div>
          <div style="font-size:0.9rem; font-weight:600; color:#1a1a2e;">{{ auth()->user()->created_at->format('M Y') }}</div>
        </div>
      </div>
    </div>

    {{-- Tab navigation --}}
    <div class="tab-nav" style="margin-bottom:1.5rem;">
      <button class="tab-btn" :class="tab === 'personal' ? 'active' : ''" @click="tab = 'personal'">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Personal Information
      </button>
      <button class="tab-btn" :class="tab === 'account' ? 'active' : ''" @click="tab = 'account'">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Account Settings
      </button>
    </div>

    {{-- ── Personal Information ───────────────────────────── --}}
    <div x-show="tab === 'personal'" x-data="{ editing: false }">
      <form method="POST" action="{{ route('donor.profile.update') }}">
        @csrf @method('PATCH')

        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Personal Information</h3>
            {{-- Edit / Cancel toggle --}}
            <div style="display:flex; gap:0.65rem;">
              <button type="button"
                x-show="!editing"
                @click="editing = true"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
                onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
                onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button type="button"
                x-show="editing"
                @click="editing = false"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#888; font-size:0.8rem; font-weight:600; cursor:pointer;">
                Cancel
              </button>
            </div>
          </div>

          <div class="dash-card-body">

            {{-- Row 1 --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">

              {{-- Full Name --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Full Name</label>
                <template x-if="!editing">
                  <div class="profile-value">{{ old('name', $donor->user->name) }}</div>
                </template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="name"
                      value="{{ old('name', $donor->user->name) }}" required
                      class="form-input form-input-light"/>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

              {{-- Contact --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Contact Number</label>
                <template x-if="!editing">
                  <div class="profile-value">{{ old('contact_number', $donor->contact_number) ?: '—' }}</div>
                </template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="contact_number"
                value="{{ old('contact_number', $donor->contact_number) }}"
                class="form-input form-input-light" placeholder="09XXXXXXXXX"
                maxlength="11"
                oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"/>
              @error('contact_number')<div class="form-error">{{ $message }}</div>@enderror
            </div>
                </template>
              </div>

              {{-- Gender --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Gender</label>
                <template x-if="!editing">
                  <div class="profile-value">{{ ucfirst(old('gender', $donor->gender)) }}</div>
                </template>
                <template x-if="editing">
                  <div>
                    <select name="gender" required class="form-input form-input-light">
                      <option value="male"   {{ old('gender', $donor->gender) === 'male'   ? 'selected' : '' }}>Male</option>
                      <option value="female" {{ old('gender', $donor->gender) === 'female' ? 'selected' : '' }}>Female</option>
                      <option value="other"  {{ old('gender', $donor->gender) === 'other'  ? 'selected' : '' }}>Other</option>
                    </select>
                    @error('gender')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

            </div>

            {{-- Row 2 --}}
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem;">

              {{-- Date of Birth --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Date of Birth</label>
                <template x-if="!editing">
                  <div class="profile-value">{{ $donor->date_of_birth->format('F d, Y') }}</div>
                </template>
                <template x-if="editing">
                  <div>
  <input type="text" name="date_of_birth"
    value="{{ old('date_of_birth', $donor->date_of_birth->format('m/d/Y')) }}"
    class="form-input form-input-light flatpickr-dob"
    placeholder="MM/DD/YYYY" readonly
    x-init="$nextTick(() => initDobPicker())"/>
  @error('date_of_birth')<div class="form-error">{{ $message }}</div>@enderror
</div>
                </template>
              </div>

              {{-- Address --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Address</label>
                <template x-if="!editing">
                  <div class="profile-value">{{ old('address', $donor->address) ?: '—' }}</div>
                </template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="address"
                      value="{{ old('address', $donor->address) }}"
                      class="form-input form-input-light" placeholder="Enter your full address"/>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

              {{-- Blood Type --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">
                  Blood Type
                  @if($hasDonations)
                    <span style="font-size:0.68rem; font-weight:400; color:#aaa; margin-left:4px;"></span>
                  @endif
                </label>

                @if($hasDonations)
                  <div class="profile-value" style="display:flex; align-items:center; gap:0.5rem;">
                    <x-blood-type-badge :type="$donor->bloodType->type_name ?? 'Unknown'"/>
                    <span style="font-size:0.72rem; color:#aaa;">Confirmed by donation</span>
                  </div>
                @else
                  <template x-if="!editing">
                    <div class="profile-value">
                      {{ $donor->bloodType->type_name ?? 'Unknown / Not sure yet' }}
                    </div>
                  </template>
                  <template x-if="editing">
                    <div>
                      <select name="blood_type_id" class="form-input form-input-light">
                        <option value="">Unknown / Not sure yet</option>
                        @foreach($bloodTypes as $bt)
                          <option value="{{ $bt->blood_type_id }}"
                            {{ old('blood_type_id', $donor->blood_type_id) == $bt->blood_type_id ? 'selected' : '' }}>
                            {{ $bt->type_name }}
                          </option>
                        @endforeach
                      </select>
                      <div style="font-size:0.7rem; color:#bbb; margin-top:0.25rem;">
                        Will be locked after your first donation.
                      </div>
                      @error('blood_type_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                @endif
              </div>

            </div>{{-- end row 2 --}}

            {{-- Save button --}}
            <div x-show="editing" x-cloak style="margin-top:1rem; text-align:right;">
              <button type="submit" class="btn btn-dash-primary">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                Save Changes
              </button>
            </div>

          </div>{{-- end dash-card-body --}}
        </div>{{-- end dash-card --}}

      </form>
    </div>{{-- end personal tab --}}

    {{-- ── Account Settings ───────────────────────────────── --}}
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
            <form method="POST" action="{{ route('donor.profile.email') }}">
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
            <form method="POST" action="{{ route('donor.profile.password') }}"
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

  @push('scripts')
  <script>
    function initDobPicker() {
  const el = document.querySelector('.flatpickr-dob');
  if (el && !el._flatpickr) {
    flatpickr(el, {
      dateFormat: 'm/d/Y',
      maxDate: new Date(new Date().setFullYear(new Date().getFullYear() - 18)),
      minDate: new Date(new Date().setFullYear(new Date().getFullYear() - 65)),
      allowInput: true,
    });
  }
}

    function avatarPreview() {
      return {
        preview: null,
        onFileChange(event) {
          const file = event.target.files[0];
          if (!file) return;
          const reader = new FileReader();
          reader.onload = (e) => {
            this.preview = e.target.result;
            // Auto-submit the form after preview renders
            this.$nextTick(() => {
              document.getElementById('avatarForm').submit();
            });
          };
          reader.readAsDataURL(file);
        }
      }
    }
  </script>
  @endpush

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
      div[style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr"]     { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
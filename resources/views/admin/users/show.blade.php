<x-app-layout title="User Profile">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('admin.users.index') }}">Users</a>
        <span class="breadcrumb-sep">›</span>
        <span>{{ $user->name }}</span>
      </div>
      <h1 class="page-title">User Profile</h1>
    </div>
    <a href="{{ route('admin.users.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  {{-- Profile header card --}}
  <div class="dash-card" style="margin-bottom:1.5rem; position:relative; overflow:hidden;">

    {{-- Diagonal stripes --}}
    @if($user->isDonor())
      <div style="position:absolute; top:-80px; left:-80px; width:350px; height:350px; background:rgba(192,57,43,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
      <div style="position:absolute; top:-50px; left:-50px; width:250px; height:250px; background:rgba(192,57,43,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
      <div style="position:absolute; bottom:-80px; left:200px; width:300px; height:300px; background:rgba(233,69,96,0.04); transform:rotate(45deg); border-radius:45px; pointer-events:none;"></div>
      <div style="position:absolute; top:-60px; right:-60px; width:350px; height:350px; background:rgba(192,57,43,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
      <div style="position:absolute; top:-40px; right:-40px; width:250px; height:250px; background:rgba(233,69,96,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
    @elseif($user->isHospital())
      <div style="position:absolute; top:-80px; left:-80px; width:350px; height:350px; background:rgba(83,52,131,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
      <div style="position:absolute; top:-50px; left:-50px; width:250px; height:250px; background:rgba(83,52,131,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
      <div style="position:absolute; bottom:-80px; left:200px; width:300px; height:300px; background:rgba(142,68,173,0.04); transform:rotate(45deg); border-radius:45px; pointer-events:none;"></div>
      <div style="position:absolute; top:-60px; right:-60px; width:350px; height:350px; background:rgba(83,52,131,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
      <div style="position:absolute; top:-40px; right:-40px; width:250px; height:250px; background:rgba(142,68,173,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
    @else
      <div style="position:absolute; top:-80px; left:-80px; width:350px; height:350px; background:rgba(41,128,185,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
      <div style="position:absolute; top:-50px; left:-50px; width:250px; height:250px; background:rgba(41,128,185,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
      <div style="position:absolute; bottom:-80px; left:200px; width:300px; height:300px; background:rgba(52,152,219,0.04); transform:rotate(45deg); border-radius:45px; pointer-events:none;"></div>
      <div style="position:absolute; top:-60px; right:-60px; width:350px; height:350px; background:rgba(41,128,185,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
      <div style="position:absolute; top:-40px; right:-40px; width:250px; height:250px; background:rgba(52,152,219,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
    @endif

    <div style="padding:1.75rem 2rem; display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; position:relative; z-index:1;">

      {{-- Avatar --}}
      @if($user->isDonor())
        <div style="width:72px; height:72px; border-radius:50%; overflow:hidden; background:linear-gradient(135deg,var(--primary),#e94560); display:flex; align-items:center; justify-content:center; font-family:var(--font-display); font-size:1.6rem; font-weight:800; color:#fff; flex-shrink:0; box-shadow:0 6px 20px rgba(192,57,43,0.3); border:3px solid #fff; outline:2px solid rgba(192,57,43,0.15);">
          @if($user->donor?->avatar)
            <img src="{{ asset($user->donor->avatar) }}" alt="{{ $user->name }}" style="width:100%; height:100%; object-fit:cover;"/>
          @else
            {{ strtoupper(substr($user->name, 0, 1)) }}
          @endif
        </div>
      @elseif($user->isHospital())
        <div style="width:72px; height:72px; border-radius:16px; background:linear-gradient(135deg,#533483,#9B59B6); display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 6px 20px rgba(83,52,131,0.3);">
          <svg width="32" height="32" fill="none" stroke="#fff" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
          </svg>
        </div>
      @else
        <div style="width:72px; height:72px; border-radius:16px; background:linear-gradient(135deg,#2980B9,#3498DB); display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 6px 20px rgba(41,128,185,0.3);">
          <svg width="32" height="32" fill="none" stroke="#fff" stroke-width="1.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
          </svg>
        </div>
      @endif

      {{-- Name + meta --}}
      <div style="flex:1; min-width:200px;">
        <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap; margin-bottom:0.4rem;">
          <h2 style="font-family:var(--font-display); font-size:1.5rem; font-weight:700; color:#1a1a2e; margin:0;">{{ $user->name }}</h2>
          <span class="badge badge-role-{{ $user->role->value }}">{{ ucfirst($user->role->value) }}</span>
          <x-status-badge :status="$user->status->value"/>
        </div>
        <div style="font-size:0.875rem; color:#888; display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap;">
          <span style="display:flex; align-items:center; gap:0.4rem;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
            {{ $user->email }}
          </span>
          <span style="display:flex; align-items:center; gap:0.4rem;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Registered {{ $user->created_at->format('M d, Y') }}
          </span>
        </div>
      </div>

      {{-- Quick actions --}}
      <div style="display:flex; gap:0.75rem; flex-wrap:wrap;" x-data>
        @if($user->isPending())
          <button @click="$dispatch('open-modal', 'approve-user')" class="btn btn-success btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
            Approve
          </button>
        @endif
        @if($user->isActive() && !$user->isAdmin())
          <button @click="$dispatch('open-modal', 'suspend-user')" class="btn btn-warning btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
            Suspend
          </button>
        @endif
        @if($user->isInactive() && !$user->isAdmin())
          <button @click="$dispatch('open-modal', 'reactivate-user')" class="btn btn-success btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Reactivate
          </button>
        @endif
        @if(!($user->isAdmin() && auth()->id() === $user->id))
          <button @click="$dispatch('open-modal', 'delete-user')" class="btn btn-sm" style="background:#fff; border:1.5px solid rgba(192,57,43,0.3); color:#E74C3C;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            Delete
          </button>
        @endif
      </div>

    </div>
  </div>

  {{-- Tabs --}}
  <div x-data="{ tab: '{{ $user->isDonor() ? 'info' : ($user->isHospital() ? 'info' : 'account') }}' }">

    <div class="tab-nav" style="margin-bottom:1.25rem;">

      {{-- Donor tabs --}}
      @if($user->isDonor())
        <button class="tab-btn" :class="tab==='info'?'active':''" @click="tab='info'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          Personal Info
        </button>
        <button class="tab-btn" :class="tab==='account'?'active':''" @click="tab='account'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Account Settings
        </button>
        <button class="tab-btn" :class="tab==='donations'?'active':''" @click="tab='donations'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
          Donations
          <span style="font-size:0.72rem; background:rgba(192,57,43,0.1); color:var(--primary); border-radius:20px; padding:0.05rem 0.45rem; margin-left:0.25rem;">{{ $user->donor?->donations->count() ?? 0 }}</span>
        </button>
        <button class="tab-btn" :class="tab==='screenings'?'active':''" @click="tab='screenings'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Screenings
          <span style="font-size:0.72rem; background:rgba(39,174,96,0.1); color:#27AE60; border-radius:20px; padding:0.05rem 0.45rem; margin-left:0.25rem;">{{ $user->donor?->screenings->count() ?? 0 }}</span>
        </button>
        <button class="tab-btn" :class="tab==='appointments'?'active':''" @click="tab='appointments'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
          Appointments
          <span style="font-size:0.72rem; background:rgba(41,128,185,0.1); color:#2980B9; border-radius:20px; padding:0.05rem 0.45rem; margin-left:0.25rem;">{{ $user->donor?->appointments->count() ?? 0 }}</span>
        </button>

      {{-- Hospital tabs --}}
      @elseif($user->isHospital())
        <button class="tab-btn" :class="tab==='info'?'active':''" @click="tab='info'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          Hospital Info
        </button>
        <button class="tab-btn" :class="tab==='account'?'active':''" @click="tab='account'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
          Account Settings
        </button>
        <button class="tab-btn" :class="tab==='requests'?'active':''" @click="tab='requests'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          Blood Requests
          <span style="font-size:0.72rem; background:rgba(142,68,173,0.1); color:#8E44AD; border-radius:20px; padding:0.05rem 0.45rem; margin-left:0.25rem;">{{ $user->hospital?->requests->count() ?? 0 }}</span>
        </button>

      {{-- Staff/Admin tabs --}}
      @else
        <button class="tab-btn" :class="tab==='account'?'active':''" @click="tab='account'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
          Account Info
        </button>
      @endif

      {{-- Notifications tab (all roles) --}}
      <button class="tab-btn" :class="tab==='notifications'?'active':''" @click="tab='notifications'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
        Notifications
      </button>
    </div>

    {{-- ── DONOR: Personal Info Tab ── --}}
    @if($user->isDonor() && $user->donor)
      @php $donor = $user->donor; @endphp
      <div x-show="tab==='info'" x-cloak>
        <div class="dash-card" x-data="{ editing: false }">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Personal Details</h3>
            <div style="display:flex; gap:0.65rem;">
              <button type="button" x-show="!editing" @click="editing=true"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
                onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
                onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button type="button" x-show="editing" @click="editing=false"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#888; font-size:0.8rem; font-weight:600; cursor:pointer;">
                Cancel
              </button>
            </div>
          </div>
          <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PATCH')
            <div class="dash-card-body">
              <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1rem;">

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Full Name</label>
                  <template x-if="!editing"><div class="profile-value">{{ $user->name }}</div></template>
                  <template x-if="editing">
                    <div>
                      <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input form-input-light"/>
                      @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Email</label>
                  <template x-if="!editing"><div class="profile-value">{{ $user->email }}</div></template>
                  <template x-if="editing">
                    <div>
                      <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input form-input-light"/>
                      @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Contact Number</label>
                  <template x-if="!editing"><div class="profile-value">{{ $donor->contact_number ?? '—' }}</div></template>
                  <template x-if="editing">
                    <div>
                      <input type="text" name="contact_number" value="{{ old('contact_number', $donor->contact_number) }}" class="form-input form-input-light" placeholder="09XXXXXXXXX" maxlength="11" oninput="this.value=this.value.replace(/[^0-9]/g,'').slice(0,11)"/>
                      @error('contact_number')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Gender</label>
                  <template x-if="!editing"><div class="profile-value">{{ ucfirst($donor->gender) }}</div></template>
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

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Date of Birth</label>
                  <template x-if="!editing"><div class="profile-value">{{ $donor->date_of_birth->format('F d, Y') }}</div></template>
                  <template x-if="editing">
                    <div>
                      <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $donor->date_of_birth->format('Y-m-d')) }}" class="form-input form-input-light"/>
                      @error('date_of_birth')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Blood Type</label>
                  @if($donor->donations()->exists())
                    <div class="profile-value" style="gap:0.5rem;">
                      <x-blood-type-badge :type="$donor->bloodType->type_name ?? 'Unknown'"/>
                      <span style="font-size:0.72rem; color:#aaa;">Confirmed by donation</span>
                    </div>
                  @else
                    <template x-if="!editing"><div class="profile-value">{{ $donor->bloodType->type_name ?? 'Unknown' }}</div></template>
                    <template x-if="editing">
                      <div>
                        <select name="blood_type_id" class="form-input form-input-light">
                          <option value="">Unknown / Not sure yet</option>
                          @foreach($bloodTypes ?? [] as $bt)
                            <option value="{{ $bt->blood_type_id }}" {{ old('blood_type_id', $donor->blood_type_id) == $bt->blood_type_id ? 'selected' : '' }}>{{ $bt->type_name }}</option>
                          @endforeach
                        </select>
                        @error('blood_type_id')<div class="form-error">{{ $message }}</div>@enderror
                      </div>
                    </template>
                  @endif
                </div>

              </div>

              <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Address</label>
                <template x-if="!editing"><div class="profile-value">{{ $donor->address ?? '—' }}</div></template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="address" value="{{ old('address', $donor->address) }}" class="form-input form-input-light" placeholder="House/Unit No., Street, Barangay, City"/>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

              <div x-show="editing" x-cloak style="text-align:right;">
                <button type="submit" class="btn btn-dash-primary">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                  Save Changes
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endif

    {{-- ── HOSPITAL: Hospital Info Tab ── --}}
    @if($user->isHospital() && $user->hospital)
      @php $hospital = $user->hospital; @endphp
      <div x-show="tab==='info'" x-cloak>
        <div class="dash-card" x-data="{ editing: false }">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Hospital Details</h3>
            <div style="display:flex; gap:0.65rem;">
              <button type="button" x-show="!editing" @click="editing=true"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
                onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
                onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
                <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                Edit
              </button>
              <button type="button" x-show="editing" @click="editing=false"
                style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#888; font-size:0.8rem; font-weight:600; cursor:pointer;">
                Cancel
              </button>
            </div>
          </div>
          <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf @method('PATCH')
            <div class="dash-card-body">
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Hospital Name</label>
                  <template x-if="!editing"><div class="profile-value">{{ $hospital->hospital_name }}</div></template>
                  <template x-if="editing">
                    <div>
                      <input type="text" name="hospital_name" value="{{ old('hospital_name', $hospital->hospital_name) }}" required class="form-input form-input-light"/>
                      @error('hospital_name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Email</label>
                  <template x-if="!editing"><div class="profile-value">{{ $user->email }}</div></template>
                  <template x-if="editing">
                    <div>
                      <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input form-input-light"/>
                      @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">License Number</label>
                  <template x-if="!editing"><div class="profile-value">{{ $hospital->license_number }}</div></template>
                  <template x-if="editing">
                    <div>
                      <input type="text" name="license_number" value="{{ old('license_number', $hospital->license_number) }}" required class="form-input form-input-light"/>
                      @error('license_number')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                  <label class="form-label">Contact Number</label>
                  <template x-if="!editing"><div class="profile-value">{{ $hospital->contact_number ?? '—' }}</div></template>
                  <template x-if="editing">
                    <div>
                      <input type="text" name="contact_number" value="{{ old('contact_number', $hospital->contact_number) }}" class="form-input form-input-light" placeholder="09XXXXXXXXX"/>
                      @error('contact_number')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                  </template>
                </div>

              </div>

              <div class="form-group" style="margin-bottom:1rem;">
                <label class="form-label">Complete Address</label>
                <template x-if="!editing"><div class="profile-value">{{ $hospital->address ?? '—' }}</div></template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="address" value="{{ old('address', $hospital->address) }}" class="form-input form-input-light" placeholder="Street, Barangay, City"/>
                    @error('address')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

              <div x-show="editing" x-cloak style="text-align:right;">
                <button type="submit" class="btn btn-dash-primary">
                  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                  Save Changes
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    @endif

    {{-- ── Account Settings Tab (all roles) ── --}}
    <div x-show="tab==='account'" x-cloak>
      <div class="dash-card" x-data="{ editing: false }">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Account Settings</h3>
          <div style="display:flex; gap:0.65rem;">
            <button type="button" x-show="!editing" @click="editing=true"
              style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
              onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
              onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
              Edit
            </button>
            <button type="button" x-show="editing" @click="editing=false"
              style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#888; font-size:0.8rem; font-weight:600; cursor:pointer;">
              Cancel
            </button>
          </div>
        </div>
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
          @csrf @method('PATCH')
          <div class="dash-card-body">
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Full Name</label>
                <template x-if="!editing"><div class="profile-value">{{ $user->name }}</div></template>
                <template x-if="editing">
                  <div>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input form-input-light"/>
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Email</label>
                <template x-if="!editing"><div class="profile-value">{{ $user->email }}</div></template>
                <template x-if="editing">
                  <div>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input form-input-light"/>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              </div>

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Registered</label>
                <div class="profile-value">{{ $user->created_at->format('F d, Y') }}</div>
              </div>

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Last Updated</label>
                <div class="profile-value">{{ $user->updated_at->diffForHumans() }}</div>
              </div>

            </div>

            {{-- Password reset section --}}
            <div x-show="editing" x-cloak style="padding:1rem; background:#f8f9fc; border-radius:12px; border:1px dashed #dee2e6; margin-bottom:1rem;">
              <p style="font-size:0.8rem; color:#666; margin-bottom:0.75rem;">
                <strong>Password:</strong> Leave blank to keep current password.
              </p>
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
                <div>
                  <input type="password" name="password" autocomplete="new-password" class="form-input form-input-light" placeholder="New Password"/>
                  @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div>
                  <input type="password" name="password_confirmation" autocomplete="new-password" class="form-input form-input-light" placeholder="Confirm New Password"/>
                </div>
              </div>
            </div>

            <div x-show="editing" x-cloak style="text-align:right;">
              <button type="submit" class="btn btn-dash-primary">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                Save Changes
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>

    {{-- ── DONOR: Donations Tab ── --}}
    @if($user->isDonor() && $user->donor)
      <div x-show="tab==='donations'" x-cloak>
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Donation History</h3>
            <a href="{{ route('admin.donations.create') }}?donor_id={{ $user->donor->donor_id }}" class="btn btn-dash-primary btn-sm">+ Record Donation</a>
          </div>
          @if($user->donor->donations->isEmpty())
            <div class="dash-card-body"><x-empty-state title="No donations recorded yet"/></div>
          @else
            <div class="table-container" style="border:none; border-radius:0;">
              <table class="data-table">
                <thead><tr><th>Date</th><th>Volume</th><th>Status</th><th>Screening</th><th>Staff</th></tr></thead>
                <tbody>
                  @foreach($user->donor->donations->sortByDesc('donation_date') as $d)
                    <tr>
                      <td style="font-weight:500;">{{ $d->donation_date->format('M d, Y') }}</td>
                      <td>{{ $d->volume }} mL</td>
                      <td><x-status-badge :status="$d->status->value" size="sm"/></td>
                      <td>
                        @if($d->screening)
                          <x-status-badge :status="$d->screening->eligibility_status->value" size="sm"/>
                        @else
                          <span style="color:#ddd;">—</span>
                        @endif
                      </td>
                      <td style="font-size:0.82rem; color:#888;">{{ $d->staff->name ?? '—' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- ── DONOR: Screenings Tab ── --}}
      <div x-show="tab==='screenings'" x-cloak>
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Screening Records</h3>
            <a href="{{ route('admin.screenings.create') }}?donor_id={{ $user->donor->donor_id }}" class="btn btn-dash-primary btn-sm">+ Record Screening</a>
          </div>
          @if($user->donor->screenings->isEmpty())
            <div class="dash-card-body"><x-empty-state title="No screenings recorded yet"/></div>
          @else
            <div class="table-container" style="border:none; border-radius:0;">
              <table class="data-table">
                <thead><tr><th>Date</th><th>Blood Pressure</th><th>Hemoglobin</th><th>Weight</th><th>Eligibility</th></tr></thead>
                <tbody>
                  @foreach($user->donor->screenings->sortByDesc('screening_date') as $s)
                    <tr>
                      <td style="font-weight:500;">{{ optional($s->screening_date)->format('M d, Y') ?? '—' }}</td>
                      <td>{{ $s->blood_pressure ?? '—' }}</td>
                      <td>{{ $s->hemoglobin_level ? $s->hemoglobin_level . ' g/dL' : '—' }}</td>
                      <td>{{ $s->weight ? $s->weight . ' kg' : '—' }}</td>
                      <td><x-status-badge :status="$s->eligibility_status->value" size="sm"/></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>

      {{-- ── DONOR: Appointments Tab ── --}}
      <div x-show="tab==='appointments'" x-cloak>
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Appointments</h3>
            <a href="{{ route('admin.appointments.create') }}?donor_id={{ $user->donor->donor_id }}" class="btn btn-dash-primary btn-sm">+ Make Appointment</a>
          </div>
          @if($user->donor->appointments->isEmpty())
            <div class="dash-card-body"><x-empty-state title="No appointments recorded yet"/></div>
          @else
            <div class="table-container" style="border:none; border-radius:0;">
              <table class="data-table">
                <thead><tr><th>Date & Time</th><th>Status</th><th>Notes</th><th>Donation</th></tr></thead>
                <tbody>
                  @foreach($user->donor->appointments->sortByDesc('appointment_date') as $a)
                    <tr>
                      <td>
                        <div style="font-weight:500;">{{ $a->appointment_date->format('M d, Y') }}</div>
                        <div style="font-size:0.75rem; color:#999;">{{ $a->appointment_date->format('h:i A') }}</div>
                      </td>
                      <td><x-status-badge :status="$a->status->value" size="sm"/></td>
                      <td style="font-size:0.82rem; color:#888; max-width:160px;">{{ $a->notes ? Str::limit($a->notes, 40) : '—' }}</td>
                      <td style="font-size:0.85rem; color:#555;">{{ $a->donation ? $a->donation->volume . ' mL' : '—' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    @endif

    {{-- ── HOSPITAL: Blood Requests Tab ── --}}
    @if($user->isHospital() && $user->hospital)
      <div x-show="tab==='requests'" x-cloak>
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Blood Request History</h3>
          </div>
          @if($user->hospital->requests->isEmpty())
            <div class="dash-card-body"><x-empty-state title="No requests yet" message="This hospital has not submitted any blood requests."/></div>
          @else
            <div class="table-container" style="border:none; border-radius:0;">
              <table class="data-table">
                <thead>
                  <tr><th>Request #</th><th>Blood Type</th><th>Qty</th><th>Urgency</th><th>Fulfillment</th><th>Date</th><th>Status</th></tr>
                </thead>
                <tbody>
                  @foreach($user->hospital->requests->sortByDesc('request_date') as $req)
                    @php $urgencyColors = ['routine'=>'#27AE60','urgent'=>'#F39C12','emergency'=>'#E74C3C']; $urgencyColor = $urgencyColors[$req->urgency ?? 'routine'] ?? '#888'; @endphp
                    <tr>
                      <td><span style="font-family:var(--font-mono); font-size:0.8rem; color:#888; background:#f5f5f5; padding:0.2rem 0.5rem; border-radius:6px;">#{{ $req->request_id }}</span></td>
                      <td><x-blood-type-badge :type="$req->bloodType->type_name"/></td>
                      <td style="font-weight:700;">{{ $req->quantity }}</td>
                      <td><span style="font-size:0.75rem; font-weight:600; color:{{ $urgencyColor }}; background:{{ $urgencyColor }}15; border:1px solid {{ $urgencyColor }}33; padding:0.2rem 0.55rem; border-radius:6px; text-transform:capitalize;">{{ ucfirst($req->urgency ?? 'routine') }}</span></td>
                      <td style="font-size:0.82rem; color:#888; text-transform:capitalize;">{{ $req->fulfillment_type ?? 'pickup' }}</td>
                      <td style="font-size:0.82rem; color:#888; white-space:nowrap;">{{ $req->request_date->format('M d, Y') }}</td>
                      <td><x-status-badge :status="$req->status->value" size="sm"/></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          @endif
        </div>
      </div>
    @endif

    {{-- ── Notifications Tab (all roles) ── --}}
    <div x-show="tab==='notifications'" x-cloak>
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Notification History</h3>
        </div>
        @php $notifs = $user->notifications()->latest()->take(20)->get(); @endphp
        @if($notifs->isEmpty())
          <div class="dash-card-body"><x-empty-state title="No notifications sent yet"/></div>
        @else
          @foreach($notifs as $notif)
            <x-notification-item :notification="$notif"/>
          @endforeach
        @endif
      </div>
    </div>

  </div>

  {{-- Modals --}}
  <div x-data>
    <x-confirm-dialog id="approve-user" title="Approve Account?"
      message="Activate {{ $user->name }}'s account. They will be able to log in immediately."
      confirm-label="Approve" confirm-class="btn-success"
      action="{{ route('admin.users.approve', $user) }}" method="PATCH"/>

    <x-confirm-dialog id="suspend-user" title="Suspend Account?"
      message="This will prevent {{ $user->name }} from logging in until reinstated."
      confirm-label="Suspend" confirm-class="btn-warning"
      action="{{ route('admin.users.suspend', $user) }}" method="PATCH"/>

    <x-confirm-dialog id="reactivate-user" title="Reactivate Account?"
      message="Reactivate {{ $user->name }}'s account? They will be able to log in immediately."
      confirm-label="Reactivate" confirm-class="btn-success"
      action="{{ route('admin.users.reactivate', $user) }}" method="PATCH"/>

    <x-confirm-dialog id="delete-user" title="Delete User?"
      message="Permanently delete {{ $user->name }} and all associated data. This cannot be undone."
      confirm-label="Delete Forever" confirm-class="btn-danger"
      action="{{ route('admin.users.destroy', $user) }}" method="DELETE"/>
  </div>

  <style>
    [x-cloak] { display: none !important; }
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
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 768px) {
      .tab-nav { flex-wrap: wrap; }
    }
  </style>

</x-app-layout>
<x-app-layout title="Donor Profile">

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
        <span>{{ $donor->name }}</span>
      </div>
      <h1 class="page-title">Donor Profile</h1>
    </div>

    {{-- Back button only --}}
    <a href="{{ route($routePrefix . '.donors.index') }}"
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
  <div style="position:absolute; top:-80px; left:-80px; width:350px; height:350px; background:rgba(192,57,43,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
  <div style="position:absolute; top:-50px; left:-50px; width:250px; height:250px; background:rgba(192,57,43,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
  <div style="position:absolute; bottom:-80px; left:200px; width:300px; height:300px; background:rgba(233,69,96,0.04); transform:rotate(45deg); border-radius:45px; pointer-events:none;"></div>
  <div style="position:absolute; top:-60px; right:-60px; width:350px; height:350px; background:rgba(192,57,43,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
  <div style="position:absolute; top:-40px; right:-40px; width:250px; height:250px; background:rgba(233,69,96,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
  <div style="position:absolute; bottom:-60px; right:200px; width:280px; height:280px; background:rgba(192,57,43,0.04); transform:rotate(45deg); border-radius:40px; pointer-events:none;"></div>
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) rotate(45deg); width:200px; height:200px; background:rgba(233,69,96,0.03); border-radius:30px; pointer-events:none;"></div>

  <div style="padding:1.75rem 2rem; display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap; position:relative; z-index:1;">

    {{-- Avatar: photo if exists, else initials --}}
    <div style="width:72px; height:72px; border-radius:50%; overflow:hidden; background:linear-gradient(135deg,var(--primary),#e94560); display:flex; align-items:center; justify-content:center; font-family:var(--font-display); font-size:1.5rem; font-weight:800; color:#fff; box-shadow:0 6px 20px rgba(192,57,43,0.3); border:3px solid #fff; outline:2px solid rgba(192,57,43,0.15); flex-shrink:0;">
      @if($donor->avatar)
        <img src="{{ asset($donor->avatar) }}" alt="{{ $donor->name }}" style="width:100%; height:100%; object-fit:cover;"/>
      @else
        {{ strtoupper(substr($donor->name, 0, 1)) }}
      @endif
    </div>

    {{-- Name + meta --}}
    <div style="flex:1; min-width:0;">
      <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap; margin-bottom:0.4rem;">
    <h2 style="font-family:var(--font-display); font-size:1.5rem; font-weight:700; color:#1a1a2e; margin:0;">{{ $donor->name }}</h2>
    <span class="badge badge-role-donor">Donor</span>
    <x-status-badge :status="$donor->user->status->value"/>
</div>
<div style="display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap;">
    <span style="display:flex; align-items:center; gap:0.4rem; font-size:0.82rem; color:#888;">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        {{ $donor->user->email }}
    </span>
    <span style="display:flex; align-items:center; gap:0.4rem; font-size:0.82rem; color:#888;">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        {{ ucfirst($donor->gender) }}
    </span>
    <span style="display:flex; align-items:center; gap:0.4rem; font-size:0.82rem; color:#888;">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        {{ $donor->date_of_birth?->format('M d, Y') ?? '—' }}
    </span>
</div>
    </div>

    {{-- Member since --}}
    <div style="text-align:right; flex-shrink:0;">
      <div style="font-size:0.72rem; color:#bbb; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.3rem;">Member since</div>
      <div style="font-size:0.9rem; font-weight:600; color:#1a1a2e;">{{ $donor->user->created_at->format('M Y') }}</div>
    </div>

  </div>
</div>

  {{-- Tabs --}}
  <div x-data="{ tab: 'info' }">

    <div class="tab-nav" style="margin-bottom:1.25rem;">
      <button class="tab-btn" :class="tab==='info'?'active':''" @click="tab='info'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Personal Info
      </button>
      <button class="tab-btn" :class="tab==='donations'?'active':''" @click="tab='donations'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        Donations <span style="font-size:0.72rem; background:rgba(192,57,43,0.1); color:var(--primary); border-radius:20px; padding:0.05rem 0.45rem; margin-left:0.25rem;">{{ $donor->donations->count() }}</span>
      </button>
      <button class="tab-btn" :class="tab==='screenings'?'active':''" @click="tab='screenings'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Screenings <span style="font-size:0.72rem; background:rgba(39,174,96,0.1); color:#27AE60; border-radius:20px; padding:0.05rem 0.45rem; margin-left:0.25rem;">{{ $donor->screenings->count() }}</span>
      </button>
      <button class="tab-btn" :class="tab==='appointments'?'active':''" @click="tab='appointments'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Appointments <span style="font-size:0.72rem; background:rgba(41,128,185,0.1); color:#2980B9; border-radius:20px; padding:0.05rem 0.45rem; margin-left:0.25rem;">{{ $donor->appointments->count() }}</span>
      </button>
    </div>

    {{-- Personal Info --}}
<div x-show="tab==='info'" x-cloak>
  <div style="display:grid; grid-template-columns:1fr; gap:1.25rem;">

    {{-- Left: Personal details --}}
    <div class="dash-card" x-data="{ editing: false }">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Personal Details</h3>
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

      <form method="POST" action="{{ route($routePrefix . '.donors.update', $donor) }}">
        @csrf @method('PATCH')
        <div class="dash-card-body">

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

            {{-- Email --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Email</label>
              <template x-if="!editing">
                <div class="profile-value">{{ old('email', $donor->user->email) }}</div>
              </template>
              <template x-if="editing">
                <div>
                  <input type="email" name="email"
                    value="{{ old('email', $donor->user->email) }}" required
                    class="form-input form-input-light"/>
                  @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
              </template>
            </div>

            {{-- Contact --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Contact Number</label>
              <template x-if="!editing">
                <div class="profile-value">{{ $donor->contact_number ?? '—' }}</div>
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
                <div class="profile-value">{{ ucfirst($donor->gender) }}</div>
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
                    class="form-input form-input-light dob-picker"
                    placeholder="MM/DD/YYYY" autocomplete="off" readonly/>
                  @error('date_of_birth')<div class="form-error">{{ $message }}</div>@enderror
                </div>
              </template>
            </div>

            {{-- Blood Type --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Blood Type</label>
              @if($donor->donations()->exists())
                <div class="profile-value" style="display:flex; align-items:center; gap:0.5rem;">
                  <x-blood-type-badge :type="$donor->bloodType->type_name ?? 'Unknown'"/>
                  <span style="font-size:0.72rem; color:#aaa;">Confirmed by donation</span>
                </div>
              @else
                <template x-if="!editing">
                  <div class="profile-value">{{ $donor->bloodType->type_name ?? 'Unknown / Not sure yet' }}</div>
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
                    @error('blood_type_id')<div class="form-error">{{ $message }}</div>@enderror
                  </div>
                </template>
              @endif
            </div>

          </div>

          {{-- Address --}}
          <div class="form-group" style="margin-bottom:1rem;">
            <label class="form-label">Address</label>
            <template x-if="!editing">
              <div class="profile-value">{{ $donor->address ?? '—' }}</div>
            </template>
            <template x-if="editing">
              <div>
                <input type="text" name="address"
                  value="{{ old('address', $donor->address) }}"
                  class="form-input form-input-light" placeholder="House/Unit No., Street, Barangay, City"/>
                @error('address')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- Save button --}}
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
</div>

    {{-- Donations --}}
    <div x-show="tab==='donations'" x-cloak>
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Donation History</h3>
          <a href="{{ route($routePrefix . '.donations.create') }}?donor_id={{ $donor->donor_id }}"
            class="btn btn-dash-primary btn-sm">
            + Record Donation
          </a>
        </div>
        @if($donor->donations->isEmpty())
          <div class="dash-card-body">
            <x-empty-state title="No donations recorded yet"/>
          </div>
        @else
          <div class="table-container" style="border:none; border-radius:0;">
            <table class="data-table">
              <thead>
                <tr><th>Date</th><th>Volume</th><th>Status</th><th>Screening</th><th>Units</th><th>Staff</th></tr>
              </thead>
              <tbody>
                @foreach($donor->donations->sortByDesc('donation_date') as $d)
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
                    <td style="font-weight:600;">{{ $d->bloodUnits->count() }}</td>
                    <td style="font-size:0.82rem; color:#888;">{{ $d->staff->name ?? '—' }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

    {{-- Screenings --}}
    <div x-show="tab==='screenings'" x-cloak>
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Screening Records</h3>
          <a href="{{ route($routePrefix . '.screenings.create') }}?donor_id={{ $donor->donor_id }}"
            class="btn btn-dash-primary btn-sm">
            + Record Screening
          </a>
        </div>
        @if($donor->screenings->isEmpty())
          <div class="dash-card-body">
            <x-empty-state title="No screenings recorded yet"/>
          </div>
        @else
          <div class="table-container" style="border:none; border-radius:0;">
            <table class="data-table">
              <thead>
                <tr><th>Date</th><th>Blood Pressure</th><th>Hemoglobin</th><th>Weight</th><th>Eligibility</th></tr>
              </thead>
              <tbody>
                @foreach($donor->screenings->sortByDesc('screening_date') as $s)
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

    {{-- Appointments --}}
    <div x-show="tab==='appointments'" x-cloak>
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Appointments</h3>
          <a href="{{ route($routePrefix . '.appointments.create') }}?donor_id={{ $donor->donor_id }}"
            class="btn btn-dash-primary btn-sm">
            + Make Appointment
          </a>
        </div>
        @if($donor->appointments->isEmpty())
          <div class="dash-card-body">
            <x-empty-state title="No appointments recorded yet"/>
          </div>
        @else
          <div class="table-container" style="border:none; border-radius:0;">
            <table class="data-table">
              <thead>
                <tr><th>Date & Time</th><th>Status</th><th>Notes</th><th>Donation</th></tr>
              </thead>
              <tbody>
                @foreach($donor->appointments->sortByDesc('appointment_date') as $a)
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

  </div>

  <style>
    [x-cloak] { display: none !important; }
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 768px) {
      .tab-nav { flex-wrap: wrap; }
    }

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
  </style>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', () => {
      const dobEl = document.querySelector('.dob-picker');
      if (dobEl && !dobEl._flatpickr && typeof flatpickr !== 'undefined') {
        flatpickr(dobEl, {
          dateFormat:    'n/j/Y',
          altInput:      true,
          altFormat:     'm/d/Y',
          defaultDate:   dobEl.value || null,
          maxDate:       new Date(new Date().setFullYear(new Date().getFullYear() - 18)),
          minDate:       new Date(new Date().setFullYear(new Date().getFullYear() - 65)),
          disableMobile: true,
          allowInput:    false,
        });
      }
    });
  });
</script>
@endpush

</x-app-layout>
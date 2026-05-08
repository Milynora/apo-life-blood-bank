<x-app-layout title="Screening Details">

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
        <a href="{{ route($routePrefix . '.screenings.index') }}">Screenings</a>
        <span class="breadcrumb-sep">›</span>
        <span>Details</span>
      </div>
      <h1 class="page-title">Screening Details</h1>
    </div>
    <a href="{{ route($routePrefix . '.screenings.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

    {{-- Screening data --}}
    <div class="dash-card" x-data="{ editing: false }">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Screening Record</h3>
        <div style="display:flex; align-items:center; gap:0.65rem;">
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
        <form method="POST" action="{{ route($routePrefix . '.screenings.update', $screening) }}">
          @csrf @method('PATCH')

          {{-- Eligibility banner --}}
          <div x-show="!editing" style="text-align:center; padding:1.25rem; border-radius:12px; margin-bottom:1.25rem;
              background:{{ $screening->eligibility_status->value==='fit' ? 'rgba(39,174,96,0.08)' : 'rgba(192,57,43,0.08)' }};
              border:1px solid {{ $screening->eligibility_status->value==='fit' ? 'rgba(39,174,96,0.25)' : 'rgba(192,57,43,0.25)' }};">
            <svg width="32" height="32" fill="none" stroke="{{ $screening->eligibility_status->value==='fit' ? '#27AE60' : '#E74C3C' }}" stroke-width="1.5" viewBox="0 0 24 24" style="margin:0 auto 0.5rem; display:block;">
              @if($screening->eligibility_status->value==='fit')
                <path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
              @else
                <path stroke-linecap="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
              @endif
            </svg>
            <div style="font-family:var(--font-display); font-size:1.2rem; font-weight:800; color:{{ $screening->eligibility_status->value==='fit' ? '#27AE60' : '#E74C3C' }};">
              {{ strtoupper($screening->eligibility_status->value) }} TO DONATE
            </div>
          </div>

          {{-- Screening Date --}}
          <div class="form-group" style="margin-bottom:0.75rem;">
            <label class="form-label">Screening Date</label>
            <template x-if="!editing">
              <div class="profile-value">{{ optional($screening->screening_date)->format('F d, Y') ?? 'N/A' }}</div>
            </template>
            <template x-if="editing">
              <div>
                <input type="text" name="date"
                  value="{{ old('date', optional($screening->screening_date)->format('m/d/Y')) }}"
                  class="form-input form-input-light flatpickr-date"
                  placeholder="MM/DD/YYYY" autocomplete="off"/>
                @error('date')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- Measurements grid --}}
          <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:0.75rem;">

            {{-- Blood Pressure --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Blood Pressure</label>
              <template x-if="!editing">
                <div class="profile-value">{{ $screening->blood_pressure ?? '—' }}</div>
              </template>
              <template x-if="editing">
                <div>
                  <input type="text" name="blood_pressure"
                    value="{{ old('blood_pressure', $screening->blood_pressure) }}"
                    class="form-input form-input-light" placeholder="e.g. 120/80"/>
                  @error('blood_pressure')<div class="form-error">{{ $message }}</div>@enderror
                </div>
              </template>
            </div>

            {{-- Hemoglobin --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Hemoglobin (g/dL)</label>
              <template x-if="!editing">
                <div class="profile-value">{{ $screening->hemoglobin_level ? $screening->hemoglobin_level . ' g/dL' : '—' }}</div>
              </template>
              <template x-if="editing">
                <div>
                  <input type="number" name="hemoglobin_level"
                    value="{{ old('hemoglobin_level', $screening->hemoglobin_level) }}"
                    step="0.1" min="0" max="30"
                    class="form-input form-input-light" placeholder="e.g. 14.5"/>
                  @error('hemoglobin_level')<div class="form-error">{{ $message }}</div>@enderror
                </div>
              </template>
            </div>

            {{-- Weight --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Weight (kg)</label>
              <template x-if="!editing">
                <div class="profile-value">{{ $screening->weight ? $screening->weight . ' kg' : '—' }}</div>
              </template>
              <template x-if="editing">
                <div>
                  <input type="number" name="weight"
                    value="{{ old('weight', $screening->weight) }}"
                    step="0.1" min="30" max="300"
                    class="form-input form-input-light" placeholder="e.g. 65.5"/>
                  @error('weight')<div class="form-error">{{ $message }}</div>@enderror
                </div>
              </template>
            </div>

          </div>

          {{-- Static fields --}}
          <div style="display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <span style="font-size:0.82rem; color:#888;">Screened By</span>
            <span style="font-size:0.85rem; color:#1a1a2e; font-weight:600;">{{ $screening->staff->name ?? '—' }}</span>
          </div>
          <div style="display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <span style="font-size:0.82rem; color:#888;">Linked Donation</span>
            <span style="font-size:0.85rem; color:#1a1a2e; font-weight:600;">{{ optional($screening->donation?->donation_date)->format('F d, Y') ?? 'No donation recorded' }}</span>
          </div>

          {{-- Remarks --}}
          <div class="form-group" style="margin-top:0.75rem; margin-bottom:0;">
            <label class="form-label">Remarks</label>
            <template x-if="!editing">
              <div>
                @if($screening->remarks)
                  <div style="padding:0.85rem; background:#fafbff; border-radius:10px; border:1px solid var(--border-light);">
                    <p style="font-size:0.875rem; color:#555; line-height:1.6; margin:0;">{{ $screening->remarks }}</p>
                  </div>
                @else
                  <div class="profile-value" style="color:#bbb; font-style:italic;">No remarks</div>
                @endif
              </div>
            </template>
            <template x-if="editing">
              <div>
                <textarea name="remarks" rows="3"
                  class="form-input form-input-light"
                  placeholder="Additional notes...">{{ old('remarks', $screening->remarks) }}</textarea>
                @error('remarks')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- Save button --}}
          <div x-show="editing" x-cloak style="margin-top:1rem; text-align:right;">
            <button type="submit" class="btn btn-dash-primary">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
              Save Changes
            </button>
          </div>

        </form>
      </div>
    </div>

    {{-- Donor info --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Donor</h3>
        <a href="{{ route($routePrefix . '.donors.show', $screening->donor) }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View profile →</a>
      </div>
      <div class="dash-card-body">
        <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem;">
          <div class="avatar-initials" style="width:48px; height:48px; font-size:1rem; overflow:hidden; border:2px solid #fff; outline:2px solid rgba(192,57,43,0.15); box-shadow:0 2px 8px rgba(192,57,43,0.25);">
    @if($screening->donor->avatar)
        <img src="{{ asset($screening->donor->avatar) }}" alt="{{ $screening->donor->name }}" style="width:100%; height:100%; object-fit:cover;"/>
    @else
        {{ strtoupper(substr($screening->donor->name, 0, 1)) }}
    @endif
</div>
          <div>
            <div style="font-weight:700; font-size:1rem; color:#1a1a2e;">{{ $screening->donor->name }}</div>
            <div style="display:flex; align-items:center; gap:0.5rem; margin-top:0.25rem;">
              @if($screening->donor->bloodType)<x-blood-type-badge :type="$screening->donor->bloodType->type_name"/>@endif
              <span style="font-size:0.78rem; color:#888;">Age {{ $screening->donor->date_of_birth->age }}</span>
            </div>
          </div>
        </div>
        @foreach([
          ['Contact',          $screening->donor->contact_number ?? '—'],
          ['Email',            $screening->donor->user->email],
          ['Total Screenings', $screening->donor->screenings->count()],
          ['Total Donations',  $screening->donor->donations->count()],
        ] as [$l, $v])
          <div style="display:flex; justify-content:space-between; padding:0.6rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <span style="font-size:0.82rem; color:#888;">{{ $l }}</span>
            <span style="font-size:0.85rem; color:#1a1a2e; font-weight:500;">{{ $v }}</span>
          </div>
        @endforeach
      </div>
    </div>

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
    @media (max-width: 768px) {
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-resolution: 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr 1fr !important; }
    }
  </style>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', () => {
      const dateEl = document.querySelector('.flatpickr-date');
      if (dateEl && !dateEl._flatpickr && typeof flatpickr !== 'undefined') {
        flatpickr(dateEl, {
          dateFormat:    'm/d/Y',
          maxDate:       'today',
          disableMobile: true,
          allowInput:    false,
        });
      }
    });
  });
</script>
@endpush

</x-app-layout>
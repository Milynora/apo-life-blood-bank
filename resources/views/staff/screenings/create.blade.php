<x-app-layout title="Record Screening">

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
        <span>Record New</span>
      </div>
      <h1 class="page-title">Record Screening</h1>
      <p class="page-subtitle">Enter health measurements. The system will automatically determine eligibility.</p>
    </div>
    <a href="{{ route($routePrefix . '.screenings.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1.3fr 1fr; gap:1.75rem; align-items:start;">

    {{-- LEFT: Form --}}
    <form method="POST" action="{{ route($routePrefix . '.screenings.store') }}"
      style="display:flex; flex-direction:column; gap:1.5rem;">
      @csrf

      {{-- Donor + Appointment --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Donor & Appointment</h3>
        </div>
        <div class="dash-card-body">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

            {{-- Donor select --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Select Donor <span style="color:#E74C3C;">*</span></label>
              <select id="donor_select" name="donor_id" required class="form-input form-input-light"
                onchange="updateAppointment(this.value)">
                <option value="">Select Donor</option>
@foreach($donors->where('screening_eligible', true)->sortBy('name') as $d)
  <option value="{{ $d->donor_id }}"
    {{ (old('donor_id', $selectedDonor?->donor_id) == $d->donor_id) ? 'selected' : '' }}>
    {{ $d->name }}
  </option>
@endforeach
              </select>
              @error('donor_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Linked Appointment --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Linked Appointment</label>
              <input type="hidden" name="appointment_id" id="appointment_id_input"
                value="{{ old('appointment_id', $selectedAppointment?->appointment_id) }}"/>
              <div id="appointment_display"
                style="font-size:0.875rem; padding:0.7rem 0.9rem; background:#f8f8f8; border:1.5px solid var(--border-light); border-radius:10px; min-height:42px; display:flex; align-items:center; gap:0.5rem; color:#888;">
                @if($selectedAppointment)
                  <svg width="13" height="13" fill="none" stroke="#27AE60" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                  <span style="color:#1a1a2e; font-weight:500;">{{ $selectedAppointment->appointment_date->format('M d, Y h:i A') }}</span>
                @else
                  <span style="font-style:italic;">Walk-in / No appointment</span>
                @endif
              </div>
            </div>

            {{-- Screening Date --}}
            <div class="form-group" style="margin-bottom:0; grid-column:span 2;">
              <label class="form-label">Screening Date <span style="color:#E74C3C;">*</span></label>
              <input type="text" name="date"
                value="{{ old('date', today()->format('m/d/Y')) }}"
                class="form-input form-input-light flatpickr-date"
                placeholder="MM/DD/YYYY" autocomplete="off"/>
              @error('date')<div class="form-error">{{ $message }}</div>@enderror
            </div>

          </div>
        </div>
      </div>

      {{-- Health Measurements --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Health Measurements</h3>
          <div style="display:flex; align-items:center; gap:0.4rem; font-size:0.75rem; color:#27AE60; font-weight:600;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            System auto-evaluates eligibility
          </div>
        </div>
        <div class="dash-card-body">
          <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem;">

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Blood Pressure</label>
              <input type="text" name="blood_pressure"
                value="{{ old('blood_pressure') }}"
                class="form-input form-input-light" placeholder="e.g. 120/80"/>
              <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Normal: 90–160 / 60–100 mmHg</div>
              @error('blood_pressure')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Hemoglobin (g/dL)</label>
              <input type="number" name="hemoglobin_level"
                value="{{ old('hemoglobin_level') }}"
                step="0.1" min="0" max="30"
                class="form-input form-input-light" placeholder="e.g. 14.5"/>
              <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Minimum: 12.5 g/dL</div>
              @error('hemoglobin_level')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Weight (kg)</label>
              <input type="number" name="weight"
                value="{{ old('weight') }}"
                step="0.1" min="30" max="300"
                class="form-input form-input-light" placeholder="e.g. 65.5"/>
              <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Minimum: 50 kg</div>
              @error('weight')<div class="form-error">{{ $message }}</div>@enderror
            </div>

          </div>

        </div>
      </div>

      {{-- Remarks --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Remarks</h3>
          <span style="font-size:0.75rem; color:#bbb; font-weight:400;">Optional</span>
        </div>
        <div class="dash-card-body">
          <textarea name="remarks" rows="3"
            class="form-input form-input-light"
            placeholder="Additional notes about the donor's condition, observations, etc.">{{ old('remarks') }}</textarea>
          @error('remarks')<div class="form-error">{{ $message }}</div>@enderror
        </div>
      </div>

      {{-- Actions --}}
      <div style="display:flex; gap:0.85rem; justify-content:flex-end;">
        <a href="{{ route($routePrefix . '.screenings.index') }}"
          style="padding:0.7rem 1.4rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:inline-flex; align-items:center;">
          Cancel
        </a>
        <button type="submit" class="btn btn-dash-primary" style="border-radius:10px; padding:0.7rem 1.75rem;">
          <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
          Save Screening
        </button>
      </div>

    </form>

    {{-- RIGHT: Eligibility criteria --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

      <div class="dash-card">
        <div class="dash-card-header"><h3 class="dash-card-title">Eligibility Criteria</h3></div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">

          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

  {{-- Must pass --}}
  <div>
    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#27AE60; margin-bottom:0.65rem;">✓ Must pass all</div>
    <div style="display:flex; flex-direction:column; gap:0.45rem;">
      @foreach([
        'Hemoglobin ≥ 12.5 g/dL',
        'Weight ≥ 50 kg',
        'Systolic BP 90–160 mmHg',
        'Diastolic BP 60–100 mmHg',
        'Last donation ≥ 56 days ago',
      ] as $item)
        <div style="display:flex; align-items:center; gap:0.6rem; font-size:0.8rem; color:#555;">
          <svg width="12" height="12" fill="none" stroke="#27AE60" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
          {{ $item }}
        </div>
      @endforeach
    </div>
  </div>

  {{-- Results in deferral --}}
  <div>
    <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#E74C3C; margin-bottom:0.65rem;">✕ Results in deferral</div>
    <div style="display:flex; flex-direction:column; gap:0.45rem;">
      @foreach([
        'Hemoglobin below 12.5 g/dL',
        'Weight below 50 kg',
        'Blood pressure out of range',
        'Donated within the last 56 days',
      ] as $item)
        <div style="display:flex; align-items:center; gap:0.6rem; font-size:0.8rem; color:#555;">
          <svg width="12" height="12" fill="none" stroke="#E74C3C" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
          {{ $item }}
        </div>
      @endforeach
    </div>
  </div>

</div>

        </div>
      </div>

      {{-- Ineligible donors notice --}}
      @php $ineligibleDonors = $donors->where('screening_eligible', false); @endphp
      @if($ineligibleDonors->count())
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title" style="color:#E67E22;">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; margin-right:0.3rem;"><path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              Not Yet Eligible ({{ $ineligibleDonors->count() }})
            </h3>
          </div>
          <div class="dash-card-body" style="padding:0; max-height:260px; overflow-y:auto;">
            @foreach($ineligibleDonors as $d)
              <div style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 1.25rem; border-bottom:1px solid rgba(0,0,0,0.04);">
                <div>
                  <div style="font-size:0.82rem; font-weight:600; color:#1a1a2e;">{{ $d->name }}</div>
                  <div style="font-size:0.72rem; color:#aaa;">{{ $d->days_since_donation }} day(s) since last donation</div>
                </div>
                <span style="font-size:0.72rem; font-weight:600; color:#E67E22; background:rgba(230,126,34,0.1); border:1px solid rgba(230,126,34,0.25); border-radius:6px; padding:0.2rem 0.55rem; white-space:nowrap;">
                  {{ $d->days_until_eligible }} day(s) left
                </span>
              </div>
            @endforeach
          </div>
        </div>
      @endif

    </div>

  </div>

  @push('scripts')
  <script>
    const appointmentMap = {
      @foreach($appointments as $a)
        "{{ $a->donor_id }}": {
          id:   "{{ $a->appointment_id }}",
          date: "{{ $a->appointment_date->format('M d, Y h:i A') }}"
        },
      @endforeach
    };

    function updateAppointment(donorId) {
      const input   = document.getElementById('appointment_id_input');
      const display = document.getElementById('appointment_display');

      if (appointmentMap[donorId]) {
        const appt = appointmentMap[donorId];
        input.value = appt.id;
        display.innerHTML = `
          <svg width="13" height="13" fill="none" stroke="#27AE60" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
          </svg>
          <span style="color:#1a1a2e; font-weight:500;">${appt.date}</span>`;
      } else {
        input.value = '';
        display.innerHTML = `<span style="font-style:italic; color:#aaa;">Walk-in / No appointment</span>`;
      }
    }
  </script>
  @endpush

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1.3fr 1fr"] { grid-template-columns: 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr 1fr !important; }
    }
  </style>

</x-app-layout>
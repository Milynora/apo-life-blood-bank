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
  x-data="{ submitDisabled: false }"
  @disable-submit.window="submitDisabled = $event.detail"
  style="display:flex; flex-direction:column; gap:1.5rem;">
      @csrf

      {{-- ONE BIG CARD --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Screening Details</h3>
          <div style="display:flex; align-items:center; gap:0.4rem; font-size:0.75rem; color:#27AE60; font-weight:600;">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            System auto-evaluates eligibility
          </div>
        </div>
        <div class="dash-card-body" style="display:flex; flex-direction:column; gap:1.5rem;">

          {{-- Section: Donor & Appointment --}}
          <div>
            <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#aaa; margin-bottom:1rem;">Donor & Appointment</div>
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

              {{-- Donor select --}}
              <div class="form-group" style="margin-bottom:0; position:relative;"
                x-data="{
                  open: false,
                  search: '',
                  selectedId: '{{ old('donor_id', $selectedDonor?->donor_id ?? '') }}',
                  selectedName: '{{ $selectedDonor ? $selectedDonor->user->name : '' }}',
                  warning: '',
                  donors: {{ Js::from($donors
    ->filter(fn($d) => $d->sort_group < 99)
    ->sortBy(fn($d) => [$d->sort_group, $d->user->name])
    ->values()
    ->map(fn($d) => [
        'id'             => $d->donor_id,
        'name'           => $d->user->name,
        'eligible'       => $d->screening_eligible,
        'days_since'     => $d->days_since_donation,
        'days_left'      => $d->days_until_eligible,
        'screened_today' => $d->screened_today_unfit,
    ])) }},
                  get filtered() {
                    if (!this.search) return this.donors;
                    return this.donors.filter(d => d.name.toLowerCase().includes(this.search.toLowerCase()));
                  },
                  select(donor) {
  this.selectedId   = donor.id;
  this.selectedName = donor.name;
  this.search       = donor.name;
  this.open         = false;
  updateAppointment(donor.id);

  const donorError = document.getElementById('error-donor_id');
  if (donorError) donorError.style.display = 'none';

  if (!donor.eligible && donor.days_left > 0) {
    this.warning = 'Donor is not yet eligible.';
    $dispatch('disable-submit', true);
  } else {
    this.warning = '';
    $dispatch('disable-submit', false);
  }
}
                }"
                @click.outside="open = false"
                x-init="search = selectedName">

                <label class="form-label">Select Donor <span style="color:#E74C3C;">*</span></label>
                <input type="hidden" name="donor_id" :value="selectedId" required/>

                <input type="text" x-model="search"
                  @focus="open = true"
                  @input="open = true; selectedId = ''; if (search === '') { selectedName = ''; warning = ''; $dispatch('disable-submit', false); clearAppointment(); }"
                  @keydown.escape="open = false"
                  @blur="if (!selectedId) { search = ''; selectedName = ''; warning = ''; $dispatch('disable-submit', false); clearAppointment(); }"
                  placeholder="Type to search donor…"
                  class="form-input form-input-light"
                  autocomplete="off"/>

                {{-- Dropdown --}}
                <div x-show="open && filtered.length > 0" x-transition
                  style="position:absolute; z-index:50; width:100%; background:#fff; border:1.5px solid var(--border-light); border-radius:12px; margin-top:4px; box-shadow:0 8px 24px rgba(0,0,0,0.1); overflow:hidden;">
                  <div style="max-height:220px; overflow-y:auto;">
                    <template x-for="donor in filtered" :key="donor.id">
  <div @mousedown.prevent="select(donor)"
    style="padding:0.65rem 1rem; cursor:pointer; font-size:0.875rem; transition:background 0.15s;"
    onmouseover="this.style.background='rgba(0,0,0,0.04)'"
    onmouseout="this.style.background='transparent'">
    <div style="display:flex; justify-content:space-between; align-items:center;">
      <span x-text="donor.name" style="color:#1a1a2e;"></span>
      <span x-show="donor.screened_today"
        style="font-size:0.7rem; font-weight:600; color:#2980B9; background:rgba(41,128,185,0.1); border:1px solid rgba(41,128,185,0.25); border-radius:6px; padding:0.15rem 0.45rem; margin-left:0.5rem;">
        Rescreening
      </span>
    </div>
  </div>
</template>
                  </div>
                </div>

                {{-- Inline warning --}}
                <div x-show="warning" x-transition
  class="form-error"
  x-text="warning">
</div>

                @error('donor_id')
    <div class="form-error" id="error-donor_id">{{ $message }}</div>
@enderror
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
                @error('date')
    <div class="form-error" id="error-date">{{ $message }}</div>
@enderror
              </div>

            </div>
          </div>

          {{-- Section: Health Measurements --}}
          <div>
            <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#aaa; margin-bottom:1rem;">Health Measurements</div>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1.25rem;">

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Blood Pressure <span style="color:#E74C3C;">*</span></label>
                <input type="text" name="blood_pressure"
                  value="{{ old('blood_pressure') }}"
                  class="form-input form-input-light" placeholder="e.g. 120/80"/>
                <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Normal: 90–160 / 60–100 mmHg</div>
                @error('blood_pressure')
    <div class="form-error" id="error-blood_pressure">{{ $message }}</div>
@enderror
              </div>

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Hemoglobin (g/dL) <span style="color:#E74C3C;">*</span></label>
                <input type="number" name="hemoglobin_level"
                  value="{{ old('hemoglobin_level') }}"
                  step="0.1" min="0" max="30"
                  class="form-input form-input-light" placeholder="e.g. 14.5"/>
                <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Minimum: 12.5 g/dL</div>
                @error('hemoglobin_level')
    <div class="form-error" id="error-hemoglobin_level">{{ $message }}</div>
@enderror
              </div>

              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Weight (kg) <span style="color:#E74C3C;">*</span></label>
                <input type="number" name="weight"
                  value="{{ old('weight') }}"
                  step="0.1" min="30" max="300"
                  class="form-input form-input-light" placeholder="e.g. 65.5"/>
                <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Minimum: 50 kg</div>
                @error('weight')
    <div class="form-error" id="error-weight">{{ $message }}</div>
@enderror
              </div>

            </div>
          </div>

          {{-- Section: Remarks --}}
          <div>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
              <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#aaa;">Remarks</div>
              <span style="font-size:0.75rem; color:#bbb; font-weight:400;">Optional</span>
            </div>
            <textarea name="remarks" rows="3"
              class="form-input form-input-light"
              placeholder="Additional notes about the donor's condition, observations, etc.">{{ old('remarks') }}</textarea>
            @error('remarks')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          {{-- Actions --}}
          <div style="display:flex; gap:0.85rem; justify-content:flex-end;">
            <a href="{{ route($routePrefix . '.screenings.index') }}"
              style="padding:0.7rem 1.4rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:inline-flex; align-items:center;">
              Cancel
            </a>
            <button type="submit" class="btn btn-dash-primary"
              style="border-radius:10px; padding:0.7rem 1.75rem;"
              :disabled="submitDisabled"
              :style="submitDisabled ? 'opacity:0.5; cursor:not-allowed;' : ''">
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Save Screening
            </button>
          </div>

        </div>
      </div>

    </form>

    {{-- RIGHT: Info cards --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

      {{-- Eligibility Criteria --}}
      <div class="dash-card">
        <div class="dash-card-header"><h3 class="dash-card-title">Eligibility Criteria</h3></div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
          <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">
            <div>
              <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#27AE60; margin-bottom:0.65rem;">✓ Must pass all</div>
              <div style="display:flex; flex-direction:column; gap:0.45rem;">
                @foreach(['Hemoglobin ≥ 12.5 g/dL','Weight ≥ 50 kg','Systolic BP 90–160 mmHg','Diastolic BP 60–100 mmHg','Last donation ≥ 56 days ago'] as $item)
                  <div style="display:flex; align-items:center; gap:0.6rem; font-size:0.8rem; color:#555;">
                    <svg width="12" height="12" fill="none" stroke="#27AE60" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                    {{ $item }}
                  </div>
                @endforeach
              </div>
            </div>
            <div>
              <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#E74C3C; margin-bottom:0.65rem;">✕ Results in deferral</div>
              <div style="display:flex; flex-direction:column; gap:0.45rem;">
                @foreach(['Hemoglobin below 12.5 g/dL','Weight below 50 kg','Blood pressure out of range','Donated within the last 56 days'] as $item)
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

      {{-- Screened Today card --}}
      @if($screenedTodayDonors->count())
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title" style="color:#27AE60;">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; margin-right:0.3rem;"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
              Screened Today ({{ $screenedTodayDonors->count() }})
            </h3>
          </div>
          <div class="dash-card-body" style="padding:0; max-height:121px; overflow-y:auto;">
            @foreach($screenedTodayDonors as $s)
              <div style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 1.25rem; border-bottom:1px solid rgba(0,0,0,0.04);">
                <div>
                  <div style="font-size:0.82rem; font-weight:600; color:#1a1a2e;">{{ $s->donor->user->name }}</div>
                  <div style="font-size:0.72rem; color:#aaa;">{{ \Carbon\Carbon::parse($s->screening_date)->format('h:i A') }}</div>
                </div>
                <span style="font-size:0.72rem; font-weight:600; color:#27AE60; background:rgba(39,174,96,0.1); border:1px solid rgba(39,174,96,0.25); border-radius:6px; padding:0.2rem 0.55rem;">
                  Passed
                </span>
              </div>
            @endforeach
          </div>
        </div>
      @endif

      {{-- Not Yet Eligible card --}}
      @php $ineligibleDonors = $donors->where('screening_eligible', false); @endphp
      @if($ineligibleDonors->count())
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title" style="color:#E67E22;">
              <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:inline; margin-right:0.3rem;"><path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
              Not Yet Eligible ({{ $ineligibleDonors->count() }})
            </h3>
          </div>
          <div class="dash-card-body" style="padding:0; max-height:121px; overflow-y:auto;">
            @foreach($ineligibleDonors as $d)
              <div style="display:flex; justify-content:space-between; align-items:center; padding:0.65rem 1.25rem; border-bottom:1px solid rgba(0,0,0,0.04);">
                <div>
                  <div style="font-size:0.82rem; font-weight:600; color:#1a1a2e;">{{ $d->user->name }}</div>
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

    document.addEventListener('DOMContentLoaded', function () {
    ['input', 'change', 'keyup'].forEach(function(eventType) {
        document.addEventListener(eventType, function(e) {
            const name = e.target.getAttribute('name');
            if (!name) return;
            const error = document.getElementById('error-' + name);
            if (error) error.style.display = 'none';
        });
    });
});

function clearAppointment() {
  const input   = document.getElementById('appointment_id_input');
  const display = document.getElementById('appointment_display');
  if (input)   input.value = '';
  if (display) display.innerHTML = `<span style="font-style:italic; color:#aaa;">Walk-in / No appointment</span>`;
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
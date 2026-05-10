<x-app-layout title="Make Appointment">

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
        <a href="{{ route($routePrefix . '.appointments.index') }}">Appointments</a>
        <span class="breadcrumb-sep">›</span>
        <span>New</span>
      </div>
      <h1 class="page-title">Make Appointment</h1>
      <p class="page-subtitle">Schedule a donation appointment for a registered donor.</p>
    </div>
    <a href="{{ route($routePrefix . '.appointments.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1.3fr 1fr; gap:1.75rem; align-items:start;">

    {{-- Form --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Appointment Details</h3>
      </div>
      <div class="dash-card-body">
        <form method="POST" action="{{ route($routePrefix . '.appointments.store') }}"
          x-data="{ selectedDonorId: '{{ old('donor_id', request('donor_id', '')) }}' }">
          @csrf

          {{-- Donor select --}}
<div class="form-group" style="position:relative;"
  x-data="{
    open: false,
    search: '',
    selectedId: '{{ old('donor_id', request('donor_id', '')) }}',
    selectedName: '{{ $selectedDonor ? $selectedDonor->user->name : '' }}',
    donors: {{ Js::from($donors->map(fn($d) => ['id' => $d->donor_id, 'name' => $d->user->name, 'blood_type' => $d->bloodType?->type_name ?? 'N/A'])) }},
    get filtered() {
      if (!this.search) return this.donors;
      return this.donors.filter(d => d.name.toLowerCase().includes(this.search.toLowerCase()));
    },
    select(donor) {
  this.selectedId   = donor.id;
  this.selectedName = donor.name;
  this.search       = donor.name;
  this.open         = false;
}
  }"
  @click.outside="open = false"
  x-init="search = selectedName">

  <label class="form-label">Select Donor <span style="color:#E74C3C;">*</span></label>

  <input type="hidden" name="donor_id" :value="selectedId" required/>

  <input type="text" x-model="search"
  @focus="open = true"
  @input="open = true; selectedId = ''; if (search === '') { selectedName = ''; }"
  @keydown.escape="open = false"
  @blur="if (!selectedId) { search = ''; selectedName = ''; }"
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
    <span x-text="donor.name" style="color:#1a1a2e;"></span>
  </div>
</template>
    </div>
  </div>

  @error('donor_id')<div class="form-error">{{ $message }}</div>@enderror
</div>

          {{-- Date & time --}}
          <div class="form-group">
            <label class="form-label">Date & Time <span style="color:#E74C3C;">*</span></label>
            <div style="position:relative;">
              <div style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); color:#aaa; pointer-events:none; z-index:1;">
              </div>
              <input type="text" name="appointment_date"
                value="{{ old('appointment_date') }}"
                class="form-input form-input-light appt-picker"
                style="padding-left:2.75rem;"
                placeholder="Select date and time" readonly/>
            </div>
            <div style="font-size:0.72rem; color:#aaa; margin-top:0.35rem;">Mon–Sat · 7:00 AM – 4:00 PM</div>
            @error('appointment_date')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          {{-- Notes --}}
          <div class="form-group">
            <label class="form-label">
              Notes
              <span style="font-weight:400; color:#bbb;">(optional)</span>
            </label>
            <textarea name="notes" rows="2"
              class="form-input form-input-light"
              placeholder="Special instructions or notes for this appointment…">{{ old('notes') }}</textarea>
            @error('notes')<div class="form-error">{{ $message }}</div>@enderror
          </div>

          {{-- Info note --}}
          <div class="alert alert-info" style="margin-bottom:1.25rem;">
  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
  <span style="font-size:0.82rem;">Inform the donor of their appointment and remind them of the eligibility requirements.</span>
</div>

          {{-- Buttons --}}
<div style="display:flex; gap:0.85rem; justify-content:flex-end;">
  <a href="{{ route($routePrefix . '.appointments.index') }}"
    style="padding:0.7rem 1.4rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:inline-flex; align-items:center;">
    Cancel
  </a>
  <button type="submit" class="btn btn-dash-primary" style="border-radius:10px; padding:0.7rem 1.75rem;">
    <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    Confirm Appointment
  </button>
</div>

        </form>
      </div>
    </div>

    {{-- Right info panel --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

      {{-- What happens --}}
      <div class="dash-card">
        <div class="dash-card-header"><h3 class="dash-card-title">What Happens Next</h3></div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
          <div style="display:flex; flex-direction:column; gap:0;">
            @foreach([
              ['Appointment created', 'Status is automatically set to Approved.',   '#27AE60'],
              ['Donor notified',      'An in-system notification is sent to the donor.', '#2980B9'],
              ['On appointment day',  'Staff records pre-donation screening.',       '#F39C12'],
              ['After screening',     'If eligible, donation is recorded.',          '#C0392B'],
            ] as $i => [$title, $desc, $color])
              <div style="display:flex; gap:1rem; padding-bottom:{{ $i < 3 ? '1rem' : '0' }}; position:relative;">
                @if($i < 3)
                  <div style="position:absolute; left:13px; top:26px; bottom:0; width:2px; background:{{ $color }}20;"></div>
                @endif
                <div style="width:26px; height:26px; border-radius:50%; background:{{ $color }}15; border:2px solid {{ $color }}45; display:flex; align-items:center; justify-content:center; flex-shrink:0; z-index:1; font-size:0.65rem; font-weight:800; color:{{ $color }}; font-family:var(--font-mono);">
                  {{ $i + 1 }}
                </div>
                <div style="padding-top:3px;">
                  <div style="font-size:0.84rem; font-weight:700; color:#1a1a2e; margin-bottom:0.15rem;">{{ $title }}</div>
                  <div style="font-size:0.77rem; color:#888; line-height:1.5;">{{ $desc }}</div>
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Eligibility reminder --}}
      <div class="dash-card">
        <div class="dash-card-header"><h3 class="dash-card-title">Donor Eligibility Reminder</h3></div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
          <div style="display:flex; flex-direction:column; gap:0.35rem;">
            @foreach([
              'Age 18–65 years old',
              'Weight at least 50 kg',
              'No donation in the past 56 days',
              'No active illness or fever',
            ] as $item)
              <div style="display:flex; align-items:center; gap:0.65rem; font-size:0.82rem; color:#555;">
                <svg width="13" height="13" fill="none" stroke="#27AE60" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                {{ $item }}
              </div>
            @endforeach
          </div>
        </div>
      </div>

    </div>

  </div>

  @push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    flatpickr('.appt-picker', {
      enableTime:      true,
      dateFormat:      'Y-m-d H:i',
      altInput:        true,
      altFormat:       'F j, Y h:i K',
      minDate:         'today',
      maxDate:         new Date(new Date().setMonth(new Date().getMonth() + 3)),
      disable:         [date => date.getDay() === 0], // disable Sundays
      minTime:         '07:00',
      maxTime:         '16:00',
      defaultHour:     8,
      defaultMinute:   0,
      minuteIncrement: 30,
      allowInput:      false,
      disableMobile:   true,
    });
  });
</script>
@endpush

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1.3fr 1fr"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
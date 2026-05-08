<x-app-layout title="Schedule Appointment">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('donor.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('donor.appointments.index') }}">Appointments</a>
        <span class="breadcrumb-sep">›</span>
        <span>Request New</span>
      </div>
      <h1 class="page-title">Request an Appointment</h1>
      <p class="page-subtitle">Choose your preferred date and time. Your request will be reviewed for approval.</p>
    </div>
    <a href="{{ route('donor.appointments.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1.2fr 1fr; gap:1.5rem; align-items:start;">

    {{-- ── LEFT ─────────────────────────────────────────────── --}}
    <div>
      <form method="POST" action="{{ route('donor.appointments.store') }}">
        @csrf

        {{-- Section 1: Donor Info --}}
<div class="dash-card" style="margin-bottom:1.25rem;">
  <div class="dash-card-header">
    <div style="display:flex; align-items:center; gap:0.6rem;">
      <div style="width:22px; height:22px; border-radius:50%; background:#C0392B; color:#fff; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">1</div>
      <h3 class="dash-card-title">Donor Information</h3>
    </div>
  </div>

  <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
    
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
      <div>
        <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Full Name</div>
        <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $donor->name }}</div>
      </div>

      <div>
        <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Blood Type</div>
        <div style="font-size:0.875rem; color:#C0392B; font-weight:700; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $donor->bloodType->type_name ?? 'Unknown' }}</div>
      </div>
    </div>

    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">
      <div>
        <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Date of Birth</div>
        <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $donor->date_of_birth ? $donor->date_of_birth->format('M d, Y') : '—' }}</div>
      </div>

      <div>
        <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Gender</div>
        <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $donor->gender ?? '—' }}</div>
      </div>
    </div>
        
    {{-- Row 3: Gender + Address --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem;">
      <div>
        <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Contact Number</div>
        <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $donor->contact_number ?? '—' }}</div>
      </div>

      <div>
        <div style="font-size:0.7rem; font-weight:600; color:#aaa; text-transform:uppercase; letter-spacing:0.05em; margin-bottom:4px;">Address</div>
        <div style="font-size:0.875rem; color:#1a1a2e; font-weight:500; background:#f8f8f8; border-radius:8px; padding:0.6rem 0.85rem; border:1px solid rgba(0,0,0,0.06);">{{ $donor->address ?? '—' }}</div>
      </div>
    </div>

  </div>
</div>

        {{-- Section 2: Appointment Details --}}
        <div class="dash-card">
          <div class="dash-card-header">
            <div style="display:flex; align-items:center; gap:0.6rem;">
              <div style="width:22px; height:22px; border-radius:50%; background:#C0392B; color:#fff; font-size:11px; font-weight:700; display:flex; align-items:center; justify-content:center; flex-shrink:0;">2</div>
              <h3 class="dash-card-title">Appointment Details</h3>
            </div>
          </div>
          <div class="dash-card-body" style="padding:1.25rem 1.5rem;">

            {{-- Date & Time --}}
            <div class="form-group" style="margin-bottom:1.25rem;">
              <label class="form-label" for="appointment_date">
                Date & Time <span style="color:#E74C3C;">*</span>
              </label>
              <div style="position:relative;">
                <div style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); color:#aaa; pointer-events:none; z-index:1;">
                  <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <input id="appointment_date" type="text" name="appointment_date"
                  value="{{ old('appointment_date') }}"
                  class="form-input form-input-light appt-picker"
                  style="padding-left:2.75rem;"
                  placeholder="Select date and time"
                  readonly/>
              </div>
              <div style="font-size:0.72rem; color:#aaa; margin-top:0.35rem; display:flex; align-items:center; gap:0.35rem;">
                <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Mon–Sat only · 7:00 AM – 4:00 PM · 30-minute intervals
              </div>
              @error('appointment_date')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>

            {{-- Notes --}}
            <div class="form-group" style="margin-bottom:1.25rem;">
              <label class="form-label" for="notes">
                Notes <span style="font-weight:400; color:#bbb;">(optional)</span>
              </label>
              <textarea id="notes" name="notes" rows="3"
                class="form-input form-input-light"
                style="resize:none;"
                placeholder="Any special requests or health updates…">{{ old('notes') }}</textarea>
              @error('notes')
                <div class="form-error">{{ $message }}</div>
              @enderror
            </div>

            {{-- Buttons --}}
            <div style="display:flex; gap:0.75rem;">
              <a href="{{ route('donor.appointments.index') }}"
                style="flex:1; text-align:center; padding:0.7rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:flex; align-items:center; justify-content:center; transition:all 0.2s;">
                Cancel
              </a>
              <button type="submit" class="btn btn-dash-primary" style="flex:2; justify-content:center; border-radius:10px; padding:0.7rem;">
                <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Submit Request
              </button>
            </div>

          </div>
        </div>

      </form>
    </div>

    {{-- ── RIGHT ────────────────────────────────────────────── --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

      {{-- Please Bring --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Please Bring</h3>
        </div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
          @foreach([
            'Valid government-issued ID',
            'Comfortable clothing (loose sleeves)',
            'Eat a meal 2–3 hours before',
            'Stay well hydrated',
          ] as $item)
            <div style="display:flex; align-items:center; gap:0.75rem; padding:0.5rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
              <div style="width:18px; height:18px; border-radius:50%; background:rgba(39,174,96,0.1); border:1px solid rgba(39,174,96,0.3); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="8" height="8" fill="none" stroke="#27AE60" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
              </div>
              <span style="font-size:0.82rem; color:#555;">{{ $item }}</span>
            </div>
          @endforeach
        </div>
      </div>

      {{-- What to Expect --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">What to Expect</h3>
        </div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
          @foreach([
            ['#2980B9','rgba(41,128,185,0.1)',  'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                                                                                                                                        'Duration',  'About 45–60 minutes total process.'],
            ['#27AE60','rgba(39,174,96,0.1)',   'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',                                                    'Screening', 'Quick health check before donation.'],
            ['#C0392B','rgba(192,57,43,0.1)',   'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',                                                                                                                       'Donation',  'Blood draw takes 10–15 minutes.'],
            ['#F39C12','rgba(243,156,18,0.1)',  'M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z',                                                                                                                                          'Recovery',  'Light refreshments provided after.'],
          ] as [$stroke,$bg,$icon,$title,$desc])
            <div style="display:flex; gap:0.85rem; align-items:flex-start; padding:0.65rem 0; border-bottom:1px solid rgba(0,0,0,0.05);">
              <div style="width:32px; height:32px; border-radius:9px; background:{{ $bg }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
                <svg width="14" height="14" fill="none" stroke="{{ $stroke }}" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="{{ $icon }}"/></svg>
              </div>
              <div>
                <div style="font-size:0.85rem; font-weight:600; color:#1a1a2e; margin-bottom:2px;">{{ $title }}</div>
                <div style="font-size:0.78rem; color:#888; line-height:1.4;">{{ $desc }}</div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

    </div>

  </div>

  @push('scripts')
  <script>
    flatpickr('.appt-picker', {
      enableTime: true,
      dateFormat: 'm/d/Y h:i K',
      minDate: 'today',
      maxDate: new Date(new Date().setMonth(new Date().getMonth() + 3)),
      disable: [function(date) { return date.getDay() === 0; }],
      minTime: '07:00',
      maxTime: '16:00',
      defaultHour: 8,
      defaultMinute: 0,
      minuteIncrement: 30,
      allowInput: false,
      disableMobile: false,
    });
  </script>
  @endpush

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1.2fr 1fr"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
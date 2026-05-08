<x-app-layout title="Appointment Details">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('donor.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('donor.appointments.index') }}">Appointments</a>
        <span class="breadcrumb-sep">›</span>
        <span>Details</span>
      </div>
      <h1 class="page-title">Appointment Details</h1>
      <p class="page-subtitle">Full summary of your appointment and donor information.</p>
    </div>
    <a href="{{ route('donor.appointments.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  @php
    $donor = $appointment->donor;
    $dob   = $donor->date_of_birth;
    $age   = $dob ? $dob->age : null;

    $statusColors = [
      'pending'   => ['#F39C12', 'rgba(243,156,18,0.08)',  'rgba(243,156,18,0.25)'],
      'approved'  => ['#27AE60', 'rgba(39,174,96,0.08)',   'rgba(39,174,96,0.25)'],
      'rejected'  => ['#E74C3C', 'rgba(231,76,60,0.08)',   'rgba(231,76,60,0.25)'],
      'cancelled' => ['#95A5A6', 'rgba(149,165,166,0.08)', 'rgba(149,165,166,0.25)'],
      'completed' => ['#2980B9', 'rgba(41,128,185,0.08)',  'rgba(41,128,185,0.25)'],
      'no_show'   => ['#7F8C8D', 'rgba(127,140,141,0.08)', 'rgba(127,140,141,0.25)'],
    ];
    [$sColor, $sBg, $sBorder] = $statusColors[$appointment->status->value] ?? ['#888', 'rgba(0,0,0,0.04)', 'rgba(0,0,0,0.1)'];
  @endphp

  <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; align-items:start;">

    {{-- ── LEFT COLUMN ─────────────────────────────────────── --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

      {{-- Appointment status banner --}}
      <div style="background:{{ $sBg }}; border:1px solid {{ $sBorder }}; border-radius:var(--radius-lg); padding:1.25rem 1.5rem; display:flex; align-items:center; justify-content:space-between; gap:1rem; flex-wrap:wrap;">
        <div>
          <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:{{ $sColor }}; margin-bottom:0.4rem;">
            Appointment Status
          </div>
          <x-status-badge :status="$appointment->status->value"/>
        </div>
        <div style="text-align:right;">
          <div style="font-size:0.7rem; color:#aaa; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.3rem;">Scheduled for</div>
          <div style="font-weight:700; font-size:1.05rem; color:#1a1a2e;">
            {{ $appointment->appointment_date->format('F d, Y') }}
          </div>
          <div style="font-size:0.85rem; color:#666; margin-top:0.15rem;">
            {{ $appointment->appointment_date->format('l · h:i A') }}
          </div>
          <div style="font-size:0.78rem; color:#aaa; margin-top:0.15rem;">
            {{ $appointment->appointment_date->isPast() ? $appointment->appointment_date->diffForHumans() : 'in ' . now()->diffForHumans($appointment->appointment_date, true) }}
          </div>
        </div>
      </div>

      {{-- Appointment info --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Appointment Info</h3>
        </div>
        <div class="dash-card-body" style="padding:0;">
          @foreach([
            ['Appointment ID', '#' . str_pad($appointment->appointment_id, 5, '0', STR_PAD_LEFT)],
            ['Date',          $appointment->appointment_date->format('F d, Y')],
            ['Time',          $appointment->appointment_date->format('h:i A')],
            ['Day',           $appointment->appointment_date->format('l')],
            ['Submitted on',  $appointment->created_at->format('M d, Y h:i A')],
            ['Notes',         $appointment->notes ?: '—'],
          ] as $i => [$label, $value])
            <div style="display:grid; grid-template-columns:140px 1fr; border-bottom:1px solid var(--border-light); {{ $loop->last ? 'border-bottom:none;' : '' }}">
              <div style="padding:0.75rem 1rem 0.75rem 1.5rem; font-size:0.8rem; font-weight:600; color:#888; background:{{ $i % 2 === 0 ? '#fafafa' : '#fff' }};">
                {{ $label }}
              </div>
              <div style="padding:0.75rem 1.5rem 0.75rem 1rem; font-size:0.875rem; color:#1a1a2e; background:{{ $i % 2 === 0 ? '#fafafa' : '#fff' }};">
                {{ $value }}
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Screening results (if done) --}}
      @if($appointment->screening)
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">On-site Screening Results</h3>
            <x-status-badge :status="$appointment->screening->result ?? 'passed'" size="sm"/>
          </div>
          <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.65rem;">
              @foreach([
                ['Hemoglobin',     ($appointment->screening->hemoglobin_level ?? '—') . ' g/dL'],
['Blood Pressure', $appointment->screening->blood_pressure    ?? '—'],
['Weight',         ($appointment->screening->weight           ?? '—') . ' kg'],
              ] as [$label, $val])
                <div style="background:#f8f8f8; border-radius:8px; padding:0.65rem 0.9rem;">
                  <div style="font-size:0.68rem; color:#aaa; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.2rem;">{{ $label }}</div>
                  <div style="font-weight:700; font-size:0.95rem; color:#1a1a2e;">{{ $val }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif

      {{-- Donation (if recorded) --}}
      @if($appointment->donation)
        <div class="dash-card">
          <div class="dash-card-header">
            <h3 class="dash-card-title">Donation Recorded</h3>
            <span style="font-size:0.78rem; font-weight:600; color:#27AE60; background:rgba(39,174,96,0.1); border:1px solid rgba(39,174,96,0.25); border-radius:6px; padding:0.2rem 0.65rem;">
              Completed
            </span>
          </div>
          <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.65rem;">
              @foreach([
                ['Volume',      ($appointment->donation->volume        ?? '—') . ' mL'],
                ['Blood Type', $appointment->donation->donor->bloodType->type_name ?? '—'],
                ['Date',        $appointment->donation->donation_date ? \Carbon\Carbon::parse($appointment->donation->donation_date)->format('M d, Y') : '—'],
              ] as [$label, $val])
                <div style="background:#f8f8f8; border-radius:8px; padding:0.65rem 0.9rem;">
                  <div style="font-size:0.68rem; color:#aaa; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.2rem;">{{ $label }}</div>
                  <div style="font-weight:700; font-size:0.9rem; color:#1a1a2e;">{{ $val }}</div>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endif

    </div>{{-- end left column --}}

    {{-- ── RIGHT COLUMN ─────────────────────────────────────── --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">

      {{-- Donor info card --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Donor Information</h3>
        </div>
        <div class="dash-card-body" style="padding:0;">
          @foreach([
            ['Full Name',     $donor->user->name                                   ?? '—'],
            ['Email',         $donor->user->email                                  ?? '—'],
            ['Contact',       $donor->contact_number                               ?? '—'],
            ['Date of Birth', $dob ? $dob->format('F d, Y') . ' (Age ' . $age . ')' : '—'],
            ['Gender',        ucfirst($donor->gender                               ?? '—')],
            ['Blood Type',    $donor->bloodType->type_name                         ?? 'Unknown'],
            ['Address',       $donor->address                                      ?? '—'],
            ['Donor since',   $donor->user->created_at->format('F d, Y')           ?? '—'],
          ] as $i => [$label, $value])
            <div style="display:grid; grid-template-columns:130px 1fr; border-bottom:1px solid var(--border-light); {{ $loop->last ? 'border-bottom:none;' : '' }}">
              <div style="padding:0.75rem 0.75rem 0.75rem 1.5rem; font-size:0.8rem; font-weight:600; color:#888; background:{{ $i % 2 === 0 ? '#fafafa' : '#fff' }};">
                {{ $label }}
              </div>
              <div style="padding:0.75rem 1.5rem 0.75rem 0.75rem; font-size:0.875rem; color:#1a1a2e; background:{{ $i % 2 === 0 ? '#fafafa' : '#fff' }}; word-break:break-word;">
                {{ $value }}
              </div>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Next steps card --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">What Happens Next</h3>
        </div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
          @if($appointment->status->value === 'pending')
            <div style="display:flex; flex-direction:column; gap:0.85rem;">
              @foreach([
                ['#F39C12', 'Awaiting staff review',      'Your appointment has been submitted and is waiting for staff to review and approve it.'],
                ['#2980B9', 'You will be notified',       'Once approved or if there are any issues, you will receive a notification.'],
                ['#27AE60', 'Come on your scheduled day', 'Bring a valid ID, eat a meal beforehand, and stay hydrated.'],
              ] as [$color, $title, $desc])
                <div style="display:flex; gap:0.85rem; align-items:flex-start;">
                  <div style="width:10px; height:10px; border-radius:50%; background:{{ $color }}; flex-shrink:0; margin-top:4px;"></div>
                  <div>
                    <div style="font-size:0.85rem; font-weight:600; color:#1a1a2e; margin-bottom:0.2rem;">{{ $title }}</div>
                    <div style="font-size:0.78rem; color:#888; line-height:1.5;">{{ $desc }}</div>
                  </div>
                </div>
              @endforeach
            </div>
          @elseif($appointment->status->value === 'approved')
            <div style="display:flex; flex-direction:column; gap:0.85rem;">
              @foreach([
                ['#27AE60', 'Appointment approved!',      'Your appointment has been confirmed by staff. Please arrive on time.'],
                ['#2980B9', 'Bring your ID',              'A valid government-issued ID is required for verification on arrival.'],
                ['#F39C12', 'Eat and hydrate',            'Have a meal 2–3 hours before and drink plenty of water.'],
                ['#E74C3C', 'Wear comfortable clothing',  'Loose sleeves make the donation process easier.'],
              ] as [$color, $title, $desc])
                <div style="display:flex; gap:0.85rem; align-items:flex-start;">
                  <div style="width:10px; height:10px; border-radius:50%; background:{{ $color }}; flex-shrink:0; margin-top:4px;"></div>
                  <div>
                    <div style="font-size:0.85rem; font-weight:600; color:#1a1a2e; margin-bottom:0.2rem;">{{ $title }}</div>
                    <div style="font-size:0.78rem; color:#888; line-height:1.5;">{{ $desc }}</div>
                  </div>
                </div>
              @endforeach
            </div>
          @elseif($appointment->status->value === 'completed')
            <div style="text-align:center; padding:1rem 0;">
              <div style="width:52px; height:52px; border-radius:50%; background:rgba(39,174,96,0.1); border:2px solid rgba(39,174,96,0.3); display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem;">
                <svg width="22" height="22" fill="none" stroke="#27AE60" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
              </div>
              <div style="font-weight:700; font-size:1rem; color:#1a1a2e; margin-bottom:0.4rem;">Thank you for donating!</div>
              <div style="font-size:0.82rem; color:#888; line-height:1.6;">
                Your donation has been recorded. You may be eligible to donate again in 56 days.
              </div>
              @if($appointment->donation)
                @php $nextDate = \Carbon\Carbon::parse($appointment->donation->donation_date)->addDays(56); @endphp
                <div style="margin-top:0.85rem; font-size:0.82rem; font-weight:600; color:#27AE60;">
                  Next eligible date: {{ $nextDate->format('F d, Y') }}
                </div>
              @endif
            </div>
          @elseif($appointment->status->value === 'cancelled')
            <div style="text-align:center; padding:1rem 0;">
              <div style="width:52px; height:52px; border-radius:50%; background:rgba(149,165,166,0.1); border:2px solid rgba(149,165,166,0.3); display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem;">
                <svg width="22" height="22" fill="none" stroke="#95A5A6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
              </div>
              <div style="font-weight:700; font-size:1rem; color:#1a1a2e; margin-bottom:0.4rem;">Appointment Cancelled</div>
              <div style="font-size:0.82rem; color:#888; line-height:1.6;">
                This appointment was cancelled. You can schedule a new one anytime.
              </div>
              <a href="{{ route('donor.appointments.create') }}"
                class="btn btn-dash-primary"
                style="margin-top:1rem; display:inline-flex; justify-content:center;">
                Schedule New Appointment
              </a>
            </div>
          @elseif($appointment->status->value === 'rejected')
            <div style="text-align:center; padding:1rem 0;">
              <div style="width:52px; height:52px; border-radius:50%; background:rgba(231,76,60,0.08); border:2px solid rgba(231,76,60,0.25); display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem;">
                <svg width="22" height="22" fill="none" stroke="#E74C3C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
              </div>
              <div style="font-weight:700; font-size:1rem; color:#1a1a2e; margin-bottom:0.4rem;">Appointment Not Approved</div>
              <div style="font-size:0.82rem; color:#888; line-height:1.6;">
                Your appointment was not approved. Please contact staff or schedule a new one after resolving any issues.
              </div>
              <a href="{{ route('donor.appointments.create') }}"
                class="btn btn-dash-primary"
                style="margin-top:1rem; display:inline-flex; justify-content:center;">
                Schedule New Appointment
              </a>
            </div>
          @else
            <div style="font-size:0.85rem; color:#888; text-align:center; padding:1rem 0;">
              No further action required at this time.
            </div>
          @endif
        </div>
      </div>

    </div>{{-- end right column --}}

  </div>

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
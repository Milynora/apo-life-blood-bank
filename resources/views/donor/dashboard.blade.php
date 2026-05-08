<x-app-layout title="My Dashboard">

  {{-- Welcome banner --}}
  <div style="background:#fff; border:1px solid var(--border-light); border-radius:18px; padding:1.75rem 2rem; margin-bottom:2rem; position:relative; overflow:hidden;">
  <div style="position:absolute; top:-40px; right:-40px; width:200px; height:200px; border-radius:50%; background:rgba(83,52,131,0.06); pointer-events:none;"></div>
  <div style="position:absolute; bottom:-30px; right:160px; width:120px; height:120px; border-radius:50%; background:rgba(192,57,43,0.06); pointer-events:none;"></div>
  <div style="position:absolute; top:-40px; right:-40px; width:200px; height:200px; border-radius:50%; background:rgba(83,52,131,0.06); pointer-events:none;"></div>
  <div style="position:absolute; bottom:-30px; right:160px; width:120px; height:120px; border-radius:50%; background:rgba(192,57,43,0.06); pointer-events:none;"></div>
  <div style="position:absolute; top:-20px; left:-20px; width:140px; height:140px; border-radius:50%; background:rgba(192,57,43,0.05); pointer-events:none;"></div>
  <div style="position:absolute; top:50%; right:320px; transform:translateY(-50%); width:80px; height:80px; border-radius:50%; background:rgba(83,52,131,0.05); pointer-events:none;"></div>
  
  {{-- Decorative shapes --}}
<div style="position:absolute; top:-40px; right:-40px; width:200px; height:200px; border-radius:50%; background:rgba(83,52,131,0.07); pointer-events:none;"></div>
<div style="position:absolute; bottom:-30px; right:160px; width:120px; height:120px; border-radius:50%; background:rgba(192,57,43,0.06); pointer-events:none;"></div>
<div style="position:absolute; top:-20px; left:-20px; width:140px; height:140px; border-radius:50%; background:rgba(192,57,43,0.05); pointer-events:none;"></div>
<div style="position:absolute; top:50%; right:320px; transform:translateY(-50%); width:80px; height:80px; border-radius:50%; background:rgba(83,52,131,0.05); pointer-events:none;"></div>
<div style="position:absolute; bottom:-50px; left:120px; width:180px; height:180px; border-radius:50%; background:rgba(41,128,185,0.05); pointer-events:none;"></div>
<div style="position:absolute; top:10px; left:200px; width:60px; height:60px; border-radius:50%; background:rgba(39,174,96,0.07); pointer-events:none;"></div>
<div style="position:absolute; bottom:10px; right:50px; width:50px; height:50px; border-radius:50%; background:rgba(243,156,18,0.07); pointer-events:none;"></div>
<div style="position:absolute; top:-10px; right:250px; width:90px; height:90px; border-radius:50%; background:rgba(233,69,96,0.06); pointer-events:none;"></div>
<div style="position:absolute; bottom:-20px; left:350px; width:100px; height:100px; border-radius:50%; background:rgba(142,68,173,0.06); pointer-events:none;"></div>
<div style="position:absolute; top:20px; right:500px; width:70px; height:70px; border-radius:16px; transform:rotate(25deg); background:rgba(192,57,43,0.05); pointer-events:none;"></div>
<div style="position:absolute; bottom:5px; left:60px; width:55px; height:55px; border-radius:14px; transform:rotate(15deg); background:rgba(83,52,131,0.06); pointer-events:none;"></div>
<div style="position:absolute; top:30px; left:420px; width:45px; height:45px; border-radius:12px; transform:rotate(-20deg); background:rgba(41,128,185,0.06); pointer-events:none;"></div>
<div style="position:absolute; bottom:-10px; right:420px; width:65px; height:65px; border-radius:50%; background:rgba(22,160,133,0.06); pointer-events:none;"></div>
<div style="position:absolute; top:-15px; left:580px; width:110px; height:110px; border-radius:50%; background:rgba(230,126,34,0.04); pointer-events:none;"></div>

  <div style="position:relative; z-index:1; display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">

    {{-- Info --}}
    <div style="flex:1; min-width:200px;">
      <div style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:var(--primary); margin-bottom:4px;">Welcome back</div>
      <div style="font-size:2rem; font-weight:800; color:#1a1a2e; font-family:var(--font-display); margin-bottom:6px;">{{ $donor->name }}</div>
      <div style="display:flex; align-items:center; gap:0.75rem; flex-wrap:wrap;">
        @php
          $eligible    = true;
          $eligLabel   = 'Eligible';
          $eligMsg     = 'Ready for your first donation!';
          $eligDot     = 'rgba(39,174,96,1)';
          $eligBg      = 'rgba(39,174,96,0.08)';
          $eligBorder  = 'rgba(39,174,96,0.25)';
          $eligTxt     = '#27AE60';

          if ($stats['last_donation']) {
            $daysSince = $stats['last_donation']->diffInDays(now());
            if ($daysSince < 56) {
              $eligible   = false;
              $eligLabel  = 'Not Yet Eligible';
              $nextDate   = $stats['last_donation']->copy()->addDays(56);
              $eligMsg    = 'Next eligible: ' . $nextDate->format('M d, Y');
              $eligDot    = 'rgba(243,156,18,1)';
              $eligBg     = 'rgba(243,156,18,0.08)';
              $eligBorder = 'rgba(243,156,18,0.25)';
              $eligTxt    = '#E67E22';
            } else {
              $eligMsg = 'Ready to donate again';
            }
          }
        @endphp
        <div style="display:inline-flex; align-items:center; gap:0.4rem; background:{{ $eligBg }}; border:1px solid {{ $eligBorder }}; border-radius:20px; padding:0.35rem 1rem;">
          <div style="width:7px; height:7px; border-radius:50%; background:{{ $eligDot }};"></div>
          <span style="font-size:0.78rem; font-weight:700; color:{{ $eligTxt }};">{{ $eligLabel }}</span>
          <span style="font-size:0.75rem; color:#888;">· {{ $eligMsg }}</span>
        </div>
        @if($stats['last_donation'])
          <span style="font-size:0.82rem; color:#1a1a2e; display:flex; align-items:center; gap:0.3rem;">
            <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Last donation: {{ $stats['last_donation']->format('M d, Y') }}
          </span>
        @endif
      </div>
    </div>

    {{-- CTA --}}
    <div style="display:flex; flex-direction:column; align-items:flex-end; gap:0.75rem; flex-shrink:0;">

      <span style="font-size:0.82rem; color:#888;">Donor since {{ auth()->user()->created_at->format('Y') }}</span>

      {{-- CTA buttons --}}
      <div style="display:flex; align-items:center; gap:0.6rem;">
        @if($eligible)
          <a href="{{ route('donor.appointments.create') }}"
            style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.55rem 1.1rem; background:var(--primary); color:#fff; border-radius:10px; font-size:0.82rem; font-weight:700; text-decoration:none; box-shadow:0 4px 12px rgba(192,57,43,0.25); transition:all 0.2s;"
            onmouseover="this.style.opacity='0.88'" onmouseout="this.style.opacity='1'">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Request Appointment
          </a>
        @endif
        <a href="{{ route('donor.donations.index') }}"
          style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.55rem 1.1rem; background:#fff; color:#1a1a2e; border:1px solid var(--border-light); border-radius:10px; font-size:0.82rem; font-weight:600; text-decoration:none; transition:all 0.2s;"
          onmouseover="this.style.borderColor='rgba(192,57,43,0.3)'; this.style.color='var(--primary)'"
          onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#1a1a2e'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
          My Donations
        </a>
      </div>

    </div>

  </div>
</div>

  {{-- Stat cards --}}
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:2rem;">
    <x-stat-card
      title="Total Donations"
      :value="$stats['total_donations']"
      color="red"
      icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"
      :link="route('donor.donations.index')"
      link-label="View history"/>

    <x-stat-card
      title="Total Volume (mL)"
      :value="number_format($stats['total_volume'])"
      color="yellow" 
      icon="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>

    <x-stat-card
      title="Lives Potentially Saved"
      :value="$stats['total_donations'] * 3"
      color="green"
      icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>

    <x-stat-card
      title="Upcoming Appointments"
      :value="$stats['upcoming_appointments']"
      color="blue"
      icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
      :link="route('donor.appointments.index')"
      link-label="View all"/>
  </div>

  {{-- Main content grid --}}
  <div style="display:grid; grid-template-columns:1.2fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">

    {{-- Upcoming appointments --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Upcoming Appointments</h3>
        <a href="{{ route('donor.appointments.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View all</a>
      </div>

      @if($upcomingAppointments->isEmpty())
        <div class="dash-card-body">
          <x-empty-state
            title="No upcoming appointments"
            message="Schedule your next donation appointment."
            :action="route('donor.appointments.create')"
            action-label="Schedule Now"/>
        </div>
      @else
        <div class="dash-card-body" style="padding:0;">
          @foreach($upcomingAppointments as $appt)
            <div style="display:flex; align-items:center; gap:1rem; padding:1rem 1.5rem; border-bottom:1px solid rgba(0,0,0,0.05);">
              {{-- Date block --}}
              <div style="width:50px; height:54px; background:linear-gradient(135deg,var(--primary),#e94560); border-radius:12px; display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0;">
                <div style="font-size:1.1rem; font-weight:800; color:#fff; line-height:1;">{{ $appt->appointment_date->format('d') }}</div>
                <div style="font-size:0.6rem; font-weight:600; color:rgba(255,255,255,0.8); text-transform:uppercase; letter-spacing:0.05em;">{{ $appt->appointment_date->format('M') }}</div>
              </div>
              <div style="flex:1;">
                <div style="font-weight:600; font-size:0.9rem; color:#1a1a2e;">{{ $appt->appointment_date->format('l, F d, Y') }}</div>
                <div style="font-size:0.78rem; color:#888; margin-top:0.2rem;">{{ $appt->appointment_date->format('h:i A') }}</div>
                @if($appt->notes)
                  <div style="font-size:0.75rem; color:#aaa; margin-top:0.2rem; font-style:italic;">{{ Str::limit($appt->notes, 50) }}</div>
                @endif
              </div>
              <x-status-badge :status="$appt->status->value" size="sm"/>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Recent notifications --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Recent Notifications</h3>
        <a href="{{ route('notifications.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View all</a>
      </div>

      @if($recentNotifications->isEmpty())
        <div class="dash-card-body">
          <x-empty-state title="No notifications yet"/>
        </div>
      @else
        @foreach($recentNotifications as $notif)
          <x-notification-item :notification="$notif"/>
        @endforeach
      @endif
    </div>

  </div>

  {{-- Recent donations --}}
  <div class="dash-card">
    <div class="dash-card-header">
      <h3 class="dash-card-title">Recent Donations</h3>
      <a href="{{ route('donor.donations.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">Full history →</a>
    </div>

    @if($recentDonations->isEmpty())
      <div class="dash-card-body">
        <x-empty-state title="No donations recorded yet" message="Your donation history will appear here after your first donation."/>
      </div>
    @else
      <div class="table-container" style="border:none; border-radius:0;">
        <table class="data-table">
          <thead>
            <tr>
              <th>Date</th>
              <th>Volume</th>
              <th>Status</th>
              <th>Units Created</th>
              <th>Screening</th>
            </tr>
          </thead>
          <tbody>
            @foreach($recentDonations as $d)
              <tr>
                <td style="font-weight:500; font-size:0.875rem;">{{ $d->donation_date->format('M d, Y') }}</td>
                <td>
                  <span style="font-weight:700; color:#1a1a2e;">{{ $d->volume }}</span>
                  <span style="font-size:0.75rem; color:#999;"> mL</span>
                </td>
                <td><x-status-badge :status="$d->status->value" size="sm"/></td>
                <td style="font-size:0.875rem; color:#666;">{{ $d->bloodUnits->count() }} unit(s)</td>
                <td>
                  @if($d->screening)
                    <x-status-badge :status="$d->screening->eligibility_status->value" size="sm"/>
                  @else
                    <span style="font-size:0.78rem; color:#ccc;">—</span>
                  @endif
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:repeat(4"] { grid-template-columns: repeat(2,1fr) !important; }
      div[style*="grid-template-columns:1.2fr 1fr"] { grid-template-columns: 1fr !important; }
    }
    @media (max-width: 480px) {
      div[style*="grid-template-columns:repeat(4"] { grid-template-columns: 1fr 1fr !important; }
    }
  </style>

</x-app-layout>

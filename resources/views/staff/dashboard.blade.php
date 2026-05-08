<x-app-layout title="Staff Dashboard">

  <div class="page-header">
    <div>
      <div class="breadcrumb"><span>Staff</span></div>
      <h1 class="page-title">Staff Dashboard</h1>
      <p class="page-subtitle">Welcome back, {{ auth()->user()->staff->full_name ?? auth()->user()->name }}. Here's your overview for today.</p>
    </div>
  </div>

  {{-- Stat cards --}}
  <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:1.25rem; margin-bottom:2rem;">
    <x-stat-card title="Today's Appointments" :value="$stats['today_appointments']" color="blue"   icon="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2"/>
    <x-stat-card title="Today's Donations"    :value="$stats['today_donations']"    color="red"    icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    <x-stat-card title="Total Donors"         :value="$stats['total_donors']"       color="purple" icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    <x-stat-card title="Available Units"       :value="$stats['available_units']"    color="green"  icon="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
    <x-stat-card title="Expiring in 7 Days"    :value="$stats['expiring_soon']"      color="yellow" icon="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
  </div>

  @if($stats['expiring_soon'] > 0)
    <div class="alert alert-warning" style="margin-bottom:1.75rem;">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <div><strong>{{ $stats['expiring_soon'] }} unit(s)</strong> are expiring within 7 days. <a href="{{ route('staff.inventory.index') }}?status=available" style="color:#E67E22; font-weight:600; text-decoration:none; border-bottom:1.5px solid transparent; transition:border-color 0.2s;" onmouseover="this.style.borderBottom='1.5px solid #E67E22'" onmouseout="this.style.borderBottom='1.5px solid transparent'">View inventory</a></div>
    </div>
  @endif

  <div style="display:grid; grid-template-columns:1.3fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">

    {{-- Today's appointments --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Today's Appointments</h3>
        <a href="{{ route('staff.appointments.index') }}?date={{ today()->toDateString() }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View all</a>
      </div>
      @if($todayAppointments->isEmpty())
        <div class="dash-card-body"><x-empty-state title="No approved appointments today" message="Pending appointments may still arrive."/></div>
      @else
        <div style="padding:0;">
          @foreach($todayAppointments as $appt)
            <div style="display:flex; align-items:center; gap:1rem; padding:1rem 1.5rem; border-bottom:1px solid rgba(0,0,0,0.05);">
              <div style="width:42px; height:46px; background:linear-gradient(135deg,var(--primary),#e94560); border-radius:10px; display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0;">
                <div style="font-size:1rem; font-weight:800; color:#fff; line-height:1;">{{ $appt->appointment_date->format('d') }}</div>
                <div style="font-size:0.55rem; font-weight:600; color:rgba(255,255,255,0.85); text-transform:uppercase;">{{ $appt->appointment_date->format('M') }}</div>
              </div>
              <div style="flex:1;">
                <div style="font-weight:600; font-size:0.9rem; color:#1a1a2e;">{{ $appt->donor->name }}</div>
                <div style="font-size:0.78rem; color:#888; display:flex; align-items:center; gap:0.5rem;">
                  {{ $appt->appointment_date->format('h:i A') }}
                  @if($appt->donor->bloodType)
                    &bull;
                    <x-blood-type-badge :type="$appt->donor->bloodType->type_name"/>
                  @endif
                </div>
              </div>
              <x-status-badge :status="$appt->status->value" size="sm"/>
            </div>
          @endforeach
        </div>
      @endif
    </div>

    {{-- Inventory summary --}}
<div class="dash-card">
  <div class="dash-card-header">
    <h3 class="dash-card-title">Blood Inventory</h3>
    <a href="{{ route('staff.inventory.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View All</a>
  </div>

  {{-- Summary strip --}}
  @php
    $totalUnits = $inventorySummary->sum();
    $zeroTypes  = $inventorySummary->filter(fn($c) => $c === 0)->count();
  @endphp
  <div style="display:grid; grid-template-columns:repeat(3,1fr); border-bottom:1px solid rgba(0,0,0,0.06);">
    <div style="padding:0.75rem 1rem; text-align:center; border-right:1px solid rgba(0,0,0,0.06);">
      <div style="font-size:1.25rem; font-weight:700; color:#1a1a2e; font-family:var(--font-display);">{{ $totalUnits }}</div>
      <div style="font-size:0.7rem; color:#aaa; margin-top:2px;">Total units</div>
    </div>
    <div style="padding:0.75rem 1rem; text-align:center; border-right:1px solid rgba(0,0,0,0.06);">
      <div style="font-size:1.25rem; font-weight:700; color:{{ $stats['expiring_soon'] > 0 ? '#E74C3C' : '#1a1a2e' }}; font-family:var(--font-display);">{{ $stats['expiring_soon'] }}</div>
      <div style="font-size:0.7rem; color:#aaa; margin-top:2px;">Expiring soon</div>
    </div>
    <div style="padding:0.75rem 1rem; text-align:center;">
      <div style="font-size:1.25rem; font-weight:700; color:{{ $zeroTypes > 0 ? '#888' : '#1a1a2e' }}; font-family:var(--font-display);">{{ $zeroTypes }}</div>
      <div style="font-size:0.7rem; color:#aaa; margin-top:2px;">Types at zero</div>
    </div>
  </div>

  {{-- Blood type grid --}}
  @php
    $btColors = ['A+'=>'#3498DB','A-'=>'#2980B9','B+'=>'#9B59B6','B-'=>'#8E44AD','AB+'=>'#1ABC9C','AB-'=>'#16A085','O+'=>'#E74C3C','O-'=>'#C0392B'];
    $maxUnits = $inventorySummary->max() ?: 1;
  @endphp
  <div style="display:grid; grid-template-columns:repeat(4,1fr);">
    @foreach($inventorySummary as $type => $count)
      @php
        $color   = $btColors[$type] ?? '#888';
        $pct     = round(($count / $maxUnits) * 100);
        $colPos  = $loop->index % 4;
        $isLast4 = $loop->index >= 4;
        $borderR = $colPos < 3 ? '1px solid rgba(0,0,0,0.06)' : 'none';
        $borderB = !$isLast4   ? '1px solid rgba(0,0,0,0.06)' : 'none';

        if ($count === 0)    { $badgeText = 'Empty'; $badgeBg = '#f1f1f1'; $badgeColor = '#999'; }
        elseif ($count <= 3) { $badgeText = 'Low';   $badgeBg = '#fdecea'; $badgeColor = '#c0392b'; }
        else                 { $badgeText = 'Good';  $badgeBg = '#eaf6f0'; $badgeColor = '#27ae60'; }
      @endphp
      <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; gap:4px; padding:0.85rem 0.5rem; border-right:{{ $borderR }}; border-bottom:{{ $borderB }};">
        <div style="font-family:var(--font-mono); font-size:0.85rem; font-weight:700; color:{{ $color }};">{{ $type }}</div>
        <div style="font-size:1.35rem; font-weight:800; color:{{ $count === 0 ? '#ccc' : '#1a1a2e' }}; font-family:var(--font-display); line-height:1;">{{ $count }}</div>
        <div style="width:36px; height:4px; background:rgba(0,0,0,0.07); border-radius:2px; overflow:hidden;">
          <div style="height:100%; width:{{ $pct }}%; background:{{ $color }}; border-radius:2px;"></div>
        </div>
        <div style="font-size:0.65rem; font-weight:600; padding:1px 6px; border-radius:3px; background:{{ $badgeBg }}; color:{{ $badgeColor }};">{{ $badgeText }}</div>
      </div>
    @endforeach
  </div>
</div>
    </div>

  </div>

  {{-- Recent donations --}}
  <div class="dash-card">
    <div class="dash-card-header">
      <h3 class="dash-card-title">Recent Donations</h3>
      <a href="{{ route('staff.donations.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View all</a>
    </div>
    @if($recentDonations->isEmpty())
      <div class="dash-card-body"><x-empty-state title="No donations recorded yet"/></div>
    @else
      <div class="table-container" style="border:none; border-radius:0;">
        <table class="data-table">
          <thead><tr><th>Donor</th><th>Blood Type</th><th>Volume</th><th>Date</th><th>Status</th><th>Staff</th></tr></thead>
          <tbody>
            @foreach($recentDonations as $d)
              <tr>
                <td style="font-weight:600; font-size:0.875rem;">{{ $d->donor->name }}</td>
                <td><x-blood-type-badge :type="$d->donor->bloodType->type_name??'?'"/></td>
                <td style="font-weight:600;">{{ $d->volume }} <span style="font-size:0.73rem; color:#aaa;">mL</span></td>
                <td style="font-size:0.82rem; color:#888;">{{ $d->donation_date->format('M d, Y') }}</td>
                <td><x-status-badge :status="$d->status->value" size="sm"/></td>
                <td style="font-size:0.82rem; color:#666;">{{ $d->staff->name??'—' }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    @endif
  </div>

  <style>
    @media (max-width: 1100px) {
      div[style*="grid-template-columns:repeat(5"] { grid-template-columns: repeat(3,1fr)!important; }
    }
    @media (max-width: 768px) {
      div[style*="grid-template-columns:repeat(5"] { grid-template-columns: repeat(2,1fr)!important; }
      div[style*="grid-template-columns:1.3fr 1fr"] { grid-template-columns: 1fr!important; }
    }
  </style>

</x-app-layout>
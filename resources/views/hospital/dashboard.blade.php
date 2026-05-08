<x-app-layout title="Hospital Dashboard">

  {{-- Welcome banner --}}
<div style="background:#fff; border:1px solid var(--border-light); border-radius:var(--radius-xl); padding:2rem 2.5rem; margin-bottom:2rem; position:relative; overflow:hidden;">
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

  <div style="position:relative; z-index:1; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1.5rem;">
    <div>
      <div style="font-size:0.78rem; font-weight:600; text-transform:uppercase; letter-spacing:0.1em; color:var(--primary); margin-bottom:0.4rem;">Hospital Dashboard</div>
      <h2 style="font-family:var(--font-display); font-size:2rem; font-weight:800; color:#1a1a2e; margin-bottom:0.5rem; letter-spacing:-0.02em;">
        {{ $hospital->hospital_name }}
      </h2>
      <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">
        <span style="font-size:0.82rem; color:#888;">{{ $hospital->address ?? 'Davao City' }}</span>
        <span style="display:inline-flex; align-items:center; gap:0.35rem; font-size:0.78rem; background:rgba(39,174,96,0.08); border:1px solid rgba(39,174,96,0.25); color:#27AE60; padding:0.25rem 0.7rem; border-radius:20px; font-weight:600;">
          <div style="width:6px; height:6px; border-radius:50%; background:#27AE60;"></div>
          Verified Hospital
        </span>
      </div>
    </div>
    <a href="{{ route('hospital.requests.create') }}" class="btn btn-primary">
      <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Submit Blood Request
    </a>
  </div>
</div>

  {{-- Stat cards --}}
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:2rem;">
    <x-stat-card
      title="Total Requests"
      :value="$stats['total_requests']"
      color="purple"
      icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"
      :link="route('hospital.requests.index')"
      link-label="View all"/>

    <x-stat-card
      title="Pending"
      :value="$stats['pending_requests']"
      color="yellow"
      icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>

    <x-stat-card
      title="Approved"
      :value="$stats['approved_requests']"
      color="blue"
      icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>

    <x-stat-card
      title="Fulfilled"
      :value="$stats['fulfilled_requests']"
      color="green"
      icon="M5 13l4 4L19 7"/>
  </div>

  {{-- Main grid --}}
  <div style="display:grid; grid-template-columns:1fr; gap:1.5rem; margin-bottom:1.5rem;">

    {{-- Recent requests --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Recent Requests</h3>
        <a href="{{ route('hospital.requests.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View all</a>
      </div>
      @if($recentRequests->isEmpty())
        <div class="dash-card-body">
          <x-empty-state
            title="No requests yet"
            message="Submit your first blood request."
            :action="route('hospital.requests.create')"
            action-label="Submit Request"/>
        </div>
      @else
        <div class="table-container" style="border:none; border-radius:0;">
          <table class="data-table">
            <thead>
  <tr>
    <th>Blood Type</th>
    <th>Quantity</th>
    <th>Date</th>
    <th>Status</th>
    <th style="text-align:right;">Action</th>
  </tr>
</thead>
<tbody>
  @foreach($recentRequests as $req)
    <tr>
      <td><x-blood-type-badge :type="$req->bloodType->type_name"/></td>
      <td style="font-weight:700; font-size:0.95rem;">{{ $req->quantity }}</td>
      <td style="font-size:0.8rem; color:#888;">{{ $req->request_date->format('M d, Y') }}</td>
      <td><x-status-badge :status="$req->status->value" size="sm"/></td>
      <td>
        <div style="display:flex; justify-content:flex-end;">
          <a href="{{ route('hospital.requests.show', $req) }}"
            style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(41,128,185,0.3); background:rgba(41,128,185,0.08); color:#2980B9; text-decoration:none; transition:all 0.2s;"
            onmouseover="this.style.background='rgba(41,128,185,0.18)'"
            onmouseout="this.style.background='rgba(41,128,185,0.08)'"
            title="View">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
          </a>
        </div>
      </td>
    </tr>
  @endforeach
</tbody>
          </table>
        </div>
      @endif
    </div>

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

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:repeat(4"] { grid-template-columns: repeat(2,1fr) !important; }
    }
  </style>

</x-app-layout>
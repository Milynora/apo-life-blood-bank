<x-app-layout title="Admin Dashboard">

  {{-- Page Header --}}
  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <span>Admin</span>
      </div>
      <h1 class="page-title">Dashboard</h1>
      <p class="page-subtitle">Welcome back. Here's what's happening today.</p>
    </div>
  </div>

  {{-- ── Stat Cards Row 1 ─────────────────────────────────────── --}}
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:1.25rem;">
    <x-stat-card
      title="Total Donors"
      :value="$stats['total_donors']"
      color="red"
      icon="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
    <x-stat-card
      title="Pending Approvals"
      :value="$stats['pending_users']"
      color="yellow"
      icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card
      title="Available Units"
      :value="$stats['available_units']"
      color="green"
      icon="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
    <x-stat-card
      title="Pending Requests"
      :value="$stats['pending_requests']"
      color="blue"
      icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
  </div>

  {{-- ── Stat Cards Row 2 ─────────────────────────────────────── --}}
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:2rem;">
    <x-stat-card
      title="Total Hospitals"
      :value="$stats['total_hospitals']"
      color="teal"
      icon="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
    <x-stat-card
      title="Total Donations"
      :value="$stats['total_donations']"
      color="purple"
      icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    <x-stat-card
      title="Expiring in 7 Days"
      :value="$stats['expiring_soon']"
      color="gray"
      icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    <x-stat-card
      title="Total Requests"
      :value="$stats['total_requests']"
      color="pink"
      icon="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
  </div>

  {{-- Expiring soon alert --}}
  @if($stats['expiring_soon'] > 0)
    <div class="alert alert-warning" style="margin-bottom:2rem;">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <div>
        <strong>{{ $stats['expiring_soon'] }} blood unit(s)</strong> are expiring within 7 days.
        <a href="{{ route('admin.inventory.index') }}?status=available" style="color:#E67E22; font-weight:600; margin-left:0.5rem; text-decoration:none; border-bottom:1.5px solid transparent; transition:border-color 0.2s;" onmouseover="this.style.borderBottom='1.5px solid #E67E22'" onmouseout="this.style.borderBottom='1.5px solid transparent'">View Inventory</a>
      </div>
    </div>
  @endif

  {{-- ── Charts Row ──────────────────────────────────────── --}}
  <div style="display:grid; grid-template-columns:1.6fr 1fr; gap:1.5rem; margin-bottom:2rem;">

    {{-- Donations chart --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Donations This Year</h3>
        <span style="font-size:0.78rem; color:#999;">{{ date('Y') }}</span>
      </div>
      <div class="dash-card-body">
        <div class="chart-wrap" style="height:220px;">
          <canvas id="donationsChart"></canvas>
        </div>
      </div>
    </div>

    {{-- Inventory summary --}}
<div class="dash-card">
  <div class="dash-card-header">
    <h3 class="dash-card-title">Blood Inventory</h3>
    <a href="{{ route('admin.inventory.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View All</a>
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

  {{-- ── Tables Row ──────────────────────────────────────── --}}
  <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem;">

    {{-- Pending Approvals --}}
    <div class="dash-card" x-data>
  <div class="dash-card-header">
    <h3 class="dash-card-title">Pending Approvals</h3>
        <a href="{{ route('admin.users.index') }}?status=pending" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View all</a>
      </div>
      @if($pendingUsers->isEmpty())
        <div class="dash-card-body">
          <x-empty-state title="No pending approvals" message="All registrations have been reviewed."/>
        </div>
      @else
        <div class="table-container" style="border:none; border-radius:0;">
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Role</th>
                <th>Registered</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($pendingUsers as $u)
                <tr>
                  <td>
                    <div style="display:flex; align-items:center; gap:0.75rem;">
                      <div class="avatar-initials" style="width:34px; height:34px; font-size:0.75rem;">{{ strtoupper(substr($u->name,0,1)) }}</div>
                      <div>
                        <div style="font-weight:600; font-size:0.85rem; color:#1a1a2e;">{{ $u->name }}</div>
                        <div style="font-size:0.75rem; color:#999;">{{ $u->email }}</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge badge-role-{{ $u->role->value }}">{{ ucfirst($u->role->value) }}</span></td>
                  <td style="font-size:0.8rem; color:#888;">{{ $u->created_at->diffForHumans() }}</td>
                  <td>
                    <div style="display:flex; gap:0.5rem;">
                      <form method="POST" action="{{ route('admin.users.approve', $u) }}">
                        @csrf @method('PATCH')
                        <button type="submit" class="btn btn-success btn-sm" style="border-radius:8px; padding:0.3rem 0.75rem;">
                          <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                        </button>
                      </form>
                      <button type="button" class="btn btn-danger btn-sm" style="border-radius:8px; padding:0.3rem 0.75rem;"
                        @click="$dispatch('open-modal', 'reject-{{ $u->id }}')">
                        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                      </button>
                    </div>

                    {{-- Reject modal --}}
                    <x-modal id="reject-{{ $u->id }}" title="Reject Registration" size="sm">
                      <form method="POST" action="{{ route('admin.users.reject', $u) }}">
                        @csrf @method('PATCH')
                        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">Rejecting <strong>{{ $u->name }}</strong>. Optionally provide a reason:</p>
                        <div class="form-group">
                          <label class="form-label">Reason (optional)</label>
                          <textarea name="reason" class="form-input form-input-light" rows="3" placeholder="Reason for rejection..."></textarea>
                        </div>
                        <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                          <button type="button" onclick="closeModal()" class="btn btn-sm" style="background:#f0f0f0; color:#555;">Cancel</button>
                          <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                        </div>
                      </form>
                    </x-modal>

                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Recent Blood Requests --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Recent Blood Requests</h3>
        <a href="{{ route('admin.blood-requests.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View all</a>
      </div>
      @if($recentRequests->isEmpty())
        <div class="dash-card-body"><x-empty-state title="No requests yet"/></div>
      @else
        <div class="table-container" style="border:none; border-radius:0;">
          <table class="data-table">
            <thead><tr><th>Hospital</th><th>Type</th><th>Qty</th><th>Status</th><th></th></tr></thead>
            <tbody>
              @foreach($recentRequests as $req)
                <tr>
                  <td style="font-size:0.85rem; font-weight:500; color:#1a1a2e;">{{ Str::limit($req->hospital->hospital_name, 22) }}</td>
                  <td><x-blood-type-badge :type="$req->bloodType->type_name"/></td>
                  <td style="font-weight:600; color:#1a1a2e;">{{ $req->quantity }}</td>
                  <td><x-status-badge :status="$req->status->value" size="sm"/></td>
                  <td><a href="{{ route('admin.blood-requests.show', $req) }}" style="font-size:0.78rem; color:var(--primary); font-weight:600; text-decoration:none;">View</a></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

  </div>

  {{-- ── Recent Donations ───────────────────────────────── --}}
  <div class="dash-card">
    <div class="dash-card-header">
      <h3 class="dash-card-title">Recent Donations</h3>
      <a href="{{ route('admin.donations.index') }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View all</a>
    </div>
    @if($recentDonations->isEmpty())
      <div class="dash-card-body"><x-empty-state title="No donations recorded yet"/></div>
    @else
      <div class="table-container" style="border:none; border-radius:0;">
        <table class="data-table">
          <thead>
            <tr>
              <th>Donor</th>
              <th>Blood Type</th>
              <th>Volume</th>
              <th>Date</th>
              <th>Status</th>
              <th>Staff</th>
            </tr>
          </thead>
          <tbody>
  @foreach($recentDonations as $d)
    <tr>
      <td>
        <div style="font-weight:600; font-size:0.875rem; color:#1a1a2e;">{{ $d->donor->name }}</div>
        <div style="font-size:0.75rem; color:#999;">{{ $d->donor->user->email }}</div>
      </td>
      <td>
        @if($d->donor->bloodType)
          <x-blood-type-badge :type="$d->donor->bloodType->type_name"/>
        @else
          <span style="font-size:0.78rem; color:#bbb; font-style:italic;">Unknown</span>
        @endif
      </td>
      <td style="font-size:0.875rem; font-weight:600;">{{ $d->volume }} mL</td>
      <td style="font-size:0.82rem; color:#888;">{{ $d->donation_date->format('M d, Y') }}</td>
      <td><x-status-badge :status="$d->status->value" size="sm"/></td>
      <td style="font-size:0.82rem; color:#666;">{{ $d->staff->name ?? '—' }}</td>
    </tr>
  @endforeach
</tbody>
        </table>
      </div>
    @endif
  </div>

  @push('scripts')
  <script>
    // Donations bar chart
    const donCtx = document.getElementById('donationsChart').getContext('2d');
    const months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    const donData = @json($donationsByMonth ?? []);
    const donValues = months.map((_, i) => donData[i+1] ?? 0);

    new Chart(donCtx, {
      type: 'bar',
      data: {
        labels: months,
        datasets: [{
          label: 'Donations',
          data: donValues,
          backgroundColor: 'rgba(192,57,43,0.15)',
          borderColor: '#C0392B',
          borderWidth: 2,
          borderRadius: 6,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#999' } },
          y: { grid: { color: 'rgba(0,0,0,0.05)' }, ticks: { font: { size: 11 }, color: '#999' }, beginAtZero: true },
        },
      }
    });

    // Inventory doughnut
    const invCtx = document.getElementById('inventoryChart').getContext('2d');
    const invData = @json($inventorySummary);
    const invColors = ['#3498DB','#9B59B6','#1ABC9C','#E74C3C','#2980B9','#8E44AD','#16A085','#C0392B'];

    new Chart(invCtx, {
      type: 'doughnut',
      data: {
        labels: Object.keys(invData),
        datasets: [{
          data: Object.values(invData),
          backgroundColor: invColors,
          borderWidth: 2,
          borderColor: '#fff',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: { display: false },
          tooltip: { callbacks: { label: (ctx) => ` ${ctx.label}: ${ctx.parsed} units` } }
        },
        cutout: '65%',
      }
    });
  </script>
  @endpush

  <style>
    @media (max-width: 1024px) {
      .stat-card { padding: 1.25rem; }
    }
    @media (max-width: 768px) {
      div[style*="grid-template-columns:repeat(4"] { grid-template-columns: repeat(2,1fr) !important; }
      div[style*="grid-template-columns:1.6fr 1fr"] { grid-template-columns: 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr"]   { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>


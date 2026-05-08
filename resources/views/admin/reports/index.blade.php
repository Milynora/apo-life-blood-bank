<x-app-layout title="Reports">

@php
  $isStaff     = auth()->user()->isStaff();
  $routePrefix = $isStaff ? 'staff' : 'admin';
  $dashRoute   = $isStaff ? route('staff.dashboard') : route('admin.dashboard');

  $successDonations = $donations->filter(fn($d) => $d->status->value === 'successful')->count();
  $failedDonations  = $donations->filter(fn($d) => $d->status->value === 'failed')->count();
  $totalVol         = $donations->filter(fn($d) => $d->status->value === 'successful')->sum('volume');
  $fulfilledReqs    = $requests->filter(fn($r) => $r->status->value === 'fulfilled')->count();

  $reqPending   = $requests->filter(fn($r) => $r->status->value === 'pending')->count();
  $reqApproved  = $requests->filter(fn($r) => $r->status->value === 'approved')->count();
  $reqFulfilled = $requests->filter(fn($r) => $r->status->value === 'fulfilled')->count();
  $reqRejected  = $requests->filter(fn($r) => $r->status->value === 'rejected')->count();

  $btColors = ['A+'=>'#3498DB','A-'=>'#2980B9','B+'=>'#9B59B6','B-'=>'#8E44AD','AB+'=>'#1ABC9C','AB-'=>'#16A085','O+'=>'#E74C3C','O-'=>'#C0392B'];
@endphp

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ $dashRoute }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>Reports</span>
      </div>
      <h1 class="page-title">Reports & Analytics</h1>
      <p class="page-subtitle">Donation trends, request statistics, and inventory performance.</p>
    </div>
    <form method="GET" action="{{ route($routePrefix . '.reports.index') }}">
      <div style="display:flex; gap:0.5rem; background:#fff; border:1px solid var(--border-light); border-radius:12px; padding:0.35rem;">
        @foreach(['monthly'=>'This Month','yearly'=>'This Year'] as $val=>$lbl)
          <button type="submit" name="period" value="{{ $val }}"
            style="padding:0.5rem 1.1rem; border-radius:8px; border:none; font-size:0.82rem; font-weight:600; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;
                   background:{{ $period===$val ? 'linear-gradient(135deg,var(--primary),#e94560)' : 'transparent' }};
                   color:{{ $period===$val ? '#fff' : '#888' }};
                   box-shadow:{{ $period===$val ? '0 2px 8px rgba(192,57,43,0.3)' : 'none' }};">
            {{ $lbl }}
          </button>
        @endforeach
      </div>
    </form>
  </div>

  {{-- KPI Cards --}}
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:2rem;">
    <x-stat-card title="{{ $period==='monthly' ? 'Donations This Month' : 'Donations This Year' }}"
      :value="$donations->count()" color="red"
      icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    <x-stat-card title="Total Volume (mL)"
      :value="number_format($totalVol)" color="pink"
      icon="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
    <x-stat-card title="{{ $period==='monthly' ? 'Requests This Month' : 'Requests This Year' }}"
      :value="$requests->count()" color="blue"
      icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    <x-stat-card title="Fulfilled Requests"
      :value="$fulfilledReqs" color="green"
      icon="M5 13l4 4L19 7"/>
  </div>

  {{-- Charts row --}}
  <div style="display:grid; grid-template-columns:1.6fr 1fr; gap:1.5rem; margin-bottom:2rem;">

    {{-- Donations trend --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Donation Trend</h3>
        <span style="font-size:0.75rem; color:#bbb;">
          {{ $period==='monthly' ? now()->format('F Y') : now()->format('Y') }}
        </span>
      </div>
      <div class="dash-card-body">
        <div style="height:240px; position:relative;">
          <canvas id="trendChart" role="img" aria-label="Donation trend chart"></canvas>
        </div>
      </div>
    </div>

    {{-- Request status donut --}}
    <div class="dash-card">
      <div class="dash-card-header"><h3 class="dash-card-title">Request Status</h3></div>
      <div class="dash-card-body">
        <div style="height:170px; display:flex; justify-content:center; position:relative;">
          <canvas id="reqDonut" style="max-width:190px;" role="img" aria-label="Request status donut chart"></canvas>
        </div>
        <div style="display:flex; flex-wrap:wrap; gap:0.6rem; margin-top:1rem; justify-content:center;">
          @foreach([
            ['Pending',   '#F39C12', $reqPending],
            ['Approved',  '#3498DB', $reqApproved],
            ['Fulfilled', '#27AE60', $reqFulfilled],
            ['Rejected',  '#E74C3C', $reqRejected],
          ] as [$lbl, $clr, $cnt])
            <div style="display:flex; align-items:center; gap:0.35rem; font-size:0.75rem; color:#666;">
              <div style="width:9px; height:9px; border-radius:50%; background:{{ $clr }};"></div>
              {{ $lbl }}: <strong style="color:#333;">{{ $cnt }}</strong>
            </div>
          @endforeach
        </div>
      </div>
    </div>

  </div>

  {{-- Blood type breakdown + inventory snapshot --}}
  <div style="display:grid; grid-template-columns:1fr 1.4fr; gap:1.5rem; margin-bottom:2rem;">

    {{-- Donations by blood type --}}
    <div class="dash-card">
      <div class="dash-card-header"><h3 class="dash-card-title">Donations by Blood Type</h3></div>
      <div class="dash-card-body">
        <div style="height:220px; position:relative;">
          <canvas id="btChart" role="img" aria-label="Donations by blood type chart"></canvas>
        </div>
      </div>
    </div>

    {{-- Current inventory snapshot --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Current Inventory Snapshot</h3>
        <a href="{{ route($routePrefix . '.inventory.index') }}"
          style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">
          Full view →
        </a>
      </div>
      <div class="dash-card-body" style="padding:1rem;">
        <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:0.6rem;">
          @foreach($inventoryByType as $type => $count)
            @php $color = $btColors[$type] ?? '#888'; @endphp
            <div style="text-align:center; padding:0.7rem 0.3rem; border-radius:10px; background:{{ $color }}11; border:1px solid {{ $color }}33;">
              <div style="font-family:var(--font-mono); font-size:0.9rem; font-weight:800; color:{{ $color }}; line-height:1;">{{ $type }}</div>
              <div style="font-size:1.2rem; font-weight:800; color:{{ $count === 0 ? '#E74C3C' : '#1a1a2e' }}; font-family:var(--font-display); margin-top:0.2rem;">{{ $count }}</div>
              <div style="font-size:0.62rem; color:#aaa; text-transform:uppercase; letter-spacing:0.04em; margin-top:0.15rem;">units</div>
            </div>
          @endforeach
        </div>

        @if($expiredUnits > 0)
          <div style="margin-top:1rem; padding:0.7rem 0.85rem; background:rgba(192,57,43,0.06); border:1px solid rgba(192,57,43,0.2); border-radius:10px; display:flex; align-items:center; gap:0.65rem;">
            <svg width="15" height="15" fill="none" stroke="#E74C3C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <span style="font-size:0.78rem; color:#C0392B; font-weight:600;">{{ $expiredUnits }} expired unit(s) on record.</span>
          </div>
        @endif
      </div>
    </div>

  </div>

  {{-- Data tables --}}
  <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

    {{-- Donations table --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          Donations
          <span style="font-size:0.75rem; font-weight:400; color:#bbb;">({{ $donations->count() }})</span>
        </h3>
        <a href="{{ route('staff.donations.index') }}"
          style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">
          View all
        </a>
      </div>
      @if($donations->isEmpty())
        <div class="dash-card-body"><x-empty-state title="No donations this period"/></div>
      @else
        <div class="table-container" style="border:none; border-radius:0; max-height:340px; overflow-y:auto;">
          <table class="data-table">
            <thead style="position:sticky; top:0; z-index:1; background:#f8f9fc;">
              <tr><th>Donor</th><th>Type</th><th>Volume</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
              @foreach($donations->sortByDesc('donation_date') as $d)
                <tr>
                  <td style="font-size:0.82rem; font-weight:500; color:#1a1a2e;">
                    {{ Str::limit($d->donor->name, 22) }}
                  </td>
                  <td>
                    @if($d->donor->bloodType)
                      <x-blood-type-badge :type="$d->donor->bloodType->type_name"/>
                    @else
                      <span style="font-size:0.78rem; color:#bbb; font-style:italic;">Unknown</span>
                    @endif
                  </td>
                  <td style="font-size:0.82rem; font-weight:600;">
                    {{ $d->volume }}<span style="color:#aaa; font-size:0.7rem;"> mL</span>
                  </td>
                  <td style="font-size:0.78rem; color:#aaa;">{{ $d->donation_date->format('M d') }}</td>
                  <td><x-status-badge :status="$d->status->value" size="sm"/></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

    {{-- Blood requests table --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          Blood Requests
          <span style="font-size:0.75rem; font-weight:400; color:#bbb;">({{ $requests->count() }})</span>
        </h3>
        <a href="{{ route($routePrefix . '.blood-requests.index') }}"
          style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">
          View all
        </a>
      </div>
      @if($requests->isEmpty())
        <div class="dash-card-body"><x-empty-state title="No requests this period"/></div>
      @else
        <div class="table-container" style="border:none; border-radius:0; max-height:340px; overflow-y:auto;">
          <table class="data-table">
            <thead style="position:sticky; top:0; z-index:1; background:#f8f9fc;">
              <tr><th>Hospital</th><th>Type</th><th>Qty</th><th>Date</th><th>Status</th></tr>
            </thead>
            <tbody>
              @foreach($requests->sortByDesc('request_date') as $r)
                <tr>
                  <td style="font-size:0.82rem; font-weight:500; color:#1a1a2e;">
                    {{ Str::limit($r->hospital->hospital_name, 22) }}
                  </td>
                  <td><x-blood-type-badge :type="$r->bloodType->type_name"/></td>
                  <td style="font-size:0.85rem; font-weight:700;">{{ $r->quantity }}</td>
                  <td style="font-size:0.78rem; color:#aaa;">{{ $r->request_date->format('M d') }}</td>
                  <td><x-status-badge :status="$r->status->value" size="sm"/></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>

  </div>

  @push('scripts')
  <script>
    // ── Trend chart ───────────────────────────────────────────────
    const tCtx = document.getElementById('trendChart').getContext('2d');
    @if($period === 'monthly')
      @php
        $donByDay = $donations->groupBy(fn($d) => $d->donation_date->day)->map->count();
        $days     = now()->daysInMonth;
      @endphp
      const tLabels = Array.from({length: {{ $days }}}, (_, i) => i + 1);
      const tData   = tLabels.map(d => (@json($donByDay))[d] ?? 0);
    @else
      @php
        $donByMonth = $donations->groupBy(fn($d) => $d->donation_date->month)->map->count();
      @endphp
      const tLabels = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
      const tData   = tLabels.map((_, i) => (@json($donByMonth))[i + 1] ?? 0);
    @endif

    new Chart(tCtx, {
      type: 'bar',
      data: {
        labels: tLabels,
        datasets: [{
          label: 'Donations',
          data: tData,
          backgroundColor: 'rgba(192,57,43,0.15)',
          borderColor: '#C0392B',
          borderWidth: 2,
          borderRadius: 5,
          borderSkipped: false,
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
          x: { grid: { display: false }, ticks: { font: { size: 11 }, color: '#bbb', maxTicksLimit: 12 } },
          y: { grid: { color: 'rgba(0,0,0,0.04)' }, ticks: { font: { size: 11 }, color: '#bbb' }, beginAtZero: true }
        }
      }
    });

    // ── Request status donut ──────────────────────────────────────
    const rCtx = document.getElementById('reqDonut').getContext('2d');
    new Chart(rCtx, {
      type: 'doughnut',
      data: {
        labels: ['Pending','Approved','Fulfilled','Rejected'],
        datasets: [{
          data: [{{ $reqPending }}, {{ $reqApproved }}, {{ $reqFulfilled }}, {{ $reqRejected }}],
          backgroundColor: ['#F39C12','#3498DB','#27AE60','#E74C3C'],
          borderWidth: 2,
          borderColor: '#fff',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        cutout: '68%',
      }
    });

    // ── Blood type donut ──────────────────────────────────────────
    const btCtx = document.getElementById('btChart').getContext('2d');
    @php
      $btCounts = $donations
        ->filter(fn($d) => $d->donor && $d->donor->bloodType)
        ->groupBy('donor.bloodType.type_name')
        ->map->count();
    @endphp
    const btData   = @json($btCounts);
    const btColors = ['#3498DB','#2980B9','#9B59B6','#8E44AD','#1ABC9C','#16A085','#E74C3C','#C0392B'];

    new Chart(btCtx, {
      type: 'doughnut',
      data: {
        labels: Object.keys(btData),
        datasets: [{
          data: Object.values(btData),
          backgroundColor: btColors.slice(0, Object.keys(btData).length),
          borderWidth: 2,
          borderColor: '#fff',
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'right', labels: { font: { size: 11 }, padding: 10 } } },
        cutout: '55%',
      }
    });
  </script>
  @endpush

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:repeat(4"]  { grid-template-columns: repeat(2,1fr) !important; }
      div[style*="grid-template-columns:1.6fr 1fr"] { grid-template-columns: 1fr !important; }
      div[style*="grid-template-columns:1fr 1.4fr"] { grid-template-columns: 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr"]   { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
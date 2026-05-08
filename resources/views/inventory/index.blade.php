<x-app-layout title="Blood Inventory">

@php
  $isAdmin     = auth()->user()->isAdmin() || auth()->user()->isStaff();
  $routePrefix = auth()->user()->isAdmin() ? 'admin' : (auth()->user()->isStaff() ? 'staff' : 'hospital');
  $dashRoute   = match($routePrefix) {
    'admin'  => route('admin.dashboard'),
    'staff'  => route('staff.dashboard'),
    default  => route('hospital.dashboard'),
  };
  $inventoryRoute = match($routePrefix) {
    'admin'  => route('admin.inventory.index'),
    'staff'  => route('staff.inventory.index'),
    default  => route('hospital.inventory.index'),
  };
@endphp

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ $dashRoute }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>Blood Inventory</span>
      </div>
      <h1 class="page-title">Blood Inventory</h1>
      <p class="page-subtitle">
        @if($isAdmin)
          Manage and monitor all available blood units.
        @else
          View current blood availability at Apo Life.
        @endif
      </p>
    </div>
  </div>

  {{-- ── Summary Stat Cards ─────────────────────────────── --}}
  <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:1.25rem; margin-bottom:1.25rem;">
    <x-stat-card
      title="Total Units"
      :value="$totalUnits"
      color="purple"
      icon="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
    <x-stat-card
      title="Available"
      :value="$availableUnits"
      color="green"
      icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card
      title="Reserved"
      :value="$reservedUnits"
      color="blue"
      icon="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
    <x-stat-card
      title="Used"
      :value="$usedUnits"
      color="gray"
      icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
    <x-stat-card
      title="Expired"
      :value="$expiredUnits"
      color="red"
      icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    </div>

  {{-- ── Blood Type Strip ─────────────────────────────────── --}}
  @php
    $btColors = [
      'A+' =>'#3498DB','A-' =>'#2980B9',
      'B+' =>'#9B59B6','B-' =>'#8E44AD',
      'AB+'=>'#1ABC9C','AB-'=>'#16A085',
      'O+' =>'#E74C3C','O-' =>'#C0392B',
    ];
    $levelColors = ['critical'=>'#E74C3C','low'=>'#E67E22','moderate'=>'#F39C12','good'=>'#27AE60'];
    $levelBg     = [
      'critical' => 'rgba(231,76,60,0.08)',
      'low'      => 'rgba(230,126,34,0.08)',
      'moderate' => 'rgba(243,156,18,0.08)',
      'good'     => 'rgba(39,174,96,0.08)',
    ];
  @endphp
  <div class="dash-card" style="margin-bottom:1.75rem;">
    <div class="dash-card-header">
      <h3 class="dash-card-title">Blood Type Availability</h3>
    </div>
    <div class="dash-card-body" style="padding:1rem 1.25rem;">
      <div style="display:grid; grid-template-columns:repeat(8,1fr); gap:0.65rem;">
        @foreach($bloodTypes as $bt)
          @php
            $count = $summary[$bt->type_name] ?? 0;
            $color = $btColors[$bt->type_name] ?? '#888';
            $level = $count === 0 ? 'critical' : ($count < 10 ? 'low' : ($count < 30 ? 'moderate' : 'good'));
          @endphp
          <div style="display:flex; flex-direction:column; align-items:center; gap:0.4rem; padding:0.75rem 0.5rem; border-radius:12px; background:{{ $color }}08; border:1.5px solid {{ $color }}22; transition:all 0.2s;"
            onmouseover="this.style.background='{{ $color }}15'; this.style.borderColor='{{ $color }}44'"
            onmouseout="this.style.background='{{ $color }}08'; this.style.borderColor='{{ $color }}22'">

            {{-- Blood type --}}
            <div style="font-family:var(--font-mono); font-size:0.9rem; font-weight:800; color:{{ $color }};">
              {{ $bt->type_name }}
            </div>

            {{-- Divider --}}
            <div style="width:24px; height:1.5px; background:{{ $color }}33; border-radius:2px;"></div>

            {{-- Count --}}
            <div style="font-size:1.3rem; font-weight:800; color:#1a1a2e; line-height:1;">
              {{ $count }}
            </div>

            {{-- Status dot + label --}}
            <div style="display:flex; align-items:center; gap:0.3rem;">
              <div style="width:6px; height:6px; border-radius:50%; background:{{ $levelColors[$level] }};"></div>
              <span style="font-size:0.6rem; font-weight:700; text-transform:uppercase; letter-spacing:0.05em; color:{{ $levelColors[$level] }};">
                {{ ucfirst($level) }}
              </span>
            </div>

          </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Expiring soon alert --}}
  @if($expiringSoon->count())
    <div class="alert alert-warning" style="margin-bottom:1.75rem;">
      <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
      <div>
        <div style="display:flex; align-items:center; flex-wrap:wrap; gap:0.5rem;">
  <strong>{{ $expiringSoon->count() }} unit(s) expiring within 7 days —</strong>
  @foreach($expiringSoon->groupBy('bloodType.type_name') as $type => $units)
    <span style="font-size:0.78rem; background:rgba(243,156,18,0.15); border:1px solid rgba(243,156,18,0.3); color:#C97A00; padding:0.2rem 0.6rem; border-radius:6px; font-weight:600;">
      {{ $type }}: {{ $units->count() }} unit(s)
    </span>
  @endforeach
</div>
      </div>
    </div>
  @endif

  {{-- Filter bar --}}
  <div class="dash-card" style="margin-bottom:1.5rem; padding:1rem 1.25rem;"
    x-data="{
      blood_type: '{{ request('blood_type', '') }}',
      status: '{{ request('status', '') }}',
      updateResults() {
        const url = new URL(window.location.href);
        url.searchParams.set('blood_type', this.blood_type);
        url.searchParams.set('status', this.status);
        url.searchParams.delete('page');
        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(r => r.text())
          .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            document.getElementById('inventory-table-wrapper').innerHTML =
              doc.getElementById('inventory-table-wrapper').innerHTML;
            window.history.replaceState({}, '', url.toString());
          });
      }
    }">
    <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">

      {{-- Blood Type --}}
      <select x-model="blood_type" @change="updateResults()"
        class="form-input form-input-light" style="min-width:150px; max-width:180px;">
        <option value="">All Blood Types</option>
        @foreach($bloodTypes as $bt)
          <option value="{{ $bt->blood_type_id }}" {{ request('blood_type') == $bt->blood_type_id ? 'selected' : '' }}>
            {{ $bt->type_name }}
          </option>
        @endforeach
      </select>

      {{-- Status --}}
      <select x-model="status" @change="updateResults()"
        class="form-input form-input-light" style="min-width:150px; max-width:180px;">
        <option value="">All Status</option>
        <option value="available">Available</option>
        <option value="reserved">Reserved</option>
        <option value="used">Used</option>
        <option value="expired">Expired</option>
      </select>

    </div>
  </div>

  {{-- Inventory table --}}
  <div id="inventory-table-wrapper">
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          All Blood Units
          <span style="font-size:0.75rem; font-weight:400; color:#bbb; margin-left:0.4rem;">({{ $inventory->total() }} total)</span>
        </h3>
      </div>
      @if($inventory->isEmpty())
        <div class="dash-card-body">
          <x-empty-state title="No blood units found" message="Try adjusting your filters."/>
        </div>
      @else
        <div class="table-container" style="border:none; border-radius:0;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Unit ID</th>
                <th>Blood Type</th>
                <th>Date Stored</th>
                <th>Expiry Date</th>
                <th>Age (days)</th>
                <th>Status</th>
                @if($isAdmin)
                  <th>Donation</th>
                @endif
              </tr>
            </thead>
            <tbody>
              @foreach($inventory as $unit)
                @php
                  $daysToExpiry    = (int) round(now()->startOfDay()->diffInDays($unit->expiry_date->startOfDay(), false));
                  $isExpiring      = $daysToExpiry <= 7 && $daysToExpiry >= 0;
                  $isExpired       = $daysToExpiry < 0;
                  $daysSinceStored = (int) round($unit->stored_date->startOfDay()->diffInDays(now()->startOfDay()));
                @endphp
                <tr style="{{ $isExpiring ? 'background:rgba(243,156,18,0.04);' : '' }}{{ $isExpired ? 'background:rgba(192,57,43,0.03);' : '' }}">
                  <td>
                    <span style="font-family:var(--font-mono); font-size:0.8rem; color:#666; background:#f5f5f5; padding:0.2rem 0.55rem; border-radius:6px;">
                      #{{ (int) $unit->blood_unit_id }}
                    </span>
                  </td>
                  <td><x-blood-type-badge :type="$unit->bloodType->type_name"/></td>
                  <td style="font-size:0.82rem; color:#888;">{{ $unit->stored_date->format('M d, Y') }}</td>
                  <td style="font-size:0.82rem;">
                    <span style="color:{{ $isExpired ? '#E74C3C' : ($isExpiring ? '#E67E22' : '#555') }}; font-weight:{{ $isExpiring || $isExpired ? '600' : '400' }};">
                      {{ $unit->expiry_date->format('M d, Y') }}
                    </span>
                    @if($isExpiring && !$isExpired)
                      <div style="font-size:0.68rem; color:#E67E22; font-weight:600; margin-top:1px;">Expires in {{ $daysToExpiry }}d</div>
                    @elseif($isExpired)
                      <div style="font-size:0.68rem; color:#E74C3C; font-weight:600; margin-top:1px;">Expired {{ abs($daysToExpiry) }}d ago</div>
                    @endif
                  </td>
                  <td style="font-size:0.82rem; color:#888;">{{ $daysSinceStored }}d</td>
                  <td><x-status-badge :status="$unit->status->value" size="sm"/></td>
                  @if($isAdmin)
                    <td>
                      @if($unit->donation)
                        <span style="font-size:0.78rem; font-family:var(--font-mono); color:#888;">
                          #{{ (int) $unit->donation->donation_id }}
                        </span>
                      @else
                        <span style="color:#ddd;">—</span>
                      @endif
                    </td>
                  @endif
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="dash-card-footer">
          <x-pagination :paginator="$inventory"/>
        </div>
      @endif
    </div>
  </div>

  <style>
    @media (max-width: 1024px) {
      div[style*="grid-template-columns:repeat(8,1fr)"] { grid-template-columns: repeat(4,1fr) !important; }
      div[style*="grid-template-columns:repeat(4,1fr)"] { grid-template-columns: repeat(2,1fr) !important; }
    }
    @media (max-width: 480px) {
      div[style*="grid-template-columns:repeat(4,1fr)"] { grid-template-columns: repeat(2,1fr) !important; }
    }
  </style>

</x-app-layout>
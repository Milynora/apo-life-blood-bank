<x-app-layout title="Donations">

@php
  $isStaff     = auth()->user()->isStaff();
  $routePrefix = $isStaff ? 'staff' : 'admin';
  $dashRoute   = $isStaff ? route('staff.dashboard') : route('admin.dashboard');
@endphp

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ $dashRoute }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span><span>Donations</span>
      </div>
      <h1 class="page-title">Donations</h1>
      <p class="page-subtitle">All recorded blood donations.</p>
    </div>
    <a href="{{ route($routePrefix . '.donations.create') }}" class="btn btn-dash-primary">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Record Donation
    </a>
  </div>

  <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; margin-bottom:1.75rem;">
    <x-stat-card title="Total Donations" :value="$stats['total']"      color="blue"   icon="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
    <x-stat-card title="Successful"      :value="$stats['successful']" color="navy"   icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Failed"          :value="$stats['failed']"     color="yellow" icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
</div>

  {{-- Filter bar --}}
  <div class="dash-card" style="margin-bottom:1.5rem; padding:1rem 1.25rem;"
    x-data="{
      search: '{{ request('search', '') }}',
      status: '{{ request('status', '') }}',
      date: '{{ request('date', '') }}',
      loading: false,
      updateResults() {
        this.loading = true;
        const url = new URL(window.location.href);
        url.searchParams.set('search', this.search);
        url.searchParams.set('status', this.status);
        url.searchParams.set('date', this.date);
        url.searchParams.delete('page');
        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(r => r.text())
          .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            document.getElementById('donations-table-wrapper').innerHTML =
              doc.getElementById('donations-table-wrapper').innerHTML;
            window.history.replaceState({}, '', url.toString());
            this.loading = false;
          });
      }
    }">
    <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">

      {{-- Search --}}
      <div style="position:relative; flex:2; min-width:220px;">
        <span style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); display:flex; align-items:center; pointer-events:none;">
          <svg x-show="!loading" width="15" height="15" fill="none" stroke="#bbb" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <svg x-show="loading" width="15" height="15" fill="none" stroke="#C0392B" stroke-width="2" viewBox="0 0 24 24"
            style="animation:donations-spin 0.7s linear infinite;">
            <path stroke-linecap="round" d="M4 12a8 8 0 018-8"/>
          </svg>
        </span>
        <input type="text" x-model="search"
          class="form-input form-input-light" style="padding-left:2.6rem; width:100%;"
          placeholder="Search donor name…"
          @input.debounce.350ms="updateResults()"/>
      </div>

      {{-- Status --}}
      <select x-model="status" @change="updateResults()"
        class="form-input form-input-light" style="min-width:150px; max-width:180px;">
        <option value="">All Status</option>
        <option value="successful">Successful</option>
        <option value="failed">Failed</option>
      </select>

      {{-- Date --}}
      <input type="date" x-model="date" @change="updateResults()"
        class="form-input form-input-light" style="min-width:160px; max-width:190px;"/>

    </div>
  </div>

  <style>
    @keyframes donations-spin { to { transform: rotate(360deg); } }
  </style>

  {{-- Table --}}
  <div id="donations-table-wrapper">
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          All Donations
          <span style="font-size:0.75rem; font-weight:400; color:#bbb; margin-left:0.4rem;">({{ $donations->total() }})</span>
        </h3>
      </div>
      @if($donations->isEmpty())
        <div class="dash-card-body">
          <x-empty-state title="No donations found" message="Adjust your filters or record a new donation."/>
        </div>
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
                <th style="text-align:right;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($donations as $d)
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
                  <td style="font-weight:700;">{{ $d->volume }} <span style="font-size:0.72rem; color:#aaa;">mL</span></td>
                  <td style="font-size:0.82rem; color:#888; white-space:nowrap;">{{ $d->donation_date->format('M d, Y') }}</td>
                  <td><x-status-badge :status="$d->status->value" size="sm"/></td>
                  <td style="font-size:0.82rem; color:#888;">{{ $d->staff->name ?? '—' }}</td>
                  <td>
                    <div style="display:flex; justify-content:flex-end;">
                      <a href="{{ route($routePrefix . '.donations.show', $d) }}"
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
        <div class="dash-card-footer"><x-pagination :paginator="$donations"/></div>
      @endif
    </div>
  </div>

</x-app-layout>
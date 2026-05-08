<x-app-layout title="Screenings">

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
        <span>Screenings</span>
      </div>
      <h1 class="page-title">Screenings</h1>
      <p class="page-subtitle">Pre-donation health screening records.</p>
    </div>
    <a href="{{ route($routePrefix . '.screenings.create') }}" class="btn btn-dash-primary">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Record Screening
    </a>
  </div>

  <div style="display:grid; grid-template-columns:repeat(3,1fr); gap:1.25rem; margin-bottom:1.75rem;">
    <x-stat-card title="Total Screenings" :value="$stats['total']" color="purple" icon="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
    <x-stat-card title="Fit"              :value="$stats['fit']"   color="green"  icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Unfit"            :value="$stats['unfit']" color="red"    icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
</div>

  {{-- Filter bar --}}
  <div class="dash-card" style="margin-bottom:1.5rem; padding:1rem 1.25rem;"
    x-data="{
      eligibility: '{{ request('eligibility', '') }}',
      date: '{{ request('date', '') }}',
      updateResults() {
        const url = new URL(window.location.href);
        url.searchParams.set('eligibility', this.eligibility);
        url.searchParams.set('date', this.date);
        url.searchParams.delete('page');
        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(r => r.text())
          .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            document.getElementById('screenings-table-wrapper').innerHTML =
              doc.getElementById('screenings-table-wrapper').innerHTML;
            window.history.replaceState({}, '', url.toString());
          });
      }
    }">
    <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">

      {{-- Eligibility --}}
      <select x-model="eligibility" @change="updateResults()"
        class="form-input form-input-light" style="min-width:150px; max-width:200px;">
        <option value="">All Eligibility</option>
        <option value="fit">Fit</option>
        <option value="unfit">Unfit</option>
      </select>

      {{-- Date --}}
      <input type="date" x-model="date" @change="updateResults()"
        class="form-input form-input-light" style="min-width:160px; max-width:190px;"/>

    </div>
  </div>

  {{-- Table --}}
  <div id="screenings-table-wrapper">
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          All Screenings
          <span style="font-size:0.75rem; font-weight:400; color:#bbb; margin-left:0.4rem;">({{ $screenings->total() }})</span>
        </h3>
      </div>

      @if($screenings->isEmpty())
        <div class="dash-card-body"><x-empty-state title="No screenings found"/></div>
      @else
        <div class="table-container" style="border:none; border-radius:0;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Donor</th>
                <th>Date</th>
                <th>BP</th>
                <th>Hemoglobin</th>
                <th>Weight</th>
                <th>Eligibility</th>
                <th>Staff</th>
                <th style="text-align:right;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($screenings as $s)
                <tr>
                  <td>
                    <div style="font-weight:600; font-size:0.875rem; color:#1a1a2e;">{{ $s->donor->name }}</div>
                    <div style="font-size:0.75rem; color:#999;">{{ $s->donor->user->email }}</div>
                  </td>
                  <td style="font-size:0.82rem; color:#888; white-space:nowrap;">
                    {{ optional($s->screening_date)->format('M d, Y') ?? 'N/A' }}
                  </td>
                  <td style="font-size:0.875rem;">{{ $s->blood_pressure ?? '—' }}</td>
                  <td style="font-size:0.875rem;">{{ $s->hemoglobin_level ? $s->hemoglobin_level . ' g/dL' : '—' }}</td>
                  <td style="font-size:0.875rem;">{{ $s->weight ? $s->weight . ' kg' : '—' }}</td>
                  <td><x-status-badge :status="$s->eligibility_status->value" size="sm"/></td>
                  <td style="font-size:0.82rem; color:#888;">{{ $s->staff->name ?? '—' }}</td>
                  <td>
                    <div style="display:flex; justify-content:flex-end;">
                      <a href="{{ route($routePrefix . '.screenings.show', $s) }}"
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
        <div class="dash-card-footer"><x-pagination :paginator="$screenings"/></div>
      @endif
    </div>
  </div>

</x-app-layout>
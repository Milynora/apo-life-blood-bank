<x-app-layout title="Blood Requests">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('hospital.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>Blood Requests</span>
      </div>
      <h1 class="page-title">Blood Requests</h1>
      <p class="page-subtitle">Track all blood requests submitted by your hospital.</p>
    </div>
    <a href="{{ route('hospital.requests.create') }}" class="btn btn-dash-primary">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      New Request
    </a>
  </div>

  {{-- Summary stat cards --}}
  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:1.75rem;">
    @foreach([
      ['All',       $requests->total(),                                                         'pink',  'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
      ['Pending',   auth()->user()->hospital->requests()->where('status','pending')->count(),   'orange','M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
      ['Approved',  auth()->user()->hospital->requests()->where('status','approved')->count(),  'teal',  'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
      ['Fulfilled', auth()->user()->hospital->requests()->where('status','fulfilled')->count(), 'navy',  'M5 13l4 4L19 7'],
    ] as [$label, $count, $color, $icon])
      <x-stat-card :title="$label" :value="$count" :color="$color" :icon="$icon"/>
    @endforeach
  </div>

  {{-- Filter --}}
  <div class="dash-card" style="margin-bottom:1.5rem; padding:1rem 1.25rem;"
    x-data="{
      status: '{{ request('status', '') }}',
      blood_type: '{{ request('blood_type', '') }}',
      date: '{{ request('date', '') }}',
      loading: false,
      updateResults() {
        this.loading = true;
        const url = new URL(window.location.href);
        url.searchParams.set('status', this.status);
        url.searchParams.set('blood_type', this.blood_type);
        url.searchParams.set('date', this.date);
        url.searchParams.delete('page');
        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(r => r.text())
          .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            document.getElementById('requests-table-wrapper').innerHTML =
              doc.getElementById('requests-table-wrapper').innerHTML;
            window.history.replaceState({}, '', url.toString());
            this.loading = false;
          });
      }
    }">
    <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">

      {{-- Status --}}
      <select x-model="status" @change="updateResults()"
        class="form-input form-input-light" style="min-width:150px; max-width:180px;">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="approved">Approved</option>
        <option value="rejected">Rejected</option>
        <option value="cancelled">Cancelled</option>
        <option value="partially_fulfilled">Partially Fulfilled</option>
        <option value="fulfilled">Fulfilled</option>
      </select>

      {{-- Blood Type --}}
      <select x-model="blood_type" @change="updateResults()"
        class="form-input form-input-light" style="min-width:130px; max-width:155px;">
        <option value="">All Blood Types</option>
        @foreach(\App\Models\BloodType::orderBy('type_name')->get() as $bt)
          <option value="{{ $bt->blood_type_id }}" {{ request('blood_type') == $bt->blood_type_id ? 'selected' : '' }}>
            {{ $bt->type_name }}
          </option>
        @endforeach
      </select>

      {{-- Date --}}
      <input type="date" x-model="date" @change="updateResults()"
        class="form-input form-input-light" style="min-width:160px; max-width:190px;"/>

    </div>
  </div>

  {{-- Requests table --}}
  <div id="requests-table-wrapper">
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          All Requests
          <span style="font-size:0.75rem; font-weight:400; color:#bbb; margin-left:0.4rem;">({{ $requests->total() }})</span>
        </h3>
      </div>

      @if($requests->isEmpty())
        <div class="dash-card-body">
          <x-empty-state
            title="No requests found"
            message="Submit a blood request when your hospital needs blood supply."
            :action="route('hospital.requests.create')"
            action-label="Submit Request"/>
        </div>
      @else
        <div class="table-container" style="border:none; border-radius:0;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Request #</th>
                <th>Blood Type</th>
                <th>Qty</th>
                <th>Fulfilled</th>
                <th>Urgency</th>
                <th>Method</th>
                <th>Date</th>
                <th>Status</th>
                <th style="text-align:right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($requests as $req)
                @php
                  $urgencyColors = ['routine'=>'#27AE60','urgent'=>'#F39C12','emergency'=>'#E74C3C'];
                  $urgencyColor  = $urgencyColors[$req->urgency ?? 'routine'] ?? '#888';
                @endphp
                <tr>
                  <td>
                    <span style="font-family:var(--font-mono); font-size:0.8rem; color:#888; background:#f5f5f5; padding:0.2rem 0.55rem; border-radius:6px;">
                      #{{ str_pad($req->request_id, 1, '0', STR_PAD_LEFT) }}
                    </span>
                  </td>
                  <td><x-blood-type-badge :type="$req->bloodType->type_name"/></td>
                  <td style="font-weight:700; font-size:0.95rem;">{{ $req->quantity }}</td>
                  <td>
                    @php $filled = $req->bloodUnits->count(); @endphp
                    <span style="font-weight:600; color:{{ $filled >= $req->quantity ? '#27AE60' : '#888' }};">
                      {{ $filled }}/{{ $req->quantity }}
                    </span>
                  </td>
                  <td>
                    <span style="font-size:0.75rem; font-weight:600; color:{{ $urgencyColor }}; background:{{ $urgencyColor }}15; border:1px solid {{ $urgencyColor }}33; padding:0.2rem 0.55rem; border-radius:6px; text-transform:capitalize;">
                      {{ ucfirst($req->urgency ?? 'routine') }}
                    </span>
                  </td>
                  <td style="font-size:0.78rem; color:#888; text-transform:capitalize;">
                    {{ $req->fulfillment_type ?? 'pickup' }}
                  </td>
                  <td style="font-size:0.82rem; color:#888; white-space:nowrap;">{{ $req->request_date->format('M d, Y') }}</td>
                  <td><x-status-badge :status="$req->status->value" size="sm"/></td>
                  <td>
                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.4rem;" x-data>

                      {{-- View --}}
                      <a href="{{ route('hospital.requests.show', $req->request_id) }}"
                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(41,128,185,0.3); background:rgba(41,128,185,0.08); color:#2980B9; text-decoration:none; transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(41,128,185,0.18)'"
                        onmouseout="this.style.background='rgba(41,128,185,0.08)'"
                        title="View">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </a>

                      {{-- Edit: pending only --}}
                      @if($req->status->value === 'pending')
                        <a href="{{ route('hospital.requests.edit', $req->request_id) }}"
                          style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(142,68,173,0.3); background:rgba(142,68,173,0.08); color:#8E44AD; text-decoration:none; transition:all 0.2s;"
                          onmouseover="this.style.background='rgba(142,68,173,0.18)'"
                          onmouseout="this.style.background='rgba(142,68,173,0.08)'"
                          title="Edit">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 20h9"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4 12.5-12.5z"/>
                          </svg>
                        </a>
                      @endif

                      {{-- Cancel: pending only --}}
                      @if($req->status->value === 'pending')
                        <button type="button"
                          @click="$dispatch('open-modal', 'cancel-req-{{ $req->request_id }}')"
                          style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(192,57,43,0.3); background:rgba(192,57,43,0.08); color:#E74C3C; cursor:pointer; transition:all 0.2s;"
                          onmouseover="this.style.background='rgba(192,57,43,0.18)'"
                          onmouseout="this.style.background='rgba(192,57,43,0.08)'"
                          title="Cancel">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>

                        <x-confirm-dialog
                          id="cancel-req-{{ $req->request_id }}"
                          title="Cancel Blood Request?"
                          message="Cancel request #{{ str_pad($req->request_id, 1, '0', STR_PAD_LEFT) }} for {{ $req->bloodType->type_name }} ({{ $req->quantity }} unit(s))? This cannot be undone."
                          confirm-label="Yes, Cancel It"
                          confirm-class="btn-danger"
                          action="{{ route('hospital.requests.cancel', $req->request_id) }}"
                          method="PATCH"/>
                      @endif

                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="dash-card-footer">
          <x-pagination :paginator="$requests"/>
        </div>
      @endif
    </div>
  </div>

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:repeat(4"] { grid-template-columns: repeat(2,1fr) !important; }
    }
  </style>

</x-app-layout>
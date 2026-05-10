<x-app-layout title="Blood Requests">

@php
  $isStaff     = auth()->user()->role->value === 'staff';
  $routePrefix = $isStaff ? 'staff' : 'admin';
  $dashRoute   = $isStaff ? route('staff.dashboard') : route('admin.dashboard');
@endphp

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ $dashRoute }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>Blood Requests</span>
      </div>
      <h1 class="page-title">Blood Requests</h1>
      <p class="page-subtitle">Review, approve, reject, and fulfill hospital blood requests.</p>
    </div>
  </div>

  {{-- Stat cards --}}
  <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:1.25rem; margin-bottom:1.75rem;">
    @foreach([
      ['Pending',             \App\Models\BloodRequest::where('status','pending')->count(),             'yellow', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
      ['Approved',            \App\Models\BloodRequest::where('status','approved')->count(),            'blue',   'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
      ['Partially Fulfilled', \App\Models\BloodRequest::where('status','partially_fulfilled')->count(), 'purple', 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
      ['Fulfilled',           \App\Models\BloodRequest::where('status','fulfilled')->count(),           'green',  'M5 13l4 4L19 7'],
      ['Rejected',            \App\Models\BloodRequest::where('status','rejected')->count(),            'red',    'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
    ] as [$label, $count, $color, $icon])
      <x-stat-card :title="$label" :value="$count" :color="$color" :icon="$icon"/>
    @endforeach
  </div>

  {{-- Filter bar --}}
<div class="dash-card" style="margin-bottom:1.5rem; padding:1rem 1.25rem;"
  x-data="{
    search: '{{ request('search', '') }}',
    status: '{{ request('status', '') }}',
    blood_type: '{{ request('blood_type', '') }}',
    loading: false,
    updateResults() {
      this.loading = true;
      const url = new URL(window.location.href);
      url.searchParams.set('search', this.search);
      url.searchParams.set('status', this.status);
      url.searchParams.set('blood_type', this.blood_type);
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

    {{-- Search --}}
    <div style="position:relative; flex:2; min-width:220px;">
      <span style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); display:flex; align-items:center; pointer-events:none;">
        <svg x-show="!loading" width="15" height="15" fill="none" stroke="#bbb" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
        </svg>
        <svg x-show="loading" width="15" height="15" fill="none" stroke="#C0392B" stroke-width="2" viewBox="0 0 24 24"
          style="animation:requests-spin 0.7s linear infinite;">
          <path stroke-linecap="round" d="M4 12a8 8 0 018-8"/>
        </svg>
      </span>
      <input type="text" x-model="search"
        class="form-input form-input-light" style="padding-left:2.6rem; width:100%;"
        placeholder="Search hospital name…"
        @input.debounce.350ms="updateResults()"/>
    </div>

    {{-- Status --}}
    <select x-model="status" @change="updateResults()"
      class="form-input form-input-light" style="min-width:160px; max-width:200px;">
      <option value="">All Status</option>
      <option value="pending">Pending</option>
      <option value="approved">Approved</option>
      <option value="partially_fulfilled">Partially Fulfilled</option>
      <option value="fulfilled">Fulfilled</option>
      <option value="rejected">Rejected</option>
    </select>

    {{-- Blood Type --}}
    <select x-model="blood_type" @change="updateResults()"
      class="form-input form-input-light" style="min-width:150px; max-width:180px;">
      <option value="">All Blood Types</option>
      @foreach(\App\Models\BloodType::all() as $bt)
        <option value="{{ $bt->blood_type_id }}" {{ request('blood_type') == $bt->blood_type_id ? 'selected' : '' }}>
          {{ $bt->type_name }}
        </option>
      @endforeach
    </select>

  </div>
</div>

<style>
  @keyframes requests-spin { to { transform: rotate(360deg); } }
</style>

  {{-- Table --}}
  <div id="requests-table-wrapper">
    <div class="dash-card" x-data>
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          All Requests
          <span style="font-size:0.78rem; font-weight:400; color:#999; margin-left:0.5rem;">({{ $requests->total() }} total)</span>
        </h3>
      </div>

      @if($requests->isEmpty())
        <div class="dash-card-body">
          <x-empty-state title="No blood requests found" message="Requests from hospitals will appear here."/>
        </div>
      @else
        <div class="table-container" style="border:none; border-radius:0;">
          <table class="data-table">
            <thead>
              <tr>
                <th>Hospital</th>
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
                  $canFulfill    = in_array($req->status->value, ['approved','partially_fulfilled']) && $req->remaining > 0;
                @endphp
                <tr>
                  <td>
                    <div style="font-weight:600; font-size:0.875rem; color:#1a1a2e;">{{ Str::limit($req->hospital->hospital_name, 24) }}</div>
                    <div style="font-size:0.75rem; color:#999;">{{ $req->hospital->user->email }}</div>
                  </td>
                  <td><x-blood-type-badge :type="$req->bloodType->type_name"/></td>
                  <td style="font-weight:700; font-size:0.95rem; color:#1a1a2e;">{{ $req->quantity }}</td>
                  <td>
                    @php $fulfilled = $req->bloodUnits->count(); @endphp
                    <div style="display:flex; align-items:center; gap:0.6rem;">
                      <div style="flex:1; height:6px; background:#f0f0f0; border-radius:3px; min-width:50px; overflow:hidden;">
                        <div style="height:100%; border-radius:3px; background:{{ $fulfilled >= $req->quantity ? '#27AE60' : '#3498DB' }}; width:{{ $req->quantity > 0 ? min(100, round(($fulfilled/$req->quantity)*100)) : 0 }}%;"></div>
                      </div>
                      <span style="font-size:0.8rem; font-weight:600; color:#333; white-space:nowrap;">{{ $fulfilled }}/{{ $req->quantity }}</span>
                    </div>
                  </td>
                  <td>
                    <span style="font-size:0.72rem; font-weight:600; color:{{ $urgencyColor }}; background:{{ $urgencyColor }}15; border:1px solid {{ $urgencyColor }}33; padding:0.18rem 0.5rem; border-radius:6px; text-transform:capitalize; white-space:nowrap;">
                      {{ ucfirst($req->urgency ?? 'routine') }}
                    </span>
                  </td>
                  <td style="font-size:0.78rem; color:#888; text-transform:capitalize; white-space:nowrap;">
                    {{ $req->fulfillment_type ?? 'pickup' }}
                  </td>
                  <td style="font-size:0.82rem; color:#888; white-space:nowrap;">{{ $req->request_date->format('M d, Y') }}</td>
                  <td><x-status-badge :status="$req->status->value" size="sm"/></td>
                  <td>
                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.4rem;">

                      {{-- View --}}
                      <a href="{{ route($routePrefix . '.blood-requests.show', $req) }}"
                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(41,128,185,0.3); background:rgba(41,128,185,0.08); color:#2980B9; text-decoration:none; transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(41,128,185,0.18)'"
                        onmouseout="this.style.background='rgba(41,128,185,0.08)'"
                        title="View">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                          <path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </a>

                      {{-- Approve --}}
                      @if($req->status->value === 'pending')
                        <button type="button"
                          @click="$dispatch('open-modal', 'approve-{{ $req->request_id }}')"
                          style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(39,174,96,0.3); background:rgba(39,174,96,0.08); color:#27AE60; cursor:pointer; transition:all 0.2s;"
                          onmouseover="this.style.background='rgba(39,174,96,0.18)'"
                          onmouseout="this.style.background='rgba(39,174,96,0.08)'"
                          title="Approve">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                        </button>

                        {{-- Reject --}}
                        <button type="button"
                          @click="$dispatch('open-modal', 'reject-{{ $req->request_id }}')"
                          style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(192,57,43,0.3); background:rgba(192,57,43,0.08); color:#E74C3C; cursor:pointer; transition:all 0.2s;"
                          onmouseover="this.style.background='rgba(192,57,43,0.18)'"
                          onmouseout="this.style.background='rgba(192,57,43,0.08)'"
                          title="Reject">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                      @endif

                      {{-- Fulfill --}}
                      @if($canFulfill)
                        <button type="button"
                          @click="$dispatch('open-modal', 'fulfill-{{ $req->request_id }}')"
                          style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(83,52,131,0.3); background:rgba(83,52,131,0.08); color:#9B59B6; cursor:pointer; transition:all 0.2s;"
                          onmouseover="this.style.background='rgba(83,52,131,0.18)'"
                          onmouseout="this.style.background='rgba(83,52,131,0.08)'"
                          title="Fulfill ({{ $req->remaining }} remaining)">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </button>

                        <x-confirm-dialog
                          id="fulfill-{{ $req->request_id }}"
                          title="Confirm Fulfillment?"
                          message="Allocate available {{ $req->bloodType->type_name }} units to {{ $req->hospital->hospital_name }}. {{ $req->remaining }} unit(s) still needed."
                          confirm-label="Confirm Fulfillment"
                          confirm-class="btn-success"
                          action="{{ route($routePrefix . '.blood-requests.fulfill', $req) }}"
                          method="PATCH"/>
                      @endif

                    </div>

                    {{-- Modals --}}
                    @if($req->status->value === 'pending')
                      <x-modal id="approve-{{ $req->request_id }}" title="Approve Blood Request" size="sm">
                        <form method="POST" action="{{ route($routePrefix . '.blood-requests.approve', $req) }}">
                          @csrf @method('PATCH')
                          <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
                            Approving <strong>{{ $req->quantity }} unit(s)</strong> of
                            <strong>{{ $req->bloodType->type_name }}</strong> for
                            <strong>{{ $req->hospital->hospital_name }}</strong>.
                          </p>
                          <div class="form-group">
                            <label class="form-label">Remarks <span style="font-weight:400; color:#bbb;">(optional)</span></label>
                            <textarea name="remarks" class="form-input form-input-light" rows="2" placeholder="Optional notes…"></textarea>
                          </div>
                          <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                            <button type="button" onclick="closeModal()"
                              style="padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body);">
                              Cancel
                            </button>
                            <button type="submit" class="btn btn-success btn-sm" style="border-radius:10px;">Approve</button>
                          </div>
                        </form>
                      </x-modal>

                      <x-modal id="reject-{{ $req->request_id }}" title="Reject Blood Request" size="sm">
                        <form method="POST" action="{{ route($routePrefix . '.blood-requests.reject', $req) }}">
                          @csrf @method('PATCH')
                          <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
                            Rejecting request from <strong>{{ $req->hospital->hospital_name }}</strong>.
                          </p>
                          <div class="form-group">
                            <label class="form-label">Reason <span style="color:#E74C3C;">*</span></label>
                            <textarea name="remarks" class="form-input form-input-light" rows="2"
                              placeholder="Reason for rejection…" required></textarea>
                          </div>
                          <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
                            <button type="button" onclick="closeModal()"
                              style="padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body);">
                              Cancel
                            </button>
                            <button type="submit" class="btn btn-danger btn-sm" style="border-radius:10px;">Reject</button>
                          </div>
                        </form>
                      </x-modal>
                    @endif

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
    @media (max-width: 1100px) {
      div[style*="grid-template-columns:repeat(5"] { grid-template-columns: repeat(3,1fr) !important; }
    }
    @media (max-width: 768px) {
      div[style*="grid-template-columns:repeat(5"] { grid-template-columns: repeat(2,1fr) !important; }
    }
  </style>

</x-app-layout>
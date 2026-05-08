<x-app-layout title="Blood Request Details">

@php
  $isStaff     = auth()->user()->role->value === 'staff';
  $routePrefix = $isStaff ? 'staff' : 'admin';
  $dashRoute   = $isStaff ? route('staff.dashboard') : route('admin.dashboard');
  $canFulfill  = in_array($bloodRequest->status->value, ['approved','partially_fulfilled'])
                  && $bloodRequest->remaining > 0;
  $urgencyColors = ['routine'=>'#27AE60','urgent'=>'#F39C12','emergency'=>'#E74C3C'];
  $urgencyColor  = $urgencyColors[$bloodRequest->urgency ?? 'routine'] ?? '#888';
@endphp

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ $dashRoute }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route($routePrefix . '.blood-requests.index') }}">Blood Requests</a>
        <span class="breadcrumb-sep">›</span>
        <span>Request #{{ str_pad($bloodRequest->request_id, 1, '0', STR_PAD_LEFT) }}</span>
      </div>
      <h1 class="page-title">Request Details</h1>
    </div>
    <a href="{{ route($routePrefix . '.blood-requests.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1fr 1.4fr; gap:1.5rem;" x-data>

    {{-- LEFT --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

      {{-- Request summary --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Request Summary</h3>
          <x-status-badge :status="$bloodRequest->status->value"/>
        </div>
        <div class="dash-card-body">

          {{-- Details --}}
          @foreach([
            ['Blood Type',   $bloodRequest->bloodType->type_name],
            ['Hospital',         $bloodRequest->hospital->hospital_name],
            ['Contact',          $bloodRequest->hospital->contact_number ?? '—'],
            ['Quantity Needed',  $bloodRequest->quantity . ' unit(s)'],
            ['Units Allocated',  $bloodRequest->fulfilled_count . ' unit(s)'],
            ['Remaining',        $bloodRequest->remaining . ' unit(s)'],
            ['Request Date',     $bloodRequest->request_date->format('F d, Y')],
            ['Needed By', $bloodRequest->needed_by ? $bloodRequest->needed_by->format('F d, Y') : '—'],
          ] as [$label, $value])
            <div style="display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
              <span style="font-size:0.82rem; color:#888;">{{ $label }}</span>
              <span style="font-size:0.85rem; color:{{ $label === 'Blood Type' ? 'var(--primary)' : '#1a1a2e' }}; font-weight:{{ $label === 'Blood Type' ? '800' : '600' }}; font-family:{{ $label === 'Blood Type' ? 'var(--font-mono)' : 'inherit' }};">
                {{ $value }}
              </span>
            </div>
          @endforeach
          
          {{-- Urgency --}}
          <div style="display:flex; justify-content:space-between; align-items:center; padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <span style="font-size:0.82rem; color:#888; font-weight:500;">Urgency</span>
            <span style="font-size:0.75rem; font-weight:700; color:{{ $urgencyColor }}; background:{{ $urgencyColor }}15; border:1px solid {{ $urgencyColor }}33; padding:0.2rem 0.6rem; border-radius:6px; text-transform:capitalize;">
              {{ ucfirst($bloodRequest->urgency ?? 'routine') }}
            </span>
          </div>

          {{-- Fulfillment method --}}
          <div style="display:flex; justify-content:space-between; align-items:center; padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <span style="font-size:0.82rem; color:#888; font-weight:500;">Fulfillment Method</span>
            <span style="font-size:0.82rem; color:#1a1a2e; font-weight:600; display:flex; align-items:center; gap:0.4rem;">
              @if(($bloodRequest->fulfillment_type ?? 'pickup') === 'delivery')
                <svg width="13" height="13" fill="none" stroke="#27AE60" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/></svg>
                Delivery
              @else
                <svg width="13" height="13" fill="none" stroke="#2980B9" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5"/></svg>
                Pickup
              @endif
            </span>
          </div>

          {{-- Progress bar --}}
          @if($bloodRequest->quantity > 0)
            @php $pct = min(100, round(($bloodRequest->fulfilled_count / $bloodRequest->quantity) * 100)); @endphp
            <div style="margin-top:1.25rem;">
              <div style="display:flex; justify-content:space-between; font-size:0.78rem; color:#888; margin-bottom:0.5rem;">
                <span>Fulfillment Progress</span>
                <span style="font-weight:600; color:#1a1a2e;">{{ $pct }}%</span>
              </div>
              <div style="height:8px; background:#f0f0f0; border-radius:4px; overflow:hidden;">
                <div style="height:100%; border-radius:4px; background:{{ $pct >= 100 ? '#27AE60' : 'linear-gradient(90deg,#3498DB,#2980B9)' }}; width:{{ $pct }}%; transition:width 0.5s ease;"></div>
              </div>
              @if($bloodRequest->remaining > 0)
                <div style="font-size:0.72rem; color:#E67E22; margin-top:0.4rem; font-weight:600;">
                  {{ $bloodRequest->remaining }} unit(s) still needed
                </div>
              @else
                <div style="font-size:0.72rem; color:#27AE60; margin-top:0.4rem; font-weight:600;">
                  Fully fulfilled
                </div>
              @endif
            </div>
          @endif

          {{-- Remarks --}}
          @if($bloodRequest->remarks)
            <div style="margin-top:1.25rem; padding:0.85rem; background:#fafbff; border-radius:10px; border:1px solid var(--border-light);">
              <div style="font-size:0.72rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#bbb; margin-bottom:0.4rem;">Remarks</div>
              <p style="font-size:0.85rem; color:#555; line-height:1.6; margin:0;">{{ $bloodRequest->remarks }}</p>
            </div>
          @endif

        </div>

        {{-- Action footer --}}
        @if($bloodRequest->status->value === 'pending' || $canFulfill)
          <div class="dash-card-footer">
            <div style="display:flex; gap:0.75rem; flex-wrap:wrap;">

              {{-- Pending: Approve + Reject --}}
              @if($bloodRequest->status->value === 'pending')
                <button @click="$dispatch('open-modal', 'approve-req')"
                  class="btn btn-success btn-sm" style="flex:1; justify-content:center;">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                  Approve
                </button>
                <button @click="$dispatch('open-modal', 'reject-req')"
                  class="btn btn-danger btn-sm" style="flex:1; justify-content:center;">
                  <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                  Reject
                </button>
              @endif

              {{-- Approved or Partially Fulfilled: Fulfill --}}
              @if($canFulfill)
  <button type="button"
    @click="$dispatch('open-modal', 'fulfill-{{ $bloodRequest->request_id }}')"
    class="btn btn-sm"
    style="flex:1; width:100%; justify-content:center; background:linear-gradient(135deg,#533483,#9B59B6); color:#fff; box-shadow:0 3px 10px rgba(83,52,131,0.3); border:none;">
    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
    </svg>
    Fulfill ({{ $bloodRequest->remaining }} remaining)
  </button>

  <x-confirm-dialog
    id="fulfill-{{ $bloodRequest->request_id }}"
    title="Confirm Fulfillment?"
    message="Allocate available {{ $bloodRequest->bloodType->type_name }} units to {{ $bloodRequest->hospital->hospital_name }}. {{ $bloodRequest->remaining }} unit(s) still needed."
    confirm-label="Confirm Fulfillment"
    confirm-class="btn-success"
    action="{{ route($routePrefix . '.blood-requests.fulfill', $bloodRequest) }}"
    method="PATCH"/>
@endif

            </div>
          </div>
        @endif
      </div>

    </div>

    {{-- RIGHT COLUMN --}}
<div style="display:flex; flex-direction:column; gap:1.5rem; align-self:start;">

  {{-- Hospital info --}}
  <div class="dash-card">
    <div class="dash-card-header"><h3 class="dash-card-title">Hospital Information</h3></div>
    <div class="dash-card-body">
      <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1rem;">
        <div class="icon-box icon-box-purple">
          <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        </div>
        <div>
          <div style="font-weight:700; font-size:0.95rem; color:#1a1a2e;">{{ $bloodRequest->hospital->hospital_name }}</div>
          <div style="font-size:0.78rem; color:#999;">{{ $bloodRequest->hospital->user->email }}</div>
        </div>
      </div>
      @foreach([
        ['License', $bloodRequest->hospital->license_number],
        ['Contact', $bloodRequest->hospital->contact_number ?? '—'],
        ['Address', $bloodRequest->hospital->address ?? '—'],
      ] as [$label, $value])
        <div style="display:flex; justify-content:space-between; padding:0.6rem 0; border-bottom:1px solid rgba(0,0,0,0.04); gap:1rem;">
          <span style="font-size:0.8rem; color:#888; white-space:nowrap;">{{ $label }}</span>
          <span style="font-size:0.82rem; color:#555; text-align:right;">{{ $value }}</span>
        </div>
      @endforeach
    </div>
  </div>

  {{-- Allocated Blood Units --}}
  <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Allocated Blood Units</h3>
        <span style="font-size:0.78rem; color:#999;">
          {{ $bloodRequest->bloodUnits->count() }} / {{ $bloodRequest->quantity }} unit(s)
        </span>
      </div>

      @if($bloodRequest->bloodUnits->isEmpty())
        <div class="dash-card-body">
          <x-empty-state
            title="No units allocated yet"
            :message="$bloodRequest->status->value === 'pending'
              ? 'Approve the request first, then fulfill it.'
              : ($bloodRequest->status->value === 'approved'
                ? 'Click Fulfill to allocate available units.'
                : 'This request was not fulfilled.')"/>
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
                <th>Status</th>
              </tr>
            </thead>
            <tbody>
              @foreach($bloodRequest->bloodUnits as $unit)
                @php $isExpiringSoon = $unit->expiry_date->diffInDays(now()) <= 7 && $unit->expiry_date->isFuture(); @endphp
                <tr style="{{ $isExpiringSoon ? 'background:rgba(243,156,18,0.04);' : '' }}">
                  <td>
                    <span style="font-family:var(--font-mono); font-size:0.8rem; color:#666; background:#f5f5f5; padding:0.2rem 0.5rem; border-radius:6px;">
                      #{{ str_pad($unit->blood_unit_id, 1, '0', STR_PAD_LEFT) }}
                    </span>
                  </td>
                  <td><x-blood-type-badge :type="$unit->bloodType->type_name"/></td>
                  <td style="font-size:0.82rem; color:#888;">{{ $unit->stored_date->format('M d, Y') }}</td>
                  <td style="font-size:0.82rem;">
                    <span style="color:{{ $isExpiringSoon ? '#E67E22' : '#888' }}; font-weight:{{ $isExpiringSoon ? '600' : '400' }};">
                      {{ $unit->expiry_date->format('M d, Y') }}
                    </span>
                    @if($isExpiringSoon)
                      <span style="font-size:0.7rem; color:#E67E22; margin-left:0.3rem;">Expiring soon</span>
                    @endif
                  </td>
                  <td><x-status-badge :status="$unit->status->value" size="sm"/></td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        {{-- Footer summary --}}
        <div class="dash-card-footer">
          <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
            <span style="font-size:0.82rem; color:#888;">
              {{ $bloodRequest->bloodUnits->count() }} unit(s) allocated
            </span>
            @if($bloodRequest->remaining > 0)
              <span style="font-size:0.78rem; color:#E67E22; font-weight:600; background:rgba(230,126,34,0.1); border:1px solid rgba(230,126,34,0.25); padding:0.2rem 0.65rem; border-radius:6px;">
                {{ $bloodRequest->remaining }} unit(s) still needed
              </span>
            @else
              <span style="font-size:0.78rem; color:#27AE60; font-weight:600; background:rgba(39,174,96,0.1); border:1px solid rgba(39,174,96,0.25); padding:0.2rem 0.65rem; border-radius:6px;">
                Fully fulfilled
              </span>
            @endif
          </div>
        </div>
      @endif
    </div>

  </div>

  {{-- Modals --}}
  @if($bloodRequest->status->value === 'pending')
    <x-modal id="approve-req" title="Approve Blood Request" size="sm">
      <form method="POST" action="{{ route($routePrefix . '.blood-requests.approve', $bloodRequest) }}">
        @csrf @method('PATCH')
        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
          Approving <strong>{{ $bloodRequest->quantity }} unit(s)</strong> of
          <strong>{{ $bloodRequest->bloodType->type_name }}</strong> for
          <strong>{{ $bloodRequest->hospital->hospital_name }}</strong>.
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

    <x-modal id="reject-req" title="Reject Blood Request" size="sm">
      <form method="POST" action="{{ route($routePrefix . '.blood-requests.reject', $bloodRequest) }}">
        @csrf @method('PATCH')
        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
          Rejecting request from <strong>{{ $bloodRequest->hospital->hospital_name }}</strong>.
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

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1fr 1.4fr"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
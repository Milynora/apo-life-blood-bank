<x-app-layout title="Request Details">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('hospital.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('hospital.requests.index') }}">Requests</a>
        <span class="breadcrumb-sep">›</span>
        <span>Request #{{ str_pad($bloodRequest->request_id, 1, '0', STR_PAD_LEFT) }}</span>
      </div>
      <h1 class="page-title">Request Details</h1>
    </div>
    <a href="{{ route('hospital.requests.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem;">

    {{-- ── LEFT COLUMN ── --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

      {{-- Summary card --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Request Summary</h3>
          <x-status-badge :status="$bloodRequest->status->value"/>
        </div>
        <div class="dash-card-body">

          @foreach([
            ['Blood Type',   $bloodRequest->bloodType->type_name],
            ['Request ID',   '#' . str_pad($bloodRequest->request_id, 1, '0', STR_PAD_LEFT)],
            ['Quantity',     $bloodRequest->quantity . ' unit(s)'],
            ['Fulfilled',    $bloodRequest->bloodUnits->count() . ' unit(s)'],
            ['Remaining',    $bloodRequest->remaining . ' unit(s)'],
            ['Urgency',      ucfirst($bloodRequest->urgency ?? 'routine')],
            ['Fulfillment',  ucfirst($bloodRequest->fulfillment_type ?? 'pickup')],
            ['Request Date', $bloodRequest->request_date->format('F d, Y')],
            ['Needed By',    $bloodRequest->needed_by ? $bloodRequest->needed_by->format('F d, Y') : '—'],
            ['Submitted',    $bloodRequest->created_at->diffForHumans()],
          ] as [$label, $value])
            <div style="display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
              <span style="font-size:0.82rem; color:#888;">{{ $label }}</span>
              <span style="font-size:0.85rem; color:{{ $label === 'Blood Type' ? 'var(--primary)' : '#1a1a2e' }}; font-weight:{{ $label === 'Blood Type' ? '800' : '600' }}; font-family:{{ $label === 'Blood Type' ? 'var(--font-mono)' : 'inherit' }};">
                {{ $value }}
              </span>
            </div>
          @endforeach

          {{-- Progress bar --}}
          @if($bloodRequest->quantity > 0)
            @php $pct = min(100, round(($bloodRequest->bloodUnits->count() / $bloodRequest->quantity) * 100)); @endphp
            <div style="margin-top:1.25rem;">
              <div style="display:flex; justify-content:space-between; font-size:0.78rem; color:#888; margin-bottom:0.5rem;">
                <span>Fulfillment</span>
                <strong style="color:#1a1a2e;">{{ $pct }}%</strong>
              </div>
              <div style="height:8px; background:#f0f2f5; border-radius:4px; overflow:hidden;">
                <div style="height:100%; border-radius:4px; background:{{ $pct >= 100 ? '#27AE60' : 'linear-gradient(90deg,#3498DB,var(--primary))' }}; width:{{ $pct }}%; transition:width 0.5s ease;"></div>
              </div>
            </div>
          @endif

          {{-- Available units for this blood type --}}
          @if(in_array($bloodRequest->status->value, ['approved', 'partially_fulfilled']))
            <div style="margin-top:1rem; padding:0.75rem 0.85rem; border-radius:10px;
              background:{{ $availableCount > 0 ? 'rgba(39,174,96,0.06)' : 'rgba(192,57,43,0.05)' }};
              border:1px solid {{ $availableCount > 0 ? 'rgba(39,174,96,0.2)' : 'rgba(192,57,43,0.2)' }};
              display:flex; align-items:center; gap:0.65rem;">
              <svg width="15" height="15" fill="none" stroke="{{ $availableCount > 0 ? '#27AE60' : '#E74C3C' }}" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" d="{{ $availableCount > 0 ? 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z' : 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z' }}"/>
              </svg>
              <div>
                <div style="font-size:0.8rem; font-weight:600; color:{{ $availableCount > 0 ? '#27AE60' : '#E74C3C' }};">
                  {{ $availableCount }} unit(s) of {{ $bloodRequest->bloodType->type_name }} currently available
                </div>
                <div style="font-size:0.72rem; color:#888; margin-top:0.1rem;">
                  {{ $availableCount >= $bloodRequest->remaining
                    ? 'Enough to fulfill remaining ' . $bloodRequest->remaining . ' unit(s)'
                    : 'Not enough for full fulfillment — partial allocation possible' }}
                </div>
              </div>
            </div>
          @endif

        </div>
      </div>

      {{-- Remarks --}}
      @if($bloodRequest->remarks)
        <div class="dash-card">
          <div class="dash-card-header"><h3 class="dash-card-title">Remarks / Notes</h3></div>
          <div class="dash-card-body">
            <p style="font-size:0.875rem; color:#555; line-height:1.7; margin:0;">{{ $bloodRequest->remarks }}</p>
          </div>
        </div>
      @endif

    </div>

    {{-- ── RIGHT COLUMN ── --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

      {{-- Status timeline --}}
      <div class="dash-card">
        <div class="dash-card-header"><h3 class="dash-card-title">Status Timeline</h3></div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
          @php
            $isCancelled = $bloodRequest->status === \App\Enums\RequestStatus::Cancelled;
            $isRejected  = $bloodRequest->status === \App\Enums\RequestStatus::Rejected;
            $isFulfilled = $bloodRequest->status === \App\Enums\RequestStatus::Fulfilled;
            $isApproved  = in_array($bloodRequest->status->value, ['approved','partially_fulfilled','fulfilled']);

            $steps = [
              [
                'label' => 'Request Submitted',
                'time'  => $bloodRequest->created_at->format('M d, Y h:i A'),
                'done'  => true,
                'color' => null,
              ],
              [
                'label' => $isCancelled ? 'Cancelled by Hospital'
                         : ($isRejected  ? 'Rejected by Staff'
                         : ($isApproved  ? 'Approved by Staff'
                                         : 'Pending Approval')),
                'time'  => in_array($bloodRequest->status->value, ['approved','rejected','cancelled','partially_fulfilled','fulfilled'])
                           ? $bloodRequest->updated_at->format('M d, Y h:i A')
                           : null,
                'done'  => in_array($bloodRequest->status->value, ['approved','rejected','cancelled','partially_fulfilled','fulfilled']),
                'color' => $isCancelled ? '#888' : ($isRejected ? '#E74C3C' : null),
              ],
              [
                'label' => 'Fulfilled',
                'time'  => $isFulfilled ? $bloodRequest->updated_at->format('M d, Y h:i A') : null,
                'done'  => $isFulfilled,
                'color' => null,
              ],
            ];
          @endphp
          <div style="display:flex; flex-direction:column; gap:0;">
            @foreach($steps as $i => $step)
              @php $useSolid = isset($step['color']) && $step['color']; @endphp
              <div style="display:flex; gap:1rem; padding-bottom:{{ $i < count($steps)-1 ? '1.25rem' : '0' }}; position:relative;">
                @if($i < count($steps)-1)
                  <div style="position:absolute; left:11px; top:24px; bottom:0; width:2px; background:{{ $step['done'] ? 'rgba(192,57,43,0.25)' : '#f0f0f0' }};"></div>
                @endif
                <div style="width:24px; height:24px; border-radius:50%;
                  background:{{ $step['done'] ? ($useSolid ? $step['color'] : 'linear-gradient(135deg,var(--primary),#e94560)') : '#f0f2f5' }};
                  border:2px solid {{ $step['done'] ? ($useSolid ? $step['color'] : 'var(--primary)') : '#ddd' }};
                  display:flex; align-items:center; justify-content:center; flex-shrink:0; z-index:1;
                  box-shadow:{{ $step['done'] && !$useSolid ? '0 2px 8px rgba(192,57,43,0.3)' : 'none' }};">
                  @if($step['done'])
                    @if(($isCancelled || $isRejected) && $i === 1)
                      <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    @else
                      <svg width="10" height="10" fill="none" stroke="#fff" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                    @endif
                  @endif
                </div>
                <div>
                  <div style="font-size:0.875rem; font-weight:{{ $step['done'] ? '700' : '500' }}; color:{{ $step['done'] ? ($useSolid ? $step['color'] : '#1a1a2e') : '#bbb' }};">
                    {{ $step['label'] }}
                  </div>
                  @if($step['time'])
                    <div style="font-size:0.75rem; color:#aaa; margin-top:0.15rem;">{{ $step['time'] }}</div>
                  @endif
                </div>
              </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Allocated blood units --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Allocated Blood Units</h3>
          <span style="font-size:0.78rem; color:#bbb;">{{ $bloodRequest->bloodUnits->count() }} / {{ $bloodRequest->quantity }} units</span>
        </div>

        @if($bloodRequest->bloodUnits->isEmpty())
          <div class="dash-card-body">
            @if($bloodRequest->status->value === 'pending')
              <x-empty-state title="Awaiting staff approval" message="Once your request is approved, blood units will be allocated here."/>
            @elseif($bloodRequest->status->value === 'approved')
              <x-empty-state title="Approved — units being allocated" message="Our staff is processing your request. You will be notified when units are assigned."/>
            @elseif($bloodRequest->status->value === 'cancelled')
              <x-empty-state title="Request was cancelled" message="This request has been cancelled and no units will be allocated."/>
            @elseif($bloodRequest->status->value === 'rejected')
              <x-empty-state title="Request was rejected" message="{{ $bloodRequest->remarks ?? 'Please contact us for more information.' }}"/>
            @else
              <x-empty-state title="No units allocated"/>
            @endif
          </div>
        @else
          <div class="table-container" style="border:none; border-radius:0;">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Unit ID</th>
                  <th>Blood Type</th>
                  <th>Stored</th>
                  <th>Expiry</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($bloodRequest->bloodUnits as $unit)
                  <tr>
                    <td>
                      <span style="font-family:var(--font-mono); font-size:0.8rem; color:#888; background:#f5f5f5; padding:0.2rem 0.5rem; border-radius:6px;">
                        #{{ str_pad($unit->blood_unit_id, 1, '0', STR_PAD_LEFT) }}
                      </span>
                    </td>
                    <td><x-blood-type-badge :type="$unit->bloodType->type_name"/></td>
                    <td style="font-size:0.82rem; color:#888;">{{ $unit->stored_date->format('M d, Y') }}</td>
                    <td style="font-size:0.82rem; color:#888;">{{ $unit->expiry_date->format('M d, Y') }}</td>
                    <td><x-status-badge :status="$unit->status->value" size="sm"/></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div class="dash-card-footer">
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:0.5rem;">
              <span style="font-size:0.82rem; color:#888;">
                {{ $bloodRequest->bloodUnits->count() }} unit(s) allocated
                @if($bloodRequest->remaining > 0)
                  · <span style="color:#E67E22; font-weight:600;">{{ $bloodRequest->remaining }} still needed</span>
                @else
                  · <span style="color:#27AE60; font-weight:600;">Fully fulfilled</span>
                @endif
              </span>
            </div>
          </div>
        @endif
      </div>

    </div>

  </div>

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
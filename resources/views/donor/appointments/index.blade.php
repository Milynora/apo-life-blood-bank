<x-app-layout title="My Appointments">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('donor.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>My Appointments</span>
      </div>
      <h1 class="page-title">My Appointments</h1>
      <p class="page-subtitle">Track and manage your donation appointments.</p>
    </div>
    <a href="{{ route('donor.appointments.create') }}" class="btn btn-dash-primary">
      <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
      Request Appointment
    </a>
  </div>

  @if($appointments->isEmpty())
    <div class="dash-card">
      <div class="dash-card-body" style="padding:4rem 2rem;">
        <x-empty-state
          title="No appointments yet"
          message="Schedule your first donation appointment. It only takes a minute."
          :action="route('donor.appointments.create')"
          action-label="Schedule Now"/>
      </div>
    </div>
  @else
    @php
      $upcoming = $appointments->filter(fn($a) => in_array($a->status->value, ['pending','approved']) && $a->appointment_date->isFuture());
      $past     = $appointments->filter(fn($a) => !$upcoming->contains($a));
    @endphp

    {{-- ── Upcoming ─────────────────────────────────────────── --}}
    @if($upcoming->count())
      <div style="margin-bottom:1.5rem;">
        <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#999; margin-bottom:0.85rem; display:flex; align-items:center; gap:0.5rem;">
          <div style="width:8px; height:8px; border-radius:50%; background:#27AE60; animation:pulse 2s infinite;"></div>
          Upcoming ({{ $upcoming->count() }})
        </div>
        <div style="display:flex; flex-direction:column; gap:1rem;">
          @foreach($upcoming->sortBy('appointment_date') as $appt)
            <div class="dash-card" style="border-left:4px solid {{ $appt->status->value === 'approved' ? '#27AE60' : '#F39C12' }};" x-data>
              <div style="padding:1.25rem 1.5rem; display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap;">

                {{-- Date block --}}
                <div style="width:56px; height:60px; background:linear-gradient(135deg,var(--primary),#e94560); border-radius:14px; display:flex; flex-direction:column; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 4px 12px rgba(192,57,43,0.3);">
                  <div style="font-size:1.3rem; font-weight:800; color:#fff; line-height:1; font-family:var(--font-display);">{{ $appt->appointment_date->format('d') }}</div>
                  <div style="font-size:0.62rem; font-weight:600; color:rgba(255,255,255,0.85); text-transform:uppercase; letter-spacing:0.05em;">{{ $appt->appointment_date->format('M Y') }}</div>
                </div>

                {{-- Details --}}
                <div style="flex:1; min-width:180px;">
                  <div style="font-weight:700; font-size:1rem; color:#1a1a2e; margin-bottom:0.3rem;">
                    {{ $appt->appointment_date->format('l') }}, {{ $appt->appointment_date->format('F d, Y') }}
                  </div>
                  <div style="font-size:0.85rem; color:#666; display:flex; align-items:center; gap:0.4rem;">
                    <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    {{ $appt->appointment_date->format('h:i A') }}
                    &bull;
                    {{ $appt->appointment_date->diffForHumans() }}
                  </div>
                  @if($appt->notes)
                    <div style="font-size:0.78rem; color:#aaa; margin-top:0.3rem; display:flex; align-items:center; gap:0.35rem;">
                      <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/></svg>
                      {{ $appt->notes }}
                    </div>
                  @endif
                </div>

                {{-- Status + actions --}}
                <div style="display:flex; align-items:center; gap:0.65rem; flex-shrink:0;">
                  <x-status-badge :status="$appt->status->value"/>

                  {{-- View --}}
                  <a href="{{ route('donor.appointments.show', $appt) }}"
                    style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 0.9rem; border-radius:8px; border:1px solid var(--border-light); background:#fff; color:#555; font-size:0.78rem; font-weight:600; text-decoration:none; transition:all 0.2s;"
                    onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
                    onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
                    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    View
                  </a>

                  {{-- Cancel --}}
                  @if(in_array($appt->status->value, ['pending','approved']) && now()->diffInHours($appt->appointment_date) > 24)
                    <button
                      @click="$dispatch('open-modal', 'cancel-{{ $appt->appointment_id }}')"
                      style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 0.9rem; border-radius:8px; border:1px solid rgba(192,57,43,0.25); background:rgba(192,57,43,0.06); color:#E74C3C; font-size:0.78rem; font-weight:600; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;"
                      onmouseover="this.style.background='rgba(192,57,43,0.14)'"
                      onmouseout="this.style.background='rgba(192,57,43,0.06)'">
                      <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                      Cancel
                    </button>
                  @endif
                </div>
              </div>

              <x-confirm-dialog
                id="cancel-{{ $appt->appointment_id }}"
                title="Cancel Appointment?"
                message="Cancel your appointment on {{ $appt->appointment_date->format('M d, Y') }}? You can always reschedule."
                confirm-label="Yes, Cancel"
                confirm-class="btn-danger"
                action="{{ route('donor.appointments.destroy', $appt) }}"
                method="DELETE"/>
            </div>
          @endforeach
        </div>
      </div>
    @endif

    {{-- ── Past ─────────────────────────────────────────────── --}}
    @if($past->count())
      <div>
        <div style="font-size:0.75rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#bbb; margin-bottom:0.85rem;">
          Past Appointments ({{ $past->count() }})
        </div>
        <div class="dash-card">
          <div class="table-container" style="border:none; border-radius:0;">
            <table class="data-table">
              <thead>
  <tr>
    <th>Date & Time</th>
    <th>Status</th>
    <th>Notes</th>
    <th>Donation</th>
    <th style="text-align:right;">Action</th>
  </tr>
</thead>
<tbody>
  @foreach($past->sortByDesc('appointment_date') as $appt)
    <tr>
      <td style="font-size:0.875rem; font-weight:500;">
        {{ $appt->appointment_date->format('M d, Y') }}
        <div style="font-size:0.75rem; color:#999;">{{ $appt->appointment_date->format('h:i A') }}</div>
      </td>
      <td><x-status-badge :status="$appt->status->value" size="sm"/></td>
      <td style="font-size:0.82rem; color:#888;">
        {{ $appt->notes ? Str::limit($appt->notes, 40) : '—' }}
      </td>
      <td>
        @if($appt->donation)
          <span style="font-size:0.78rem; font-weight:600; color:#27AE60;">
            {{ $appt->donation->volume }} mL donated
          </span>
        @else
          <span style="font-size:0.78rem; color:#ddd;">—</span>
        @endif
      </td>
      <td>
        <div style="display:flex; justify-content:flex-end;">
          <a href="{{ route('donor.appointments.show', $appt) }}"
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
          <div class="dash-card-footer">
            <x-pagination :paginator="$appointments"/>
          </div>
        </div>
      </div>
    @endif
  @endif

</x-app-layout>
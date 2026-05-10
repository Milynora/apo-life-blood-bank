<x-app-layout title="Appointment Details">

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
        <a href="{{ route($routePrefix . '.appointments.index') }}">Appointments</a>
        <span class="breadcrumb-sep">›</span>
        <span>Details</span>
      </div>
      <h1 class="page-title">Appointment Details</h1>
    </div>
    <a href="{{ route($routePrefix . '.appointments.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1fr; gap:1.25rem;" x-data>

    {{-- Hero banner --}}
<div class="dash-card" x-data>

  {{-- Row 1: Identity + profile --}}
<div style="padding:0.85rem 1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem; border-bottom:1px solid rgba(0,0,0,0.06);">
  <div style="display:flex; align-items:center; gap:0.85rem;">
    <div style="width:38px; height:38px; border-radius:50%; overflow:hidden; background:linear-gradient(135deg,var(--primary),#e94560); display:flex; align-items:center; justify-content:center; font-size:0.95rem; font-weight:800; color:#fff; flex-shrink:0; border:2px solid #fff; outline:2px solid rgba(192,57,43,0.15); box-shadow:0 2px 8px rgba(192,57,43,0.25);">
      @if($appointment->donor->avatar)
        <img src="{{ asset($appointment->donor->avatar) }}" alt="{{ $appointment->donor->name }}" style="width:100%; height:100%; object-fit:cover;"/>
      @else
        {{ strtoupper(substr($appointment->donor->name, 0, 1)) }}
      @endif
    </div>
    <div>
      <div style="font-size:0.9rem; font-weight:700; color:#1a1a2e;">{{ $appointment->donor->name }}</div>
      <div style="font-size:0.75rem; color:#888; margin-top:1px;">{{ $appointment->donor->user->email }}</div>
    </div>
  </div>
  <a href="{{ route($routePrefix . '.donors.show', $appointment->donor) }}"
    style="display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 0.8rem; border-radius:7px; border:1px solid rgba(41,128,185,0.3); background:rgba(41,128,185,0.08); color:#2980B9; text-decoration:none; font-size:0.78rem; font-weight:600; transition:all 0.2s;"
    onmouseover="this.style.background='rgba(41,128,185,0.18)'"
    onmouseout="this.style.background='rgba(41,128,185,0.08)'">
    <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
    Profile
  </a>
</div>

{{-- Row 2: Date/time/notes + status + action buttons --}}
<div style="padding:0.75rem 1.25rem; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:0.75rem;">
  <div style="font-size:0.8rem; color:#888; display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
    <svg width="13" height="13" fill="none" stroke="#bbb" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
    <span>{{ $appointment->appointment_date->format('M d, Y') }} · {{ $appointment->appointment_date->format('h:i A') }} · {{ $appointment->appointment_date->format('l') }}</span>
    @if($appointment->notes)
      <span style="color:#ddd;">·</span>
      <span style="color:#aaa; font-style:italic;">{{ Str::limit($appointment->notes, 50) }}</span>
    @endif
  </div>
  <div style="display:flex; align-items:center; gap:0.5rem; flex-wrap:wrap;">
    <x-status-badge :status="$appointment->status->value"/>
    @if($appointment->status->value === 'pending')
      <button @click="$dispatch('open-modal','approve-appt')"
        style="display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 0.8rem; border-radius:7px; border:1px solid rgba(39,174,96,0.3); background:rgba(39,174,96,0.08); color:#27AE60; font-size:0.78rem; font-weight:600; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;"
        onmouseover="this.style.background='rgba(39,174,96,0.18)'"
        onmouseout="this.style.background='rgba(39,174,96,0.08)'">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
        Approve
      </button>
      <button @click="$dispatch('open-modal','reject-appt')"
        style="display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 0.8rem; border-radius:7px; border:1px solid rgba(243,156,18,0.3); background:rgba(243,156,18,0.08); color:#E67E22; font-size:0.78rem; font-weight:600; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;"
        onmouseover="this.style.background='rgba(243,156,18,0.18)'"
        onmouseout="this.style.background='rgba(243,156,18,0.08)'">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
        Reject
      </button>
    @endif
    @if($appointment->status->value === 'approved')
      <button @click="$dispatch('open-modal','cancel-appt')"
        style="display:inline-flex; align-items:center; gap:0.35rem; padding:0.4rem 0.8rem; border-radius:7px; border:1px solid rgba(192,57,43,0.3); background:rgba(192,57,43,0.08); color:#E74C3C; font-size:0.78rem; font-weight:600; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;"
        onmouseover="this.style.background='rgba(192,57,43,0.18)'"
        onmouseout="this.style.background='rgba(192,57,43,0.08)'">
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
        Cancel Appointment
      </button>
    @endif
  </div>
</div>

  {{-- Modals --}}
  @if($appointment->status->value === 'pending')
    <x-modal id="approve-appt" title="Approve Appointment" size="sm">
      <form method="POST" action="{{ route($routePrefix . '.appointments.update', $appointment) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="approved"/>
        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
          Approve appointment for <strong>{{ $appointment->donor->name }}</strong> on
          <strong>{{ $appointment->appointment_date->format('M d, Y h:i A') }}</strong>?
        </p>
        <div class="form-group">
          <label class="form-label">Notes <span style="font-weight:400; color:#bbb;">(optional)</span></label>
          <textarea name="notes" class="form-input form-input-light" rows="2"
            placeholder="Any instructions for the donor…">{{ $appointment->notes }}</textarea>
        </div>
        <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
          <button type="button" onclick="closeModal()"
            style="padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body);">
            Cancel
          </button>
          <button type="submit" class="btn btn-success btn-sm" style="border-radius:10px;">Confirm Approval</button>
        </div>
      </form>
    </x-modal>

    <x-modal id="reject-appt" title="Reject Appointment" size="sm">
      <form method="POST" action="{{ route($routePrefix . '.appointments.update', $appointment) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="rejected"/>
        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
          Reject appointment for <strong>{{ $appointment->donor->name }}</strong> on
          <strong>{{ $appointment->appointment_date->format('M d, Y h:i A') }}</strong>?
        </p>
        <div class="form-group">
          <label class="form-label">Reason <span style="color:#E74C3C;">*</span></label>
          <textarea name="notes" class="form-input form-input-light" rows="2"
            placeholder="e.g. Chosen date is a holiday, blood bank is at full capacity…" required></textarea>
        </div>
        <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
          <button type="button" onclick="closeModal()"
            style="padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body);">
            Back
          </button>
          <button type="submit" class="btn btn-warning btn-sm" style="border-radius:10px;">Reject</button>
        </div>
      </form>
    </x-modal>
  @endif

  @if($appointment->status->value === 'approved')
    <x-modal id="cancel-appt" title="Cancel Appointment" size="sm">
      <form method="POST" action="{{ route($routePrefix . '.appointments.update', $appointment) }}">
        @csrf @method('PATCH')
        <input type="hidden" name="status" value="cancelled"/>
        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
          Cancel appointment for <strong>{{ $appointment->donor->name }}</strong> on
          <strong>{{ $appointment->appointment_date->format('M d, Y h:i A') }}</strong>?
        </p>
        <div class="form-group">
          <label class="form-label">Reason <span style="font-weight:400; color:#bbb;">(optional)</span></label>
          <textarea name="notes" class="form-input form-input-light" rows="2"
            placeholder="Reason for cancellation…"></textarea>
        </div>
        <div style="display:flex; gap:0.75rem; justify-content:flex-end;">
          <button type="button" onclick="closeModal()"
            style="padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body);">
            Back
          </button>
          <button type="submit" class="btn btn-danger btn-sm" style="border-radius:10px;">Cancel Appointment</button>
        </div>
      </form>
    </x-modal>
  @endif

</div>

    {{-- Bottom two columns --}}
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.25rem;">

      {{-- Donor details --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Donor Details</h3>
        </div>
        <div class="dash-card-body">
          @foreach([
            ['Contact',        $appointment->donor->contact_number ?? '—'],
            ['Gender',         ucfirst($appointment->donor->gender)],
            ['Address',        $appointment->donor->address ?? '—'],
            ['Total Donations', $appointment->donor->donations->count() . ' time(s)'],
            ['Last Donation',  $appointment->donor->donations->sortByDesc('donation_date')->first()?->donation_date->format('M d, Y') ?? 'None'],
          ] as [$l, $v])
            <div style="display:flex; justify-content:space-between; padding:0.65rem 0; border-bottom:1px solid rgba(0,0,0,0.04); gap:1rem;">
              <span style="font-size:0.82rem; color:#888; white-space:nowrap;">{{ $l }}</span>
              <span style="font-size:0.85rem; color:#1a1a2e; font-weight:500; text-align:right;">{{ $v }}</span>
            </div>
          @endforeach
        </div>
      </div>

      {{-- Linked records --}}
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Linked Records</h3>
        </div>
        <div class="dash-card-body">

          @if($appointment->screening)
            <div style="padding:0.85rem 1rem; background:rgba(39,174,96,0.05); border:1px solid rgba(39,174,96,0.18); border-radius:10px; margin-bottom:0.75rem;">
              <div style="font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#27AE60; margin-bottom:0.4rem;">Screening</div>
              <div style="font-size:0.875rem; color:#1a1a2e; font-weight:600; margin-bottom:0.35rem;">
                Screened on {{ optional($appointment->screening->screening_date)->format('M d, Y') }}
              </div>
              <x-status-badge :status="$appointment->screening->eligibility_status->value" size="sm"/>
            </div>
          @else
            <div style="padding:0.85rem 1rem; background:rgba(0,0,0,0.02); border:1px dashed rgba(0,0,0,0.1); border-radius:10px; margin-bottom:0.75rem;">
              <div style="font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#bbb; margin-bottom:0.4rem;">Screening</div>
              <div style="font-size:0.82rem; color:#bbb; font-style:italic;">No screening recorded yet</div>
            </div>
          @endif

          @if($appointment->donation)
            <div style="padding:0.85rem 1rem; background:rgba(41,128,185,0.05); border:1px solid rgba(41,128,185,0.18); border-radius:10px; margin-bottom:0.75rem;">
              <div style="font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#2980B9; margin-bottom:0.4rem;">Donation</div>
              <div style="font-size:0.875rem; color:#1a1a2e; font-weight:600; margin-bottom:0.35rem;">
                Donated on {{ $appointment->donation->donation_date->format('M d, Y') }}
              </div>
              <x-status-badge :status="$appointment->donation->status->value" size="sm"/>
            </div>
          @else
            <div style="padding:0.85rem 1rem; background:rgba(0,0,0,0.02); border:1px dashed rgba(0,0,0,0.1); border-radius:10px; margin-bottom:0.75rem;">
              <div style="font-size:0.68rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#bbb; margin-bottom:0.4rem;">Donation</div>
              <div style="font-size:0.82rem; color:#bbb; font-style:italic;">No donation recorded yet</div>
            </div>
          @endif

          @if($appointment->status->value === 'approved' && !$appointment->screening)
            <a href="{{ route($routePrefix . '.screenings.create') }}?donor_id={{ $appointment->donor->donor_id }}&appointment_id={{ $appointment->appointment_id }}"
              style="display:inline-flex; align-items:center; justify-content:center; gap:0.4rem; width:100%; padding:0.55rem 1rem; border-radius:8px; border:1px solid rgba(39,174,96,0.3); background:rgba(39,174,96,0.08); color:#27AE60; text-decoration:none; font-size:0.82rem; font-weight:600; transition:all 0.2s;"
              onmouseover="this.style.background='rgba(39,174,96,0.18)'"
              onmouseout="this.style.background='rgba(39,174,96,0.08)'">
              <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
              Record Screening
            </a>
          @endif

        </div>
      </div>

    </div>
  </div>

  <style>
    @media (max-width: 768px) {
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
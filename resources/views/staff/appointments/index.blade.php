<x-app-layout title="Appointments">

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
        <span>Appointments</span>
      </div>
      <h1 class="page-title">Appointments</h1>
      <p class="page-subtitle">Review and manage donor appointment requests.</p>
    </div>
    <a href="{{ route($routePrefix . '.appointments.create') }}" class="btn btn-dash-primary">
  <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 5v14m7-7H5"/></svg>
  Schedule Appointment
</a>
  </div>

  <div style="display:grid; grid-template-columns:repeat(6,1fr); gap:1.25rem; margin-bottom:1.75rem;">
    <x-stat-card title="Pending"   :value="$stats['pending']"   color="yellow" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Approved"  :value="$stats['approved']"  color="blue"   icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Completed" :value="$stats['completed']" color="green"  icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Cancelled" :value="$stats['cancelled']" color="gray"   icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Rejected"  :value="$stats['rejected']"  color="red"    icon="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
    <x-stat-card title="No Show"   :value="$stats['no_show']"   color="navy"   icon="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"/>
</div>

  {{-- Filter bar --}}
<div class="dash-card" style="margin-bottom:1.5rem; padding:1rem 1.25rem;"
  x-data="{
    search: '{{ request('search', '') }}',
    status: '{{ request('status','') }}',
    date: '{{ request('date','') }}',
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
          document.getElementById('appointments-table-wrapper').innerHTML =
            doc.getElementById('appointments-table-wrapper').innerHTML;
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
          style="animation:appt-spin 0.7s linear infinite;">
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
      class="form-input form-input-light" style="min-width:150px; max-width:200px;">
      <option value="">All Status</option>
      <option value="pending">Pending</option>
      <option value="approved">Approved</option>
      <option value="rejected">Rejected</option>
      <option value="cancelled">Cancelled</option>
      <option value="completed">Completed</option>
      <option value="no_show">No Show</option>
    </select>

    {{-- Date --}}
    <input type="date" x-model="date" @change="updateResults()"
      class="form-input form-input-light" style="min-width:175px; max-width:210px;"/>

  </div>
</div>

<style>
  @keyframes appt-spin { to { transform: rotate(360deg); } }
</style>

  {{-- Table --}}
  <div id="appointments-table-wrapper">
  <div class="dash-card" x-data>
    <div class="dash-card-header">
      <h3 class="dash-card-title">
        All Appointments
        <span style="font-size:0.75rem; font-weight:400; color:#bbb; margin-left:0.4rem;">({{ $appointments->total() }})</span>
      </h3>
    </div>

    @if($appointments->isEmpty())
      <div class="dash-card-body"><x-empty-state title="No appointments found"/></div>
    @else
      <div class="table-container" style="border:none; border-radius:0;">
        <table class="data-table">
          <thead>
            <tr>
              <th>Donor</th>
              <th>Blood Type</th>
              <th>Date & Time</th>
              <th>Status</th>
              <th>Notes</th>
              <th style="text-align:right;">Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($appointments as $appt)
              <tr>
                {{-- Donor --}}
                <td>
                  <div style="font-weight:600; font-size:0.875rem; color:#1a1a2e;">{{ $appt->donor->name }}</div>
                  <div style="font-size:0.75rem; color:#999;">{{ $appt->donor->user->email ?? '—' }}</div>
                </td>

                {{-- Blood Type --}}
                <td>
                  @if($appt->donor->bloodType)
                    <x-blood-type-badge :type="$appt->donor->bloodType->type_name"/>
                  @else
                    <span style="font-size:0.78rem; color:#bbb; font-style:italic;">Unknown</span>
                  @endif
                </td>

                {{-- Date --}}
                <td>
                  <div style="font-size:0.875rem; font-weight:500; color:#1a1a2e;">{{ $appt->appointment_date->format('M d, Y') }}</div>
                  <div style="font-size:0.78rem; color:#888;">{{ $appt->appointment_date->format('h:i A') }}</div>
                </td>

                {{-- Status --}}
                <td><x-status-badge :status="$appt->status->value" size="sm"/></td>

                {{-- Notes --}}
                <td style="font-size:0.78rem; color:#888; max-width:160px;">
                  {{ $appt->notes ? Str::limit($appt->notes, 40) : '—' }}
                </td>

                {{-- Actions --}}
                <td style="text-align:right;">
                  <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.4rem;">

                    {{-- View --}}
                    <a href="{{ route($routePrefix . '.appointments.show', $appt) }}"
                      style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(41,128,185,0.3); background:rgba(41,128,185,0.08); color:#2980B9; text-decoration:none; transition:all 0.2s;"
                      onmouseover="this.style.background='rgba(41,128,185,0.18)'"
                      onmouseout="this.style.background='rgba(41,128,185,0.08)'"
                      title="View">
                      <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </a>

                    {{-- PENDING: Approve + Reject --}}
                    @if($appt->status->value === 'pending')
                      <button type="button"
                        @click="$dispatch('open-modal','approve-{{ $appt->appointment_id }}')"
                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(39,174,96,0.3); background:rgba(39,174,96,0.08); color:#27AE60; cursor:pointer; transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(39,174,96,0.18)'"
                        onmouseout="this.style.background='rgba(39,174,96,0.08)'"
                        title="Approve">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                      </button>
                      <button type="button"
                        @click="$dispatch('open-modal','reject-{{ $appt->appointment_id }}')"
                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(243,156,18,0.3); background:rgba(243,156,18,0.08); color:#F39C12; cursor:pointer; transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(243,156,18,0.18)'"
                        onmouseout="this.style.background='rgba(243,156,18,0.08)'"
                        title="Reject">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                      </button>
                    @endif

                    {{-- APPROVED: Cancel --}}
                    @if($appt->status->value === 'approved')
                      <button type="button"
                        @click="$dispatch('open-modal','cancel-{{ $appt->appointment_id }}')"
                        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(192,57,43,0.3); background:rgba(192,57,43,0.08); color:#E74C3C; cursor:pointer; transition:all 0.2s;"
                        onmouseover="this.style.background='rgba(192,57,43,0.18)'"
                        onmouseout="this.style.background='rgba(192,57,43,0.08)'"
                        title="Cancel">
                        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/></svg>
                      </button>
                    @endif

                  </div>

                  {{-- Modals --}}
                  @if($appt->status->value === 'pending')
                    <x-modal id="approve-{{ $appt->appointment_id }}" title="Approve Appointment" size="sm">
                      <form method="POST" action="{{ route($routePrefix . '.appointments.update', $appt) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="approved"/>
                        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
                          Approve appointment for <strong>{{ $appt->donor->name }}</strong> on
                          <strong>{{ $appt->appointment_date->format('M d, Y h:i A') }}</strong>?
                        </p>
                        <div class="form-group">
                          <label class="form-label">Notes <span style="font-weight:400; color:#bbb;">(optional)</span></label>
                          <textarea name="notes" class="form-input form-input-light" rows="2"
                            placeholder="Any instructions for the donor…"></textarea>
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

                    <x-modal id="reject-{{ $appt->appointment_id }}" title="Reject Appointment" size="sm">
                      <form method="POST" action="{{ route($routePrefix . '.appointments.update', $appt) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="rejected"/>
                        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
                          Reject appointment for <strong>{{ $appt->donor->name }}</strong> on
                          <strong>{{ $appt->appointment_date->format('M d, Y h:i A') }}</strong>?
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

                  @if($appt->status->value === 'approved')
                    <x-modal id="cancel-{{ $appt->appointment_id }}" title="Cancel Appointment" size="sm">
                      <form method="POST" action="{{ route($routePrefix . '.appointments.update', $appt) }}">
                        @csrf @method('PATCH')
                        <input type="hidden" name="status" value="cancelled"/>
                        <p style="font-size:0.875rem; color:#555; margin-bottom:1rem;">
                          Cancel appointment for <strong>{{ $appt->donor->name }}</strong> on
                          <strong>{{ $appt->appointment_date->format('M d, Y h:i A') }}</strong>?
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

                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <div class="dash-card-footer"><x-pagination :paginator="$appointments"/></div>
    @endif
  </div>
  </div>

</x-app-layout>
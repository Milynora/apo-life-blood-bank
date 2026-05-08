<x-app-layout title="Donation Details">

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
        <a href="{{ route($routePrefix . '.donations.index') }}">Donations</a>
        <span class="breadcrumb-sep">›</span>
        <span>#{{ str_pad($donation->donation_id,5,'0',STR_PAD_LEFT) }}</span>
      </div>
      <h1 class="page-title">Donation Details</h1>
    </div>
    <a href="{{ route($routePrefix . '.donations.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.5rem;">

    {{-- Donation Summary --}}
    <div class="dash-card" x-data="{ editing: false, status: '{{ $donation->status->value }}' }">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Donation Summary</h3>
        <div style="display:flex; align-items:center; gap:0.65rem;">
          <button type="button"
            x-show="!editing"
            @click="editing = true"
            style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#555; font-size:0.8rem; font-weight:600; cursor:pointer; transition:all 0.2s;"
            onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
            onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            Edit
          </button>
          <button type="button"
            x-show="editing"
            @click="editing = false"
            style="display:inline-flex; align-items:center; gap:0.4rem; padding:0.45rem 1rem; border-radius:8px; border:1.5px solid var(--border-light); background:#fff; color:#888; font-size:0.8rem; font-weight:600; cursor:pointer;">
            Cancel
          </button>
        </div>
      </div>

      <div class="dash-card-body">
        <form method="POST" action="{{ route($routePrefix . '.donations.update', $donation) }}">
          @csrf @method('PATCH')
          <input type="hidden" name="status" :value="status"/>

          {{-- Donation ID --}}
          <div style="display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <span style="font-size:0.82rem; color:#888;">Donation ID</span>
            <span style="font-size:0.85rem; color:#1a1a2e; font-weight:600;">#{{ str_pad($donation->donation_id,5,'0',STR_PAD_LEFT) }}</span>
          </div>

          {{-- Donation Date --}}
          <div style="padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:0.82rem; color:#888;">Donation Date</span>
              <template x-if="!editing">
                <span style="font-size:0.85rem; color:#1a1a2e; font-weight:600;">{{ $donation->donation_date->format('F d, Y') }}</span>
              </template>
            </div>
            <template x-if="editing">
              <div style="margin-top:0.5rem;">
                <input type="text" name="donation_date"
                  value="{{ old('donation_date', $donation->donation_date->format('m/d/Y')) }}"
                  class="form-input form-input-light flatpickr-date"
                  placeholder="MM/DD/YYYY" autocomplete="off"/>
                @error('donation_date')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- Volume --}}
          <div style="padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:0.82rem; color:#888;">Volume</span>
              <template x-if="!editing">
                <span style="font-size:0.85rem; color:#1a1a2e; font-weight:600;">{{ $donation->volume }} mL</span>
              </template>
            </div>
            <template x-if="editing">
              <div style="margin-top:0.5rem;">
                <input type="number" name="volume"
                  value="{{ old('volume', $donation->volume) }}"
                  min="200" max="550"
                  class="form-input form-input-light" placeholder="450"/>
                <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Range: 200–550 mL</div>
                @error('volume')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- Blood Type --}}
<div style="padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
  <div style="display:flex; justify-content:space-between; align-items:center;">
    <span style="font-size:0.82rem; color:#888;">Blood Type</span>
    <template x-if="!editing">
      <span style="font-size:0.85rem; color:#1a1a2e; font-weight:600;">{{ $donation->donor->bloodType->type_name ?? '—' }}</span>
    </template>
  </div>
  <template x-if="editing">
    <div style="margin-top:0.5rem;">
      <select name="blood_type_id" required class="form-input form-input-light">
        @foreach(\App\Models\BloodType::orderBy('type_name')->get() as $bt)
          <option value="{{ $bt->blood_type_id }}"
            {{ old('blood_type_id', $donation->blood_type_id) == $bt->blood_type_id ? 'selected' : '' }}>
            {{ $bt->type_name }}
          </option>
        @endforeach
      </select>
      @error('blood_type_id')<div class="form-error">{{ $message }}</div>@enderror
    </div>
  </template>
</div>

          {{-- Status --}}
          <div style="padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:0.82rem; color:#888;">Status</span>
              <template x-if="!editing">
                <x-status-badge :status="$donation->status->value" size="sm"/>
              </template>
            </div>
            <template x-if="editing">
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-top:0.5rem;">
                <button type="button" @click="status='successful'"
                  :style="status==='successful' ? 'border-color:rgba(39,174,96,0.5); background:rgba(39,174,96,0.06); box-shadow:0 0 0 3px rgba(39,174,96,0.1);' : 'border-color:var(--border-light); background:#fafbff;'"
                  style="display:flex; align-items:center; gap:0.65rem; padding:0.75rem 1rem; border-radius:10px; border:2px solid; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;">
                  <svg width="16" height="16" fill="none" stroke="#27AE60" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  <span style="font-size:0.82rem; font-weight:600;" :style="status==='successful' ? 'color:#27AE60;' : 'color:#555;'">Successful</span>
                </button>
                <button type="button" @click="status='failed'"
                  :style="status==='failed' ? 'border-color:rgba(192,57,43,0.5); background:rgba(192,57,43,0.05); box-shadow:0 0 0 3px rgba(192,57,43,0.08);' : 'border-color:var(--border-light); background:#fafbff;'"
                  style="display:flex; align-items:center; gap:0.65rem; padding:0.75rem 1rem; border-radius:10px; border:2px solid; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;">
                  <svg width="16" height="16" fill="none" stroke="#E74C3C" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  <span style="font-size:0.82rem; font-weight:600;" :style="status==='failed' ? 'color:#C0392B;' : 'color:#555;'">Failed</span>
                </button>
              </div>
            </template>
          </div>

          {{-- Static fields --}}
          @foreach([
  ['Units Created', $donation->bloodUnits->count()],
  ['Recorded By',  $donation->staff->name ?? '—'],
  ['Linked Appt.', $donation->appointment ? $donation->appointment->appointment_date->format('M d, Y') : 'Walk-in'],
] as [$l, $v])
            <div style="display:flex; justify-content:space-between; padding:0.7rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
              <span style="font-size:0.82rem; color:#888;">{{ $l }}</span>
              <span style="font-size:0.85rem; color:#1a1a2e; font-weight:600; text-align:right;">{{ $v }}</span>
            </div>
          @endforeach

          {{-- Remarks --}}
          <div style="padding:0.7rem 0;">
            <div style="display:flex; justify-content:space-between; align-items:center;">
              <span style="font-size:0.82rem; color:#888;">Remarks</span>
            </div>
            <template x-if="!editing">
              <div style="margin-top:0.4rem;">
                @if($donation->remarks)
                  <div style="padding:0.85rem; background:#fafbff; border-radius:10px; border:1px solid var(--border-light);">
                    <p style="font-size:0.875rem; color:#555; line-height:1.6; margin:0;">{{ $donation->remarks }}</p>
                  </div>
                @else
                  <div class="profile-value" style="color:#bbb; font-style:italic;">No remarks</div>
                @endif
              </div>
            </template>
            <template x-if="editing">
              <div style="margin-top:0.5rem;">
                <textarea name="remarks" rows="3"
                  class="form-input form-input-light"
                  placeholder="Additional notes...">{{ old('remarks', $donation->remarks) }}</textarea>
                <div x-show="status === 'failed'" style="font-size:0.72rem; color:#E74C3C; margin-top:0.25rem;">Required when status is Failed.</div>
                @error('remarks')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- Save button --}}
          <div x-show="editing" x-cloak style="margin-top:1rem; text-align:right;">
            <button type="submit" class="btn btn-dash-primary">
              <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
              Save Changes
            </button>
          </div>

        </form>
      </div>
    </div>

    {{-- Donor Information --}}
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Donor Information</h3>
        <a href="{{ route($routePrefix . '.donors.show', $donation->donor) }}" style="font-size:0.78rem; color:var(--primary); text-decoration:none; font-weight:600;">View profile →</a>
      </div>
      <div class="dash-card-body">
        <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem;">
          <div class="avatar-initials" style="width:48px; height:48px; font-size:1rem; overflow:hidden; border:2px solid #fff; outline:2px solid rgba(192,57,43,0.15); box-shadow:0 2px 8px rgba(192,57,43,0.25);">
    @if($donation->donor->avatar)
        <img src="{{ asset($donation->donor->avatar) }}" alt="{{ $donation->donor->name }}" style="width:100%; height:100%; object-fit:cover;"/>
    @else
        {{ strtoupper(substr($donation->donor->name, 0, 1)) }}
    @endif
</div>
          <div>
            <div style="font-weight:700; font-size:1rem; color:#1a1a2e;">{{ $donation->donor->name }}</div>
            <div style="font-size:0.78rem; color:#888; display:flex; align-items:center; gap:0.5rem;">
              <x-blood-type-badge :type="$donation->donor->bloodType->type_name??'?'"/>
              <span>Age {{ $donation->donor->date_of_birth->age }}</span>
            </div>
          </div>
        </div>
        @foreach([
          ['Email',           $donation->donor->user->email],
          ['Contact',         $donation->donor->contact_number ?? '—'],
          ['Total Donations',  $donation->donor->donations->count() . ' times'],
        ] as [$l, $v])
          <div style="display:flex; justify-content:space-between; padding:0.6rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
            <span style="font-size:0.82rem; color:#888;">{{ $l }}</span>
            <span style="font-size:0.85rem; color:#1a1a2e; font-weight:500;">{{ $v }}</span>
          </div>
        @endforeach
      </div>
    </div>

  </div>

  {{-- Screening record --}}
  @if($donation->screening)
    <div class="dash-card" style="margin-bottom:1.5rem;">
      <div class="dash-card-header"><h3 class="dash-card-title">Pre-donation Screening</h3></div>
      <div class="dash-card-body">
        <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:1.25rem;">
          @foreach([
            ['Blood Pressure',  $donation->screening->blood_pressure ?? '—', null],
            ['Hemoglobin',      $donation->screening->hemoglobin_level ? $donation->screening->hemoglobin_level.' g/dL' : '—', null],
            ['Weight',          $donation->screening->weight ? $donation->screening->weight.' kg' : '—', null],
            ['Eligibility',     null, $donation->screening->eligibility_status->value],
            ['Screened By',     $donation->screening->staff->name ?? '—', null],
          ] as [$l, $v, $status])
            <div style="text-align:center; padding:1rem; background:#f8f9fc; border-radius:12px;">
              <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#bbb; margin-bottom:0.4rem;">{{ $l }}</div>
              @if($status)
                <x-status-badge :status="$status"/>
              @else
                <div style="font-size:1rem; font-weight:700; color:#1a1a2e;">{{ $v }}</div>
              @endif
            </div>
          @endforeach
        </div>
        @if($donation->screening->remarks)
          <div style="margin-top:1rem; padding:0.85rem; background:#fafbff; border-radius:10px; border:1px solid var(--border-light); font-size:0.85rem; color:#555;">
            <strong style="color:#888; font-size:0.75rem; text-transform:uppercase; letter-spacing:0.06em;">Remarks:</strong> {{ $donation->screening->remarks }}
          </div>
        @endif
      </div>
    </div>
  @endif

  {{-- Blood units created --}}
  @if($donation->bloodUnits->count())
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">Blood Units Created</h3>
        <span style="font-size:0.78rem; color:#bbb;">{{ $donation->bloodUnits->count() }} unit(s)</span>
      </div>
      <div class="table-container" style="border:none; border-radius:0;">
        <table class="data-table">
          <thead>
            <tr><th>Unit ID</th><th>Blood Type</th><th>Stored</th><th>Expiry</th><th>Status</th></tr>
          </thead>
          <tbody>
            @foreach($donation->bloodUnits as $unit)
              <tr>
                <td><span style="font-family:var(--font-mono); font-size:0.8rem; color:#888; background:#f5f5f5; padding:0.2rem 0.5rem; border-radius:6px;">#{{ str_pad($unit->blood_unit_id,5,'0',STR_PAD_LEFT) }}</span></td>
                <td><x-blood-type-badge :type="$unit->bloodType->type_name"/></td>
                <td style="font-size:0.82rem; color:#888;">{{ $unit->stored_date->format('M d, Y') }}</td>
                <td style="font-size:0.82rem; color:#888;">{{ $unit->expiry_date->format('M d, Y') }}</td>
                <td><x-status-badge :status="$unit->status->value" size="sm"/></td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  @endif

  <style>
    [x-cloak] { display: none !important; }
    .profile-value {
      font-size: 0.875rem;
      color: #1a1a2e;
      padding: 0.7rem 0.9rem;
      background: #f8f8f8;
      border: 1.5px solid var(--border-light);
      border-radius: 10px;
      min-height: 42px;
      display: flex;
      align-items: center;
    }
    @media (max-width: 768px) {
      div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
      div[style*="grid-template-columns:repeat(5"] { grid-template-columns: repeat(2,1fr) !important; }
    }
  </style>

@push('scripts')
<script>
  document.addEventListener('DOMContentLoaded', () => {
    document.addEventListener('click', () => {
      const dateEl = document.querySelector('.flatpickr-date');
      if (dateEl && !dateEl._flatpickr && typeof flatpickr !== 'undefined') {
        flatpickr(dateEl, {
          dateFormat:    'm/d/Y',
          maxDate:       'today',
          disableMobile: true,
          allowInput:    false,
        });
      }
    });
  });
</script>
@endpush

</x-app-layout>
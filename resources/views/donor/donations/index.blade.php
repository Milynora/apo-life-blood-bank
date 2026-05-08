<x-app-layout title="My Donation History">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('donor.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>Donation History</span>
      </div>
      <h1 class="page-title">My Donation History</h1>
      <p class="page-subtitle">Every donation you've made and its impact.</p>
    </div>
  </div>

  {{-- Impact summary banner --}}
<div style="background:#fff; border:1px solid var(--border-light); border-radius:var(--radius-xl); padding:1.75rem 2.5rem; margin-bottom:2rem; display:grid; grid-template-columns:repeat(3,1fr); gap:2rem; position:relative; overflow:hidden;">

  {{-- Dot grid --}}
  <div style="position:absolute; inset:0; background-image:radial-gradient(circle, rgba(192,57,43,0.2) 1px, transparent 1px); background-size:20px 20px; pointer-events:none;"></div>

  @foreach([
    [$stats['total_donations'], 'Total Donations', 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
    [number_format($stats['total_volume']) . ' mL', 'Total Volume', 'M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z'],
    [$stats['total_donations'] * 3, 'Lives Saved', 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
  ] as [$value, $label, $icon])
    <div style="text-align:center; position:relative; z-index:1;">
      <div style="width:44px; height:44px; border-radius:12px; background:rgba(192,57,43,0.08); border:1px solid rgba(192,57,43,0.15); display:flex; align-items:center; justify-content:center; margin:0 auto 0.75rem;">
        <svg width="20" height="20" fill="none" stroke="var(--primary)" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="{{ $icon }}"/></svg>
      </div>
      <div style="font-family:var(--font-display); font-size:1.8rem; font-weight:800; color:#1a1a2e; line-height:1; margin-bottom:0.3rem;">{{ $value }}</div>
      <div style="font-size:0.75rem; color:#999; text-transform:uppercase; letter-spacing:0.06em;">{{ $label }}</div>
    </div>
  @endforeach
</div>

  {{-- Donations list --}}
  @if($donations->isEmpty())
    <div class="dash-card">
      <div class="dash-card-body" style="padding:4rem 2rem;">
        <x-empty-state
          title="No donations recorded yet"
          message="Your donation history will appear here. Request your first appointment to get started."
          :action="route('donor.appointments.create')"
          action-label="Request Appointment"/>
      </div>
    </div>
  @else
    {{-- Timeline view --}}
    <div style="display:flex; flex-direction:column; gap:1.25rem;">
      @foreach($donations as $d)
        <div class="dash-card">
          <div style="padding:1.5rem; display:grid; grid-template-columns:auto 1fr auto; gap:1.5rem; align-items:start;">

            {{-- Date badge --}}
            <div style="text-align:center;">
              <div style="width:56px; height:60px; background:{{ $d->status->value === 'successful' ? 'linear-gradient(135deg,var(--primary),#e94560)' : '#e0e0e0' }}; border-radius:14px; display:flex; flex-direction:column; align-items:center; justify-content:center; box-shadow:{{ $d->status->value === 'successful' ? '0 4px 12px rgba(192,57,43,0.3)' : 'none' }};">
                <div style="font-size:1.2rem; font-weight:800; color:#fff; line-height:1; font-family:var(--font-display);">{{ $d->donation_date->format('d') }}</div>
                <div style="font-size:0.6rem; font-weight:600; color:rgba(255,255,255,0.85); text-transform:uppercase; letter-spacing:0.05em;">{{ $d->donation_date->format('M Y') }}</div>
              </div>
            </div>

            {{-- Main info --}}
            <div>
              <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap; margin-bottom:0.5rem;">
                <span style="font-weight:700; font-size:1rem; color:#1a1a2e;">{{ $d->donation_date->format('l, F d, Y') }}</span>
                <x-status-badge :status="$d->status->value" size="sm"/>
              </div>

              <div style="display:grid; grid-template-columns:repeat(3,auto); gap:1.25rem; margin-bottom:0.75rem;">
                <div>
                  <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#bbb; margin-bottom:0.2rem;">Volume</div>
                  <div style="font-weight:700; color:#1a1a2e; font-size:0.95rem;">{{ $d->volume }} <span style="font-size:0.75rem; font-weight:400; color:#999;">mL</span></div>
                </div>
                <div>
                  <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#bbb; margin-bottom:0.2rem;">Units Created</div>
                  <div style="font-weight:700; color:#1a1a2e; font-size:0.95rem;">{{ $d->bloodUnits->count() }}</div>
                </div>
                <div>
                  <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#bbb; margin-bottom:0.2rem;">Recorded By</div>
                  <div style="font-size:0.85rem; color:#666;">{{ $d->staff->name ?? '—' }}</div>
                </div>
              </div>

              {{-- Screening result --}}
              @if($d->screening)
                <div style="display:flex; align-items:center; gap:1.25rem; padding:0.7rem 1rem; background:rgba(0,0,0,0.025); border-radius:10px; border:1px solid var(--border-light); font-size:0.8rem; color:#666; flex-wrap:wrap;">
                  <span style="font-weight:600; color:#555;">Screening:</span>
                  <x-status-badge :status="$d->screening->eligibility_status->value" size="sm"/>
                  @if($d->screening->hemoglobin_level)
                    <span>Hgb: <strong style="color:#333;">{{ $d->screening->hemoglobin_level }} g/dL</strong></span>
                  @endif
                  @if($d->screening->weight)
                    <span>Weight: <strong style="color:#333;">{{ $d->screening->weight }} kg</strong></span>
                  @endif
                  @if($d->screening->blood_pressure)
                    <span>BP: <strong style="color:#333;">{{ $d->screening->blood_pressure }}</strong></span>
                  @endif
                </div>
              @endif
            </div>

            {{-- Blood units sidebar --}}
            @if($d->bloodUnits->count())
              <div style="min-width:120px; text-align:center; padding:0.75rem; background:rgba(192,57,43,0.04); border-radius:12px; border:1px solid rgba(192,57,43,0.12);">
                <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#C0392B; margin-bottom:0.5rem;">Blood Units</div>
                @foreach($d->bloodUnits as $unit)
                  <div style="display:flex; align-items:center; justify-content:space-between; gap:0.5rem; padding:0.25rem 0; font-size:0.75rem;">
                    <x-blood-type-badge :type="$unit->bloodType->type_name"/>
                    <x-status-badge :status="$unit->status->value" size="sm"/>
                  </div>
                @endforeach
              </div>
            @endif

          </div>
        </div>
      @endforeach
    </div>

    <x-pagination :paginator="$donations"/>
  @endif

  <style>
    @media (max-width: 768px) {
      div[style*="grid-template-columns:repeat(3,1fr)"] { grid-template-columns: 1fr 1fr !important; }
      div[style*="grid-template-columns:auto 1fr auto"]  { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
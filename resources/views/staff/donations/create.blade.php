<x-app-layout title="Record Donation">

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
        <span>Record New</span>
      </div>
      <h1 class="page-title">Record Donation</h1>
      <p class="page-subtitle">Record a new blood donation for a donor.</p>
    </div>
    <a href="{{ route($routePrefix . '.donations.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  <div style="display:grid; grid-template-columns:1.4fr 1fr; gap:1.75rem; align-items:start;">

    {{-- LEFT: One big card --}}
    <form method="POST" action="{{ route($routePrefix . '.donations.store') }}"
      x-data="{ status: '{{ old('status', 'successful') }}' }">
      @csrf

      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Donation Details</h3>
        </div>
        <div class="dash-card-body" style="display:flex; flex-direction:column; gap:1.5rem;">

          {{-- Section: Donor & Screening --}}
          <div>
            <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#aaa; margin-bottom:1rem;">Donor & Screening</div>

            {{-- Donor select --}}
            <div class="form-group" style="margin-bottom:1.25rem; position:relative;"
              x-data="{
                open: false,
                search: '',
                selectedId: '{{ old('donor_id', $selectedDonor?->donor_id ?? '') }}',
                selectedName: '{{ $selectedDonor ? $selectedDonor->user->name : '' }}',
                donors: {{ Js::from($donors
                    ->filter(fn($d) => $screenings->contains('donor_id', $d->donor_id))
                    ->sortBy(fn($d) => $d->user->name)
                    ->values()
                    ->map(fn($d) => [
                        'id'   => $d->donor_id,
                        'name' => $d->user->name,
                    ])) }},
                get filtered() {
                  if (!this.search) return this.donors;
                  return this.donors.filter(d => d.name.toLowerCase().includes(this.search.toLowerCase()));
                },
                select(donor) {
                  this.selectedId   = donor.id;
                  this.selectedName = donor.name;
                  this.search       = donor.name;
                  this.open         = false;
                  window.location.href = '{{ route($routePrefix . '.donations.create') }}?donor_id=' + donor.id;
                }
              }"
              @click.outside="open = false"
              x-init="search = selectedName">

              <label class="form-label">Donor <span style="color:#E74C3C;">*</span></label>
              <input type="hidden" name="donor_id" :value="selectedId" required/>

              <input type="text" x-model="search"
                @focus="open = true"
                @input="open = true; selectedId = ''; if (search === '') { selectedName = ''; window.history.replaceState({}, '', '{{ route($routePrefix . '.donations.create') }}'); }"
                @keydown.escape="open = false"
                @blur="if (!selectedId) { search = ''; selectedName = ''; window.history.replaceState({}, '', '{{ route($routePrefix . '.donations.create') }}'); }"
                placeholder="Type to search donor…"
                class="form-input form-input-light"
                autocomplete="off"/>

              {{-- Dropdown --}}
              <div x-show="open && filtered.length > 0" x-transition
                style="position:absolute; z-index:50; width:100%; background:#fff; border:1.5px solid var(--border-light); border-radius:12px; margin-top:4px; box-shadow:0 8px 24px rgba(0,0,0,0.1); overflow:hidden;">
                <div style="max-height:220px; overflow-y:auto;">
                  <template x-for="donor in filtered" :key="donor.id">
                    <div @mousedown.prevent="select(donor)"
                      style="padding:0.65rem 1rem; cursor:pointer; font-size:0.875rem; transition:background 0.15s;"
                      onmouseover="this.style.background='rgba(0,0,0,0.04)'"
                      onmouseout="this.style.background='transparent'">
                      <span x-text="donor.name" style="color:#1a1a2e;"></span>
                    </div>
                  </template>
                </div>
              </div>

              @error('donor_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            {{-- Linked Screening --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Linked Screening</label>
              @if($selectedScreening)
                <input type="hidden" name="screening_id" value="{{ $selectedScreening->screening_id }}"/>
                <div style="background:#f0fff4; border:1.5px solid rgba(39,174,96,0.3); border-radius:10px; padding:1rem 1.25rem;">
                  <div style="display:flex; align-items:center; gap:0.65rem; margin-bottom:0.75rem;">
                    <svg width="16" height="16" fill="none" stroke="#27AE60" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span style="font-size:0.82rem; font-weight:700; color:#27AE60;">Eligible Screening Found</span>
                  </div>
                  <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:0.75rem;">
                    @foreach([
                      ['Screened',    optional($selectedScreening->screening_date)->format('M d, Y') ?? '—'],
                      ['BP',          $selectedScreening->blood_pressure ?? '—'],
                      ['Hemoglobin',  $selectedScreening->hemoglobin_level ? $selectedScreening->hemoglobin_level . ' g/dL' : '—'],
                      ['Weight',      $selectedScreening->weight ? $selectedScreening->weight . ' kg' : '—'],
                      ['Type',        $selectedScreening->appointment_id ? 'Appointment' : 'Walk-in'],
                      ['Screened By', $selectedScreening->staff->name ?? '—'],
                    ] as [$l, $v])
                      <div style="background:#fff; border-radius:8px; padding:0.55rem 0.75rem;">
                        <div style="font-size:0.65rem; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:#aaa; margin-bottom:0.15rem;">{{ $l }}</div>
                        <div style="font-size:0.82rem; font-weight:600; color:#1a1a2e;">{{ $v }}</div>
                      </div>
                    @endforeach
                  </div>
                </div>
              @elseif(request('donor_id'))
                <div style="background:rgba(192,57,43,0.05); border:1.5px solid rgba(192,57,43,0.2); border-radius:10px; padding:1rem 1.25rem; display:flex; align-items:center; gap:0.65rem;">
                  <svg width="16" height="16" fill="none" stroke="#E74C3C" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                  <div>
                    <div style="font-size:0.82rem; font-weight:700; color:#E74C3C;">No eligible screening found</div>
                    <div style="font-size:0.75rem; color:#888; margin-top:0.1rem;">
                      This donor must complete a pre-donation screening first.
                      <a href="{{ route($routePrefix . '.screenings.create') }}?donor_id={{ request('donor_id') }}"
                        style="color:var(--primary); font-weight:600; text-decoration:none; margin-left:0.3rem;">
                        Record Screening →
                      </a>
                    </div>
                  </div>
                </div>
              @else
                <div style="font-size:0.875rem; padding:0.7rem 0.9rem; background:#f8f8f8; border:1.5px solid var(--border-light); border-radius:10px; min-height:42px; display:flex; align-items:center; color:#aaa; font-style:italic;">
                  Select a donor to auto-load their screening.
                </div>
              @endif
              @error('screening_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>
          </div>

          {{-- Section: Details --}}
          <div>
            <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#aaa; margin-bottom:1rem;">Donation Info</div>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; margin-bottom:1.25rem;">

              {{-- Donation date --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Donation Date <span style="color:#E74C3C;">*</span></label>
                <input type="text" name="donation_date"
                  value="{{ old('donation_date', today()->format('m/d/Y')) }}"
                  class="form-input form-input-light flatpickr-date" placeholder="MM/DD/YYYY"
                  autocomplete="off"/>
                @error('donation_date')<div class="form-error">{{ $message }}</div>@enderror
              </div>

              {{-- Volume --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Volume (mL) <span style="color:#E74C3C;">*</span></label>
                <input type="number" name="volume" value="{{ old('volume', 450) }}"
                  min="200" max="550" required
                  class="form-input form-input-light" placeholder="450"/>
                <div style="font-size:0.7rem; color:#aaa; margin-top:0.25rem;">Standard: 450 mL · Range: 200–550</div>
                @error('volume')<div class="form-error">{{ $message }}</div>@enderror
              </div>

              {{-- Blood Type --}}
              <div class="form-group" style="margin-bottom:0;">
                <label class="form-label">Blood Type <span style="color:#E74C3C;">*</span></label>
                @php
                  $preselectedBt = old('blood_type_id',
                    $selectedScreening?->donor?->blood_type_id
                    ?? $selectedDonor?->blood_type_id
                    ?? ''
                  );
                @endphp
                <select name="blood_type_id" required class="form-input form-input-light">
                  <option value="">Select blood type</option>
                  @foreach(\App\Models\BloodType::orderBy('type_name')->get() as $bt)
                    <option value="{{ $bt->blood_type_id }}"
                      {{ $preselectedBt == $bt->blood_type_id ? 'selected' : '' }}>
                      {{ $bt->type_name }}
                    </option>
                  @endforeach
                </select>
                @error('blood_type_id')<div class="form-error">{{ $message }}</div>@enderror
              </div>

            </div>

            {{-- Status --}}
            <div class="form-group" style="margin-bottom:0;">
              <label class="form-label">Donation Status <span style="color:#E74C3C;">*</span></label>
              <input type="hidden" name="status" :value="status"/>
              <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-top:0.3rem;">
                <button type="button" @click="status='successful'"
                  :class="status==='successful' ? 'elig-btn-active-green' : 'elig-btn-inactive'"
                  class="elig-btn">
                  <div class="elig-btn-icon" style="background:rgba(39,174,96,0.12); border-color:rgba(39,174,96,0.35);">
                    <svg width="20" height="20" fill="none" stroke="#27AE60" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </div>
                  <div>
                    <div class="elig-btn-title">Successful</div>
                    <div class="elig-btn-sub">Donation completed normally</div>
                  </div>
                  <div class="elig-btn-check" x-show="status==='successful'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                </button>
                <button type="button" @click="status='failed'"
                  :class="status==='failed' ? 'elig-btn-active-red' : 'elig-btn-inactive'"
                  class="elig-btn">
                  <div class="elig-btn-icon" style="background:rgba(192,57,43,0.1); border-color:rgba(192,57,43,0.3);">
                    <svg width="20" height="20" fill="none" stroke="#E74C3C" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                  </div>
                  <div>
                    <div class="elig-btn-title">Failed</div>
                    <div class="elig-btn-sub">Draw was unsuccessful</div>
                  </div>
                  <div class="elig-btn-check" x-show="status==='failed'">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                  </div>
                </button>
              </div>
            </div>
          </div>

          {{-- Section: Remarks --}}
          <div>
            <div x-show="status === 'failed'">
              <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#aaa; margin-bottom:1rem;">Reason for Failure <span style="color:#E74C3C;">*</span></div>
              <textarea name="remarks" rows="3"
                class="form-input form-input-light"
                placeholder="e.g. Donor fainted mid-draw, needle issue, donor withdrew consent…">{{ old('remarks') }}</textarea>
              @error('remarks')<div class="form-error">{{ $message }}</div>@enderror
            </div>
            <div x-show="status === 'successful'">
              <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem;">
                <div style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:#aaa;">Remarks</div>
                <span style="font-size:0.75rem; color:#bbb;">Optional</span>
              </div>
              <textarea name="remarks" rows="2"
                class="form-input form-input-light"
                placeholder="Any notes about this donation…">{{ old('remarks') }}</textarea>
            </div>
          </div>

          {{-- Actions --}}
          <div style="display:flex; gap:0.85rem; justify-content:flex-end;">
            <a href="{{ route($routePrefix . '.donations.index') }}"
              style="padding:0.7rem 1.4rem; border-radius:10px; border:1.5px solid var(--border-light); background:#fff; color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; display:inline-flex; align-items:center;">
              Cancel
            </a>
            <button type="submit"
              class="btn btn-dash-primary"
              style="border-radius:10px; padding:0.7rem 1.75rem;"
              @if(!$selectedScreening) disabled style="opacity:0.5; cursor:not-allowed;" @endif>
              <svg width="16" height="16" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
              Record Donation
            </button>
          </div>

        </div>
      </div>

    </form>

    {{-- RIGHT: Guidelines + Donor info --}}
    <div style="display:flex; flex-direction:column; gap:1.5rem;">

      {{-- Donation Guidelines --}}
      <div class="dash-card">
        <div class="dash-card-header"><h3 class="dash-card-title">Donation Guidelines</h3></div>
        <div class="dash-card-body" style="padding:1.25rem 1.5rem;">
          <div style="display:flex; flex-direction:column; gap:0.75rem;">
            @foreach([
              'Donor must pass pre-donation screening',
              'Minimum 56 days since last whole blood donation',
              'Hemoglobin ≥ 12.5 g/dL required',
              'Minimum weight of 50 kg',
              'No fever, illness, or active infection',
            ] as $item)
              <div style="display:flex; gap:0.65rem; align-items:flex-start; font-size:0.82rem; color:#555;">
                <svg width="13" height="13" fill="none" stroke="#27AE60" stroke-width="2.5" viewBox="0 0 24 24" style="flex-shrink:0; margin-top:1px;"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                {{ $item }}
              </div>
            @endforeach
          </div>
        </div>
      </div>

      {{-- Selected Donor — shows after donor selected --}}
      @if($selectedDonor)
        <div class="dash-card">
          <div class="dash-card-header"><h3 class="dash-card-title">Selected Donor</h3></div>
          <div class="dash-card-body">

            {{-- Avatar + name --}}
            <div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.25rem;">
              <div style="width:52px; height:52px; border-radius:50%; overflow:hidden; background:linear-gradient(135deg,var(--primary),#e94560); display:flex; align-items:center; justify-content:center; font-size:1.1rem; font-weight:800; color:#fff; flex-shrink:0;">
                @if($selectedDonor->avatar)
                  <img src="{{ asset($selectedDonor->avatar) }}" style="width:100%; height:100%; object-fit:cover;"/>
                @else
                  {{ strtoupper(substr($selectedDonor->user->name, 0, 1)) }}
                @endif
              </div>
              <div>
                <div style="font-weight:700; font-size:1rem; color:#1a1a2e;">{{ $selectedDonor->user->name }}</div>
                <div style="display:flex; align-items:center; gap:0.5rem; margin-top:0.25rem; flex-wrap:wrap;">
                  @if($selectedDonor->bloodType)
                    <x-blood-type-badge :type="$selectedDonor->bloodType->type_name"/>
                  @else
                    <span style="font-size:0.75rem; color:#bbb; font-style:italic;">Unknown blood type</span>
                  @endif
                  <span style="font-size:0.78rem; color:#888;">Age {{ $selectedDonor->date_of_birth->age }}</span>
                </div>
              </div>
            </div>

            {{-- Stat boxes --}}
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:0.75rem; margin-bottom:1.25rem;">
              <div style="text-align:center; padding:0.85rem; background:rgba(192,57,43,0.06); border:1.5px solid rgba(192,57,43,0.18); border-radius:12px;">
                <div style="font-size:1.6rem; font-weight:800; color:var(--primary); font-family:var(--font-display); line-height:1;">{{ $selectedDonor->donations()->count() }}</div>
                <div style="font-size:0.72rem; color:#888; margin-top:0.3rem; font-weight:500;">Total Donations</div>
              </div>
              <div style="text-align:center; padding:0.85rem; background:rgba(41,128,185,0.06); border:1.5px solid rgba(41,128,185,0.18); border-radius:12px;">
                <div style="font-size:1.6rem; font-weight:800; color:#2980B9; font-family:var(--font-display); line-height:1;">{{ $selectedDonor->screenings()->count() }}</div>
                <div style="font-size:0.72rem; color:#888; margin-top:0.3rem; font-weight:500;">Total Screenings</div>
              </div>
            </div>

            {{-- Details --}}
            @foreach([
              ['Age',          $selectedDonor->date_of_birth->age . ' years'],
              ['Last Donation', $selectedDonor->donations()->latest('donation_date')->first()?->donation_date->format('M d, Y') ?? 'None'],
            ] as [$l, $v])
              <div style="display:flex; justify-content:space-between; padding:0.6rem 0; border-bottom:1px solid rgba(0,0,0,0.04);">
                <span style="font-size:0.82rem; color:#888;">{{ $l }}</span>
                <span style="font-size:0.85rem; font-weight:600; color:#1a1a2e;">{{ $v }}</span>
              </div>
            @endforeach

          </div>
        </div>
      @endif

    </div>

  </div>

  <style>
    @media (max-width: 900px) {
      div[style*="grid-template-columns:1.4fr 1fr"] { grid-template-columns: 1fr !important; }
      div[style*="grid-template-columns:1fr 1fr 1fr"] { grid-template-columns: 1fr 1fr !important; }
    }

    .elig-btn { display:flex; align-items:center; gap:1rem; padding:1rem 1.25rem; border-radius:14px; border:2px solid; cursor:pointer; font-family:var(--font-body); transition:all 0.25s; text-align:left; width:100%; background:#fff; }
    .elig-btn-icon { width:46px; height:46px; border-radius:12px; border:1.5px solid; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .elig-btn-title { font-size:0.95rem; font-weight:700; color:#1a1a2e; margin-bottom:0.2rem; }
    .elig-btn-sub { font-size:0.75rem; color:#999; }
    .elig-btn-check { margin-left:auto; width:26px; height:26px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; }
    .elig-btn-inactive { border-color:var(--border-light); background:#fafbff; }
    .elig-btn-inactive:hover { border-color:#ccc; background:#f5f6fa; }
    .elig-btn-active-green { border-color:rgba(39,174,96,0.5) !important; background:rgba(39,174,96,0.06) !important; box-shadow:0 0 0 3px rgba(39,174,96,0.1); }
    .elig-btn-active-green .elig-btn-title { color:#27AE60; }
    .elig-btn-active-green .elig-btn-check { background:#27AE60; color:#fff; }
    .elig-btn-active-red { border-color:rgba(192,57,43,0.5) !important; background:rgba(192,57,43,0.05) !important; box-shadow:0 0 0 3px rgba(192,57,43,0.08); }
    .elig-btn-active-red .elig-btn-title { color:#C0392B; }
    .elig-btn-active-red .elig-btn-check { background:#C0392B; color:#fff; }
  </style>

</x-app-layout>
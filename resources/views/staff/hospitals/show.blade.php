<x-app-layout title="Hospital Profile">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('staff.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <a href="{{ route('staff.donors.index') }}">Donors & Hospitals</a>
        <span class="breadcrumb-sep">›</span>
        <span>{{ $hospital->hospital_name }}</span>
      </div>
      <h1 class="page-title">Hospital Profile</h1>
    </div>

    <a href="{{ route('staff.donors.index') }}"
      style="display:inline-flex; align-items:center; gap:0.5rem; padding:0.6rem 1.2rem; border-radius:10px; border:1.5px solid var(--border-light); color:#555; text-decoration:none; font-size:0.875rem; font-weight:600; background:#fff; transition:all 0.2s;"
      onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'"
      onmouseout="this.style.borderColor='var(--border-light)'; this.style.color='#555'">
      <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
      Back
    </a>
  </div>

  {{-- Profile header card --}}
  <div class="dash-card" style="margin-bottom:1.5rem; position:relative; overflow:hidden;">

  {{-- Diagonal stripes --}}
  <div style="position:absolute; top:-80px; left:-80px; width:350px; height:350px; background:rgba(83,52,131,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
  <div style="position:absolute; top:-50px; left:-50px; width:250px; height:250px; background:rgba(83,52,131,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
  <div style="position:absolute; bottom:-80px; left:200px; width:300px; height:300px; background:rgba(142,68,173,0.04); transform:rotate(45deg); border-radius:45px; pointer-events:none;"></div>
  <div style="position:absolute; top:-60px; right:-60px; width:350px; height:350px; background:rgba(83,52,131,0.05); transform:rotate(45deg); border-radius:50px; pointer-events:none;"></div>
  <div style="position:absolute; top:-40px; right:-40px; width:250px; height:250px; background:rgba(142,68,173,0.04); transform:rotate(45deg); border-radius:35px; pointer-events:none;"></div>
  <div style="position:absolute; bottom:-60px; right:200px; width:280px; height:280px; background:rgba(83,52,131,0.04); transform:rotate(45deg); border-radius:40px; pointer-events:none;"></div>
  <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) rotate(45deg); width:200px; height:200px; background:rgba(142,68,173,0.03); border-radius:30px; pointer-events:none;"></div>

  <div style="padding:1.75rem 2rem; display:flex; align-items:center; gap:1.5rem; flex-wrap:wrap;">

      <div style="width:72px; height:72px; border-radius:16px; background:linear-gradient(135deg,#533483,#9B59B6); display:flex; align-items:center; justify-content:center; flex-shrink:0; box-shadow:0 6px 20px rgba(83,52,131,0.3);">
  <svg width="32" height="32" fill="none" stroke="#fff" stroke-width="1.5" viewBox="0 0 24 24">
    <path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
  </svg>
</div>

      {{-- Name + meta --}}
      <div style="flex:1; min-width:0;">
        <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap; margin-bottom:0.4rem;">
    <h2 style="font-family:var(--font-display); font-size:1.5rem; font-weight:700; color:#1a1a2e; margin:0;">{{ $hospital->hospital_name }}</h2>
    <span class="badge badge-role-hospital">Hospital</span>
    <x-status-badge :status="$hospital->user->status->value"/>
</div>
<div style="display:flex; align-items:center; gap:1.25rem; flex-wrap:wrap;">
    <span style="display:flex; align-items:center; gap:0.4rem; font-size:0.82rem; color:#888;">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        {{ $hospital->user->email }}
    </span>
    <span style="display:flex; align-items:center; gap:0.4rem; font-size:0.82rem; color:#888;">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
        {{ $hospital->license_number }}
    </span>
</div>
      </div>

      {{-- Registered --}}
      <div style="text-align:right;">
        <div style="font-size:0.72rem; color:#bbb; text-transform:uppercase; letter-spacing:0.06em; margin-bottom:0.3rem;">Registered</div>
        <div style="font-size:0.9rem; font-weight:600; color:#1a1a2e;">{{ $hospital->user->created_at->format('M d, Y') }}</div>
      </div>

    </div>
  </div>

  {{-- Tabs --}}
  <div x-data="{ tab: 'info' }">

    <div class="tab-nav" style="margin-bottom:1.25rem;">
      <button class="tab-btn" :class="tab==='info'?'active':''" @click="tab='info'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        Hospital Info
      </button>
      <button class="tab-btn" :class="tab==='requests'?'active':''" @click="tab='requests'">
        <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Blood Requests
        <span style="font-size:0.72rem; background:rgba(142,68,173,0.1); color:#8E44AD; border-radius:20px; padding:0.05rem 0.45rem; margin-left:0.25rem;">{{ $hospital->requests->count() }}</span>
      </button>
    </div>

    {{-- Info tab --}}
<div x-show="tab==='info'" x-cloak>
  <div class="dash-card" x-data="{ editing: false }">
    <div class="dash-card-header">
      <h3 class="dash-card-title">Hospital Details</h3>
      <div style="display:flex; gap:0.65rem;">
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

    <form method="POST" action="{{ route('staff.hospitals.update', $hospital) }}">
      @csrf @method('PATCH')
      <div class="dash-card-body">

        <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; margin-bottom:1rem;">

          {{-- Hospital Name --}}
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Hospital Name</label>
            <template x-if="!editing">
              <div class="profile-value">{{ $hospital->hospital_name }}</div>
            </template>
            <template x-if="editing">
              <div>
                <input type="text" name="hospital_name"
                  value="{{ old('hospital_name', $hospital->hospital_name) }}" required
                  class="form-input form-input-light"/>
                @error('hospital_name')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- Email --}}
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Email</label>
            <template x-if="!editing">
              <div class="profile-value">{{ $hospital->user->email }}</div>
            </template>
            <template x-if="editing">
              <div>
                <input type="email" name="email"
                  value="{{ old('email', $hospital->user->email) }}" required
                  class="form-input form-input-light"/>
                @error('email')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- License Number --}}
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">License Number</label>
            <template x-if="!editing">
              <div class="profile-value">{{ $hospital->license_number }}</div>
            </template>
            <template x-if="editing">
              <div>
                <input type="text" name="license_number"
                  value="{{ old('license_number', $hospital->license_number) }}" required
                  class="form-input form-input-light"/>
                @error('license_number')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

          {{-- Contact Number --}}
          <div class="form-group" style="margin-bottom:0;">
            <label class="form-label">Contact Number</label>
            <template x-if="!editing">
              <div class="profile-value">{{ $hospital->contact_number ?? '—' }}</div>
            </template>
            <template x-if="editing">
              <div>
                <input type="text" name="contact_number"
                  value="{{ old('contact_number', $hospital->contact_number) }}"
                  class="form-input form-input-light" placeholder="09XXXXXXXXX or 082XXXXXXX"
                  maxlength="11"
                  oninput="this.value = this.value.replace(/[^0-9]/g, '').slice(0, 11)"/>
                @error('contact_number')<div class="form-error">{{ $message }}</div>@enderror
              </div>
            </template>
          </div>

        </div>

        {{-- Address --}}
        <div class="form-group" style="margin-bottom:1rem;">
          <label class="form-label">Complete Address</label>
          <template x-if="!editing">
            <div class="profile-value">{{ $hospital->address ?? '—' }}</div>
          </template>
          <template x-if="editing">
            <div>
              <input type="text" name="address"
                value="{{ old('address', $hospital->address) }}"
                class="form-input form-input-light"
                placeholder="Street, Barangay, City/Municipality"/>
              @error('address')<div class="form-error">{{ $message }}</div>@enderror
            </div>
          </template>
        </div>

        {{-- Save button --}}
        <div x-show="editing" x-cloak style="text-align:right;">
          <button type="submit" class="btn btn-dash-primary">
            <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
            Save Changes
          </button>
        </div>

      </div>
    </form>
  </div>
</div>

    {{-- Requests tab --}}
    <div x-show="tab==='requests'" x-cloak>
      <div class="dash-card">
        <div class="dash-card-header">
          <h3 class="dash-card-title">Blood Request History</h3>
        </div>
        @if($hospital->requests->isEmpty())
          <div class="dash-card-body">
            <x-empty-state title="No requests yet" message="This hospital has not submitted any blood requests."/>
          </div>
        @else
          <div class="table-container" style="border:none; border-radius:0;">
            <table class="data-table">
              <thead>
                <tr>
                  <th>Request #</th>
                  <th>Blood Type</th>
                  <th>Qty</th>
                  <th>Urgency</th>
                  <th>Fulfillment</th>
                  <th>Date</th>
                  <th>Status</th>
                </tr>
              </thead>
              <tbody>
                @foreach($hospital->requests->sortByDesc('request_date') as $req)
                  @php
                    $urgencyColors = ['routine'=>'#27AE60','urgent'=>'#F39C12','emergency'=>'#E74C3C'];
                    $urgencyColor  = $urgencyColors[$req->urgency ?? 'routine'] ?? '#888';
                  @endphp
                  <tr>
                    <td>
                      <span style="font-family:var(--font-mono); font-size:0.8rem; color:#888; background:#f5f5f5; padding:0.2rem 0.5rem; border-radius:6px;">
                        #{{ $req->request_id }}
                      </span>
                    </td>
                    <td><x-blood-type-badge :type="$req->bloodType->type_name"/></td>
                    <td style="font-weight:700;">{{ $req->quantity }}</td>
                    <td>
                      <span style="font-size:0.75rem; font-weight:600; color:{{ $urgencyColor }}; background:{{ $urgencyColor }}15; border:1px solid {{ $urgencyColor }}33; padding:0.2rem 0.55rem; border-radius:6px; text-transform:capitalize;">
                        {{ ucfirst($req->urgency ?? 'routine') }}
                      </span>
                    </td>
                    <td style="font-size:0.82rem; color:#888; text-transform:capitalize;">
                      {{ $req->fulfillment_type ?? 'pickup' }}
                    </td>
                    <td style="font-size:0.82rem; color:#888; white-space:nowrap;">
                      {{ $req->request_date->format('M d, Y') }}
                    </td>
                    <td><x-status-badge :status="$req->status->value" size="sm"/></td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>

  </div>

  <style>
  [x-cloak] { display: none !important; }
  @media (max-width: 900px) {
    div[style*="grid-template-columns:1fr 1fr"] { grid-template-columns: 1fr !important; }
  }
  @media (max-width: 768px) {
    .tab-nav { flex-wrap: wrap; }
  }
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
</style>

</x-app-layout>
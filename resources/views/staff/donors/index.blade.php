<x-app-layout title="Donors & Hospitals">

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
        <span>Donors & Hospitals</span>
      </div>
      <h1 class="page-title">Donors & Hospitals</h1>
      <p class="page-subtitle">Manage registered donors and hospital accounts.</p>
    </div>

    {{-- Dropdown button --}}
    <div style="position:relative;" x-data="{ open: false }">
      <button @click="open=!open" class="btn btn-dash-primary">
        <svg width="15" height="15" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 4v16m8-8H4"/></svg>
        Add User
        <svg width="12" height="12" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
          :style="open ? 'transform:rotate(180deg);transition:0.2s;' : 'transition:0.2s;'">
          <path stroke-linecap="round" d="M19 9l-7 7-7-7"/>
        </svg>
      </button>
      <div x-show="open" x-cloak @click.outside="open=false"
        style="position:absolute; right:0; top:calc(100% + 0.5rem); background:#fff; border:1px solid var(--border-light); border-radius:12px; box-shadow:0 8px 32px rgba(0,0,0,0.12); min-width:185px; z-index:50; overflow:hidden; padding:0.4rem;">
        <a href="{{ route('staff.donors.create') }}"
          style="display:flex; align-items:center; gap:0.7rem; padding:0.65rem 0.9rem; text-decoration:none; color:#1a1a2e; font-size:0.855rem; font-weight:500; border-radius:8px; transition:background 0.15s;"
          onmouseover="this.style.background='rgba(192,57,43,0.06)'" onmouseout="this.style.background='transparent'">
          <svg width="14" height="14" fill="none" stroke="#C0392B" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
          Add Donor
        </a>
        <a href="{{ route('staff.hospitals.create') }}"
          style="display:flex; align-items:center; gap:0.7rem; padding:0.65rem 0.9rem; text-decoration:none; color:#1a1a2e; font-size:0.855rem; font-weight:500; border-radius:8px; transition:background 0.15s;"
          onmouseover="this.style.background='rgba(83,52,131,0.06)'" onmouseout="this.style.background='transparent'">
          <svg width="14" height="14" fill="none" stroke="#9B59B6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          Add Hospital
        </a>
      </div>
    </div>
  </div>

  <div style="display:grid; grid-template-columns:repeat(4,1fr); gap:1.25rem; margin-bottom:1.75rem;">
    <x-stat-card title="Pending"   :value="$stats['pending']"   color="yellow" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Active"    :value="$stats['active']"    color="green"  icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Inactive"  :value="$stats['inactive']"  color="gray"   icon="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
    <x-stat-card title="Rejected"  :value="$stats['rejected']"  color="navy"   icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
</div>

  {{-- Filter bar --}}
  <div class="dash-card" style="margin-bottom:1.5rem; padding:1rem 1.25rem;"
    x-data="{
      search: '{{ request('search','') }}',
      role: '{{ request('role','') }}',
      status: '{{ request('status','') }}',
      loading: false,
      updateResults() {
        this.loading = true;
        const url = new URL(window.location.href);
        url.searchParams.set('search', this.search);
        url.searchParams.set('role', this.role);
        url.searchParams.set('status', this.status);
        url.searchParams.delete('page');
        fetch(url.toString(), { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
          .then(r => r.text())
          .then(html => {
            const doc = new DOMParser().parseFromString(html, 'text/html');
            document.getElementById('users-table-wrapper').innerHTML =
              doc.getElementById('users-table-wrapper').innerHTML;
            window.history.replaceState({}, '', url.toString());
            this.loading = false;
          });
      }
    }">
    <div style="display:flex; align-items:center; gap:0.85rem; flex-wrap:wrap;">

      {{-- Search --}}
      <div style="position:relative; flex:2; min-width:240px;">
        <span style="position:absolute; left:0.9rem; top:50%; transform:translateY(-50%); display:flex; align-items:center; pointer-events:none;">
          <svg x-show="!loading" width="15" height="15" fill="none" stroke="#bbb" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
          </svg>
          <svg x-show="loading" width="15" height="15" fill="none" stroke="#C0392B" stroke-width="2" viewBox="0 0 24 24" style="animation:donors-spin 0.7s linear infinite;">
            <path stroke-linecap="round" d="M4 12a8 8 0 018-8"/>
          </svg>
        </span>
        <input type="text" x-model="search"
          class="form-input form-input-light" style="padding-left:2.6rem; width:100%;"
          placeholder="Search by name or email…"
          @input.debounce.350ms="updateResults()"/>
      </div>

      {{-- Role --}}
      <select x-model="role" @change="updateResults()"
        class="form-input form-input-light" style="min-width:140px; max-width:165px;">
        <option value="">All Roles</option>
        <option value="donor">Donors</option>
        <option value="hospital">Hospitals</option>
      </select>

      {{-- Status --}}
      <select x-model="status" @change="updateResults()"
        class="form-input form-input-light" style="min-width:140px; max-width:165px;">
        <option value="">All Status</option>
        <option value="pending">Pending</option>
        <option value="active">Active</option>
        <option value="inactive">Inactive</option>
        <option value="rejected">Rejected</option>
      </select>

    </div>
  </div>

  <style>
    @keyframes donors-spin { to { transform: rotate(360deg); } }
  </style>

  {{-- Table --}}
  <div id="users-table-wrapper">
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          {{ request('role') === 'donor' ? 'Donors' : (request('role') === 'hospital' ? 'Hospitals' : 'All Donors & Hospitals') }}
          <span style="font-size:0.78rem; font-weight:400; color:#999; margin-left:0.5rem;">({{ $users->total() }} total)</span>
        </h3>
      </div>

      @if($users->isEmpty())
        <div class="dash-card-body">
          <x-empty-state title="No records found" message="Try adjusting your search or filters."/>
        </div>
      @else
        <div class="table-container" style="border:none; border-radius:0;">
          <table class="data-table">
            <thead>
              <tr>
                <th>User</th>
                <th>Role</th>
                <th>Status</th>
                <th>Registered</th>
                <th style="text-align:right;">Action</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr>
                  <td>
                    <div style="display:flex; align-items:center; gap:0.85rem;">
                      <div>
                        <div style="font-weight:600; font-size:0.875rem; color:#1a1a2e;">{{ $user->name }}</div>
                        <div style="font-size:0.75rem; color:#999;">{{ $user->email }}</div>
                      </div>
                    </div>
                  </td>
                  <td>
                    <span class="badge badge-role-{{ $user->role->value }}">
                      {{ ucfirst($user->role->value) }}
                    </span>
                  </td>
                  <td><x-status-badge :status="$user->status->value" size="sm"/></td>
                  <td style="font-size:0.8rem; color:#999; white-space:nowrap;">
                    {{ $user->created_at->format('M d, Y') }}
                  </td>
                  <td>
  <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.4rem;" x-data>
    @if($user->role->value === 'donor' && $user->donor)
      <a href="{{ route('staff.donors.show', $user->donor) }}"
        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(41,128,185,0.3); background:rgba(41,128,185,0.08); color:#2980B9; text-decoration:none; transition:all 0.2s;"
        onmouseover="this.style.background='rgba(41,128,185,0.18)'"
        onmouseout="this.style.background='rgba(41,128,185,0.08)'"
        title="View">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
      </a>
    @elseif($user->role->value === 'hospital' && $user->hospital)
      <a href="{{ route('staff.hospitals.show', $user->hospital) }}"
        style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(41,128,185,0.3); background:rgba(41,128,185,0.08); color:#2980B9; text-decoration:none; transition:all 0.2s;"
        onmouseover="this.style.background='rgba(41,128,185,0.18)'"
        onmouseout="this.style.background='rgba(41,128,185,0.08)'"
        title="View">
        <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
          <path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
        </svg>
      </a>
    @endif
  </div>
</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="dash-card-footer">
          <x-pagination :paginator="$users"/>
        </div>
      @endif
    </div>
  </div>

  <style>
    @media (max-width: 768px) {
      div[style*="grid-template-columns:repeat(2,1fr)"] { grid-template-columns: 1fr !important; }
    }
  </style>

</x-app-layout>
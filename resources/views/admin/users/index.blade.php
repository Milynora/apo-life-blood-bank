<x-app-layout title="User Management">

  <div class="page-header">
    <div>
      <div class="breadcrumb">
        <a href="{{ route('admin.dashboard') }}">Dashboard</a>
        <span class="breadcrumb-sep">›</span>
        <span>Users</span>
      </div>
      <h1 class="page-title">User Management</h1>
      <p class="page-subtitle">Manage donor, hospital, and staff accounts.</p>
    </div>
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
        <a href="{{ route('admin.users.create-donor') }}"
          style="display:flex; align-items:center; gap:0.7rem; padding:0.65rem 0.9rem; text-decoration:none; color:#1a1a2e; font-size:0.855rem; font-weight:500; border-radius:8px; transition:background 0.15s;"
          onmouseover="this.style.background='rgba(192,57,43,0.06)'" onmouseout="this.style.background='transparent'">
          <svg width="14" height="14" fill="none" stroke="#C0392B" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
          Add Donor
        </a>
        <a href="{{ route('admin.users.create-hospital') }}"
          style="display:flex; align-items:center; gap:0.7rem; padding:0.65rem 0.9rem; text-decoration:none; color:#1a1a2e; font-size:0.855rem; font-weight:500; border-radius:8px; transition:background 0.15s;"
          onmouseover="this.style.background='rgba(83,52,131,0.06)'" onmouseout="this.style.background='transparent'">
          <svg width="14" height="14" fill="none" stroke="#9B59B6" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
          Add Hospital
        </a>
        <div style="height:1px; background:var(--border-light); margin:0.3rem 0.5rem;"></div>
        <a href="{{ route('admin.users.create-staff') }}"
          style="display:flex; align-items:center; gap:0.7rem; padding:0.65rem 0.9rem; text-decoration:none; color:#1a1a2e; font-size:0.855rem; font-weight:500; border-radius:8px; transition:background 0.15s;"
          onmouseover="this.style.background='rgba(41,128,185,0.06)'" onmouseout="this.style.background='transparent'">
          <svg width="14" height="14" fill="none" stroke="#2980B9" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
          Add Staff
        </a>
      </div>
    </div>
  </div>

  {{-- Stat cards --}}
  <div style="display:grid; grid-template-columns:repeat(5,1fr); gap:1.25rem; margin-bottom:1.75rem;">
    <x-stat-card title="Pending Approval" :value="$stats['pending']"  color="yellow" icon="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Active"           :value="$stats['active']"   color="green"  icon="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Inactive"         :value="$stats['inactive']" color="red"    icon="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
    <x-stat-card title="Rejected"         :value="$stats['rejected']" color="gray"   icon="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
    <x-stat-card title="Deleted"          :value="$stats['deleted']"  color="navy"   icon="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
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
          <svg x-show="loading" width="15" height="15" fill="none" stroke="#C0392B" stroke-width="2" viewBox="0 0 24 24" style="animation:users-spin 0.7s linear infinite;">
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
        @foreach(['donor'=>'Donor','hospital'=>'Hospital','staff'=>'Staff','admin'=>'Admin'] as $v=>$l)
          <option value="{{ $v }}" {{ request('role')===$v ? 'selected' : '' }}>{{ $l }}</option>
        @endforeach
      </select>

      {{-- Status --}}
      <select x-model="status" @change="updateResults()"
        class="form-input form-input-light" style="min-width:140px; max-width:165px;">
        <option value="">All Status</option>
        @foreach(['pending'=>'Pending','active'=>'Active','inactive'=>'Inactive','rejected'=>'Rejected','deleted'=>'Deleted'] as $v=>$l)
          <option value="{{ $v }}" {{ request('status')===$v ? 'selected' : '' }}>{{ $l }}</option>
        @endforeach
      </select>

    </div>
  </div>

  <style>
    @keyframes users-spin { to { transform: rotate(360deg); } }
  </style>

  {{-- Users table --}}
  <div id="users-table-wrapper">
    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          {{ request('status') === 'deleted' ? 'Deleted Users' : 'All Users' }}
          <span style="font-size:0.78rem; font-weight:400; color:#999; margin-left:0.5rem;">({{ $users->total() }} total)</span>
        </h3>
      </div>

      @if($users->isEmpty())
        <div class="dash-card-body">
          <x-empty-state title="No users found" message="Try adjusting your search or filters."/>
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
                <th style="text-align:right;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
                <tr style="{{ $user->trashed() ? 'opacity:0.6;' : '' }}">
                  <td>
                    <div style="display:flex; align-items:center; gap:0.85rem;">
                      <div>
                        <div style="font-weight:600; font-size:0.875rem; color:#1a1a2e;">
                          {{ $user->name }}
                          @if($user->trashed())
                            <span style="font-size:0.68rem; color:#E74C3C; font-weight:600; background:rgba(192,57,43,0.1); border:1px solid rgba(192,57,43,0.2); border-radius:4px; padding:0.1rem 0.4rem; margin-left:0.3rem;">Deleted</span>
                          @endif
                        </div>
                        <div style="font-size:0.75rem; color:#999;">{{ $user->email }}</div>
                      </div>
                    </div>
                  </td>
                  <td><span class="badge badge-role-{{ $user->role->value }}">{{ ucfirst($user->role->value) }}</span></td>
                  <td>
                    @if($user->trashed())
                      <span class="badge badge-rejected">Deleted</span>
                    @else
                      <x-status-badge :status="$user->status->value" size="sm"/>
                    @endif
                  </td>
                  <td style="font-size:0.8rem; color:#999; white-space:nowrap;">{{ $user->created_at->format('M d, Y') }}</td>
                  <td>
                    <div style="display:flex; align-items:center; justify-content:flex-end; gap:0.4rem;" x-data>

                      @if($user->trashed())
                        {{-- Deleted user: show restore button only --}}
                        <button type="button"
                          @click="$dispatch('open-modal', 'restore-{{ $user->id }}')"
                          style="display:inline-flex; align-items:center; gap:0.35rem; padding:0.3rem 0.75rem; border-radius:8px; border:1px solid rgba(39,174,96,0.3); background:rgba(39,174,96,0.08); color:#27AE60; cursor:pointer; font-size:0.75rem; font-weight:600; font-family:var(--font-body); transition:all 0.2s;"
                          onmouseover="this.style.background='rgba(39,174,96,0.18)'"
                          onmouseout="this.style.background='rgba(39,174,96,0.08)'"
                          title="Restore">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                          </svg>
                          Restore
                        </button>
                        <x-confirm-dialog
                          id="restore-{{ $user->id }}"
                          title="Restore User?"
                          message="Restore {{ $user->name }}'s account? They will be able to log in again."
                          confirm-label="Restore"
                          confirm-class="btn-success"
                          action="{{ route('admin.users.restore', $user->id) }}"
                          method="PATCH"/>

                      @else
                        {{-- Normal user: show all action buttons --}}
                        <a href="{{ route('admin.users.show', $user) }}"
                          style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(41,128,185,0.3); background:rgba(41,128,185,0.08); color:#2980B9; text-decoration:none; transition:all 0.2s;"
                          onmouseover="this.style.background='rgba(41,128,185,0.18)'"
                          onmouseout="this.style.background='rgba(41,128,185,0.08)'"
                          title="View">
                          <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                          </svg>
                        </a>

                        @if($user->isPending())
                          <button type="button"
                            @click="$dispatch('open-modal', 'approve-{{ $user->id }}')"
                            style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(39,174,96,0.3); background:rgba(39,174,96,0.08); color:#27AE60; cursor:pointer; transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(39,174,96,0.18)'"
                            onmouseout="this.style.background='rgba(39,174,96,0.08)'"
                            title="Approve">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 13l4 4L19 7"/></svg>
                          </button>
                          <x-confirm-dialog
                            id="approve-{{ $user->id }}"
                            title="Approve Account?"
                            message="Activate {{ $user->name }}'s account. They will be able to log in immediately."
                            confirm-label="Approve"
                            confirm-class="btn-success"
                            action="{{ route('admin.users.approve', $user) }}"
                            method="PATCH"/>
                        @endif

                        @if($user->isActive() && !$user->isAdmin())
                          <button type="button"
                            @click="$dispatch('open-modal', 'suspend-{{ $user->id }}')"
                            style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(243,156,18,0.3); background:rgba(243,156,18,0.08); color:#E67E22; cursor:pointer; transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(243,156,18,0.18)'"
                            onmouseout="this.style.background='rgba(243,156,18,0.08)'"
                            title="Suspend">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/></svg>
                          </button>
                          <x-confirm-dialog id="suspend-{{ $user->id }}"
                            title="Suspend Account?"
                            message="This will prevent {{ $user->name }} from logging in."
                            confirm-label="Suspend" confirm-class="btn-warning"
                            action="{{ route('admin.users.suspend', $user) }}" method="PATCH"/>
                        @endif

                        @if($user->isInactive() && !$user->isAdmin())
                          <button type="button"
                            @click="$dispatch('open-modal', 'reactivate-{{ $user->id }}')"
                            style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(39,174,96,0.3); background:rgba(39,174,96,0.08); color:#27AE60; cursor:pointer; transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(39,174,96,0.18)'"
                            onmouseout="this.style.background='rgba(39,174,96,0.08)'"
                            title="Reactivate">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                              <path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                          </button>
                          <x-confirm-dialog id="reactivate-{{ $user->id }}"
                            title="Reactivate Account?"
                            message="Reactivate {{ $user->name }}'s account? They will be able to log in immediately."
                            confirm-label="Reactivate" confirm-class="btn-success"
                            action="{{ route('admin.users.reactivate', $user) }}" method="PATCH"/>
                        @endif

                        @if(!($user->isAdmin() && auth()->id() === $user->id))
                          <button type="button"
                            @click="$dispatch('open-modal', 'delete-{{ $user->id }}')"
                            style="display:inline-flex; align-items:center; justify-content:center; width:32px; height:32px; border-radius:8px; border:1px solid rgba(192,57,43,0.25); background:rgba(192,57,43,0.06); color:#E74C3C; cursor:pointer; transition:all 0.2s;"
                            onmouseover="this.style.background='rgba(192,57,43,0.15)'; this.style.borderColor='rgba(192,57,43,0.4)'"
                            onmouseout="this.style.background='rgba(192,57,43,0.06)'; this.style.borderColor='rgba(192,57,43,0.25)'"
                            title="Delete">
                            <svg width="13" height="13" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                          </button>
                          <x-confirm-dialog id="delete-{{ $user->id }}"
                            title="Delete User?"
                            message="Permanently delete {{ $user->name }} and all their data. This cannot be undone."
                            confirm-label="Delete" confirm-class="btn-danger"
                            action="{{ route('admin.users.destroy', $user) }}" method="DELETE"/>
                        @endif

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

</x-app-layout>
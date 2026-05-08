@php
  $user     = auth()->user();
  $role     = $user->role->value;
  $initials = strtoupper(substr($user->name, 0, 1));
  if (str_contains($user->name, ' ')) {
    $parts    = explode(' ', $user->name);
    $initials = strtoupper(substr($parts[0],0,1) . substr(end($parts),0,1));
  }
@endphp

<aside class="app-sidebar" id="app-sidebar">

  {{-- Profile block at top --}}
  <div class="sidebar-profile">
    <div class="sidebar-profile-avatar">
  @if($user->isDonor() && $user->donor?->avatar)
    <img src="{{ asset($user->donor->avatar) }}"
      alt="{{ $user->name }}"
      style="width:100%; height:100%; object-fit:cover; border-radius:50%;"/>
  @else
    {{ $initials }}
  @endif
</div>
    <div class="sidebar-profile-name">{{ $user->name }}</div>
    <div class="sidebar-profile-email">{{ $user->email }}</div>
    <span class="badge badge-role-{{ $role }}" style="margin-top:0.5rem; font-size:0.68rem;">
      {{ ucfirst($role) }}
    </span>
  </div>

  {{-- Divider after profile --}}
<div style="height:1px; background:linear-gradient(90deg, transparent, rgba(192,57,43,0.15), transparent); margin:0 0.75rem;"></div>

  {{-- Nav links --}}
  <nav class="sidebar-nav">

    @if($role === 'admin')
      <div class="sidebar-section-label">Main</div>
      <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>
      <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
        Users
      </a>

      <div class="sidebar-section-label">Operations</div>
      <a href="{{ route('admin.appointments.index') }}" class="sidebar-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Appointments
      </a>
      <a href="{{ route('admin.screenings.index') }}" class="sidebar-link {{ request()->routeIs('admin.screenings.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Screenings
      </a>
      <a href="{{ route('admin.donations.index') }}" class="sidebar-link {{ request()->routeIs('admin.donations.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        Donations
      </a>
      <a href="{{ route('admin.blood-requests.index') }}" class="sidebar-link {{ request()->routeIs('admin.blood-requests.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Blood Requests
      </a>

      <div class="sidebar-section-label">Data</div>
      <a href="{{ route('admin.inventory.index') }}" class="sidebar-link {{ request()->routeIs('admin.inventory.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
        Inventory
      </a>
      <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Reports
      </a>
    @endif

    @if($role === 'staff')
      <div class="sidebar-section-label">Main</div>
      <a href="{{ route('staff.dashboard') }}" class="sidebar-link {{ request()->routeIs('staff.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>
      <a href="{{ route('staff.donors.index') }}" class="sidebar-link {{ request()->routeIs('staff.donors.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Users
      </a>

      <div class="sidebar-section-label">Operations</div>
      <a href="{{ route('staff.appointments.index') }}" class="sidebar-link {{ request()->routeIs('staff.appointments.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Appointments
      </a>
      <a href="{{ route('staff.screenings.index') }}" class="sidebar-link {{ request()->routeIs('staff.screenings.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        Screenings
      </a>
      <a href="{{ route('staff.donations.index') }}" class="sidebar-link {{ request()->routeIs('staff.donations.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        Donations
      </a>
      <a href="{{ route('staff.blood-requests.index') }}" class="sidebar-link {{ request()->routeIs('staff.blood-requests.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Blood Requests
      </a>

      <div class="sidebar-section-label">Data</div>
      <a href="{{ route('staff.inventory.index') }}" class="sidebar-link {{ request()->routeIs('staff.inventory.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
        Inventory
      </a>
      <a href="{{ route('staff.reports.index') }}" class="sidebar-link {{ request()->routeIs('staff.reports.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
        Reports
      </a>
    @endif

    @if($role === 'donor')
      <div class="sidebar-section-label">Main</div>
      <a href="{{ route('donor.dashboard') }}" class="sidebar-link {{ request()->routeIs('donor.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>
      <a href="{{ route('donor.appointments.index') }}" class="sidebar-link {{ request()->routeIs('donor.appointments.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        Appointments
      </a>
      <a href="{{ route('donor.donations.index') }}" class="sidebar-link {{ request()->routeIs('donor.donations.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        Donation History
      </a>
      <a href="{{ route('donor.profile.edit') }}" class="sidebar-link {{ request()->routeIs('donor.profile.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        Profile
      </a>
    @endif

    @if($role === 'hospital')
      <div class="sidebar-section-label">Main</div>
      <a href="{{ route('hospital.dashboard') }}" class="sidebar-link {{ request()->routeIs('hospital.dashboard') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        Dashboard
      </a>
      <a href="{{ route('hospital.requests.index') }}" class="sidebar-link {{ request()->routeIs('hospital.requests.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        Blood Requests
      </a>
      <a href="{{ route('hospital.profile.edit') }}" class="sidebar-link {{ request()->routeIs('hospital.profile.*') ? 'active' : '' }}">
        <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
        Hospital Profile
      </a>
    @endif

  </nav>

  <div style="flex:1;"></div>

</aside>

<style>
  /* ── Profile block ── */
  .sidebar-profile {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 1.75rem 1rem 1.25rem;
    text-align: center;
    background: linear-gradient(160deg, #fff5f5 0%, #fff 60%);
    border-radius: 18px 18px 0 0;
    position: relative;
    overflow: hidden;
  }
  .sidebar-profile::before {
    content: '';
    position: absolute;
    top: -30px; right: -30px;
    width: 100px; height: 100px;
    background: radial-gradient(circle, rgba(192,57,43,0.08) 0%, transparent 70%);
    border-radius: 50%;
  }
  .sidebar-profile::after {
    content: '';
    position: absolute;
    bottom: -20px; left: -20px;
    width: 80px; height: 80px;
    background: radial-gradient(circle, rgba(233,69,96,0.06) 0%, transparent 70%);
    border-radius: 50%;
  }

  .sidebar-profile-avatar {
    width: 72px;
    height: 72px;
    border-radius: 50%;
    background: linear-gradient(135deg, #C0392B 0%, #e94560 100%);
    color: #fff;
    font-size: 1.5rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 0.85rem;
    box-shadow: 0 6px 20px rgba(192,57,43,0.35);
    border: 3px solid #fff;
    outline: 2px solid rgba(192,57,43,0.15);
    position: relative;
    z-index: 1;
    flex-shrink: 0;
  }

  .sidebar-profile-name {
    font-size: 0.9rem;
    font-weight: 700;
    color: #1a1a2e;
    line-height: 1.3;
    word-break: break-word;
    position: relative;
    z-index: 1;
  }
  .sidebar-profile-email {
    font-size: 0.7rem;
    color: #aaa;
    margin-top: 0.2rem;
    word-break: break-all;
    position: relative;
    z-index: 1;
  }

  /* ── Nav ── */
  .sidebar-nav {
    display: flex;
    flex-direction: column;
    padding: 0.75rem 0.75rem 0.5rem;
    gap: 0.1rem;
  }
  .sidebar-section-label {
    font-size: 0.63rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.09em;
    color: #ccc;
    padding: 0.7rem 0.6rem 0.2rem;
  }
  .sidebar-link {
    display: flex;
    align-items: center;
    gap: 0.7rem;
    padding: 0.6rem 0.8rem;
    border-radius: 10px;
    text-decoration: none;
    font-size: 0.858rem;
    font-weight: 500;
    color: #666;
    transition: all 0.18s ease;
  }
  .sidebar-link svg {
    width: 16px;
    height: 16px;
    flex-shrink: 0;
    opacity: 0.6;
    transition: opacity 0.18s;
  }
  .sidebar-link:hover {
    background: rgba(192,57,43,0.07);
    color: var(--primary);
  }
  .sidebar-link:hover svg { opacity: 1; }
  .sidebar-link.active {
    background: linear-gradient(135deg, rgba(192,57,43,0.12), rgba(233,69,96,0.08));
    color: var(--primary);
    font-weight: 600;
  }
  .sidebar-link.active svg { opacity: 1; }
</style>
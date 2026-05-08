@php
  $user     = auth()->user();
  $role     = $user->role->value;
  $initials = strtoupper(substr($user->name, 0, 1));
  if (str_contains($user->name, ' ')) {
    $parts    = explode(' ', $user->name);
    $initials = strtoupper(substr($parts[0],0,1) . substr(end($parts),0,1));
  }

  $unreadNotificationCount = $user->unreadNotifications()->count();
@endphp

<header class="app-topbar" x-data="{ notifOpen: false, menuOpen: false, showLogout: false }">

  {{-- Mobile hamburger --}}
  <button id="sidebar-toggle" class="topbar-hamburger" aria-label="Toggle sidebar">
    <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
      <path stroke-linecap="round" d="M3 6h18M3 12h18M3 18h18"/>
    </svg>
  </button>

  {{-- Apo Life logo --}}
  <a href="{{ route($role . '.dashboard') }}" class="topbar-logo">
    <img src="{{ asset('images/logo.png') }}" alt="Apo Life"/>
    <span>Apo Life</span>
  </a>

  {{-- Right side --}}
  <div class="topbar-right">

    {{-- Notification Bell --}}
    <div style="position:relative;">
      <button
        class="topbar-icon-btn"
        @click="notifOpen = !notifOpen; menuOpen = false"
        @click.away="notifOpen = false"
        aria-label="Notifications">
        <svg width="17" height="17" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
          <path stroke-linecap="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
        </svg>
        @if($unreadNotificationCount > 0)
          <span class="topbar-notif-count">
            {{ $unreadNotificationCount > 9 ? '9+' : $unreadNotificationCount }}
          </span>
        @endif
      </button>

      {{-- Notification Dropdown --}}
      <div class="topbar-dropdown notif-dropdown" x-show="notifOpen" x-transition style="display:none;">
        <div class="notif-dropdown-header">
          <h4>Notifications</h4>
          @if($unreadNotificationCount > 0)
            <form method="POST" action="{{ route('notifications.readAll') }}">
              @csrf @method('PATCH')
              <button type="submit" class="notif-mark-all-btn">Mark all read</button>
            </form>
          @endif
        </div>

        @php
          $latestNotifs = $user->notifications()->latest()->take(5)->get();
          $typeColors = [
            'account_approved'       => '#27AE60',
            'account_rejected'       => '#E74C3C',
            'appointment'            => '#2980B9',
            'screening'              => '#27AE60',
            'donation'               => '#C0392B',
            'blood_request'          => '#533483',
            'blood_unit_expiring'    => '#F39C12',
            'registration'           => '#1ABC9C',
          ];
        @endphp

        @forelse($latestNotifs as $notif)
          @php $color = $typeColors[$notif->data['type'] ?? ''] ?? '#888'; @endphp
          <a href="{{ route('notifications.index') }}"
             class="notif-item {{ is_null($notif->read_at) ? 'unread' : '' }}">
            <div class="notif-icon" style="background:{{ $color }}22;">
              <svg width="14" height="14" fill="none" stroke="{{ $color }}" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
              </svg>
            </div>
            <div class="notif-text">
              <div class="msg">{{ Str::limit($notif->data['message'] ?? '', 60) }}</div>
              <div class="time">{{ $notif->created_at->diffForHumans() }}</div>
            </div>
          </a>
        @empty
          <div class="notif-empty">No notifications yet</div>
        @endforelse

        <div class="notif-dropdown-footer">
          <a href="{{ route('notifications.index') }}">View all notifications</a>
        </div>
      </div>
    </div>

    {{-- Divider --}}
    <div class="topbar-divider"></div>

    {{-- Avatar dropdown --}}
    <div style="position:relative;">
      <button
        class="topbar-avatar-btn"
        @click="menuOpen = !menuOpen; notifOpen = false"
        @click.away="menuOpen = false"
        aria-label="Account menu"
        style="{{ ($user->isDonor() && $user->donor?->avatar) ? 'padding:0; overflow:hidden;' : '' }}">
        @if($user->isDonor() && $user->donor?->avatar)
          <img src="{{ asset($user->donor->avatar) }}"
            alt="{{ $user->name }}"
            style="width:100%; height:100%; object-fit:cover; border-radius:50%;"/>
        @else
          {{ $initials }}
        @endif
      </button>

      {{-- Dropdown --}}
      <div class="topbar-dropdown menu-dropdown" x-show="menuOpen" x-transition style="display:none;">

        <div class="menu-dropdown-header">
          <div class="menu-dropdown-avatar"
            style="{{ ($user->isDonor() && $user->donor?->avatar) ? 'padding:0; overflow:hidden;' : '' }}">
            @if($user->isDonor() && $user->donor?->avatar)
              <img src="{{ asset($user->donor->avatar) }}"
                alt="{{ $user->name }}"
                style="width:100%; height:100%; object-fit:cover; border-radius:50%;"/>
            @else
              {{ $initials }}
            @endif
          </div>
          <div class="menu-dropdown-info">
            <div class="name">{{ $user->name }}</div>
            <div class="email">{{ $user->email }}</div>
          </div>
        </div>
        
@if($role === 'donor')
          <a href="{{ route('donor.profile.edit') }}" class="dropdown-item">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" width="15" height="15">
              <path stroke-linecap="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            My Profile
          </a>
        @elseif($role === 'hospital')
          <a href="{{ route('hospital.profile.edit') }}" class="dropdown-item">
            <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" width="15" height="15">
              <path stroke-linecap="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
            </svg>
            Hospital Profile
          </a>
        @endif

        <a href="{{ route('notifications.index') }}" class="dropdown-item">
          <svg fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" width="15" height="15">
            <path stroke-linecap="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
          </svg>
          Notifications
          @if($unreadNotificationCount > 0)
            <span class="badge badge-rejected" style="margin-left:auto; font-size:0.63rem; padding:0.1rem 0.45rem;">
              {{ $unreadNotificationCount }}
            </span>
          @endif
        </a>

        <div class="dropdown-divider"></div>

        {{-- Sign Out button --}}
        <button
  @click="menuOpen = false; $dispatch('open-logout')"
  style="display:flex; align-items:center; gap:0.5rem; width:100%; padding:0.6rem 1rem; border-radius:8px; border:none; background:transparent; color:#E74C3C; font-size:0.82rem; font-weight:600; cursor:pointer; font-family:var(--font-body); transition:all 0.2s; text-align:left;"
  onmouseover="this.style.background='rgba(192,57,43,0.08)'"
  onmouseout="this.style.background='transparent'">
  <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
    <path stroke-linecap="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
  </svg>
  Sign Out
</button>

      </div>
    </div>

  </div>

  {{-- Logout confirm dialog — teleported to body, lives on header x-data scope --}}
  <template x-teleport="#modal-portal">
    <div
      x-show="showLogout"
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0"
      x-transition:enter-end="opacity-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100"
      x-transition:leave-end="opacity-0"
      @keydown.escape.window="showLogout = false"
      style="position:absolute; inset:0; z-index:99999; width:100vw !important; height:100vh !important; display:flex; align-items:center; justify-content:center; background:rgba(0,0,0,0.45); backdrop-filter:blur(2px);"
      x-cloak>
      <div
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.outside="showLogout = false"
        style="background:#fff; border-radius:16px; padding:2rem; max-width:380px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
        <div style="text-align:center; margin-bottom:1.5rem;">
          <div style="width:52px; height:52px; border-radius:50%; background:rgba(192,57,43,0.1); display:flex; align-items:center; justify-content:center; margin:0 auto 1rem;">
            <svg width="24" height="24" fill="none" stroke="#E74C3C" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
            </svg>
          </div>
          <h3 style="font-size:1.1rem; font-weight:700; color:#1a1a2e; margin-bottom:0.5rem;">Sign Out</h3>
          <p style="font-size:0.875rem; color:#888; line-height:1.6;">Are you sure you want to sign out of Apo Life?</p>
        </div>
        <div style="display:flex; gap:0.75rem;">
          <button @click="showLogout = false"
            style="flex:1; padding:0.7rem; border-radius:10px; border:1.5px solid #e0e0e0; background:#fff; color:#555; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body);">
            Cancel
          </button>
          <form method="POST" action="{{ route('logout') }}" style="flex:1;">
            @csrf
            <button type="submit"
              style="width:100%; padding:0.7rem; border-radius:10px; border:none; background:linear-gradient(135deg,#C0392B,#e94560); color:#fff; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body);">
              Sign Out
            </button>
          </form>
        </div>
      </div>
    </div>
  </template>

</header>

<style>
  .app-topbar {
    height: 56px;
    background: #fff;
    border-radius: 14px;
    box-shadow: 0 2px 12px rgba(192,57,43,0.07), 0 1px 3px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    padding: 0 1.1rem;
    position: sticky;
    top: 0.85rem;
    z-index: 50;
    gap: 0.65rem;
    margin-bottom: 1.25rem;
    border: 1px solid rgba(192,57,43,0.07);
  }

  .topbar-logo {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    text-decoration: none;
    flex: 1;
    min-width: 0;
  }
  .topbar-logo img { height: 30px; width: auto; }
  .topbar-logo span {
    font-family: var(--font-display);
    font-size: 1rem;
    font-weight: 700;
    color: #1a1a2e;
    white-space: nowrap;
  }

  .topbar-right {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    flex-shrink: 0;
  }

  .topbar-search { display: flex; align-items: center; }
  .topbar-search-inner {
    display: flex;
    align-items: center;
    gap: 0.45rem;
    background: #f8f3f3;
    border: 1.5px solid transparent;
    border-radius: 99px;
    padding: 0.36rem 0.85rem;
    transition: all 0.2s ease;
  }
  .topbar-search-inner.focused {
    background: #fff;
    border-color: rgba(192,57,43,0.4);
    box-shadow: 0 0 0 3px rgba(192,57,43,0.08);
  }
  .topbar-search-inner svg { color: #bbb; flex-shrink: 0; transition: color 0.2s; }
  .topbar-search-inner.focused svg { color: var(--primary); }
  .topbar-search-input {
    border: none;
    background: transparent;
    outline: none;
    font-family: var(--font-body);
    font-size: 0.82rem;
    color: #1a1a2e;
    width: 150px;
    transition: width 0.25s ease;
  }
  .topbar-search-input::placeholder { color: #ccc; }
  .topbar-search-inner.focused .topbar-search-input { width: 190px; }

  .topbar-divider {
    width: 1px;
    height: 20px;
    background: rgba(192,57,43,0.1);
    margin: 0 0.1rem;
  }

  .topbar-icon-btn {
    position: relative;
    background: #f8f3f3;
    border: 1.5px solid transparent;
    border-radius: 10px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    color: #888;
    transition: all 0.18s;
    flex-shrink: 0;
  }
  .topbar-icon-btn:hover {
    background: rgba(192,57,43,0.08);
    border-color: rgba(192,57,43,0.2);
    color: var(--primary);
  }
  .topbar-notif-count {
    position: absolute;
    top: -4px; right: -4px;
    background: var(--primary);
    color: #fff;
    font-size: 0.58rem;
    font-weight: 700;
    min-width: 16px;
    height: 16px;
    border-radius: 99px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 3px;
    border: 2px solid #fff;
    animation: pulse 2s infinite;
  }

  .topbar-avatar-btn {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: linear-gradient(135deg, #C0392B 0%, #e94560 100%);
    color: #fff;
    font-size: 0.72rem;
    font-weight: 700;
    border: 2px solid #fff;
    outline: 2px solid rgba(192,57,43,0.2);
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 3px 10px rgba(192,57,43,0.3);
    transition: all 0.18s;
    flex-shrink: 0;
  }
  .topbar-avatar-btn:hover {
    box-shadow: 0 4px 14px rgba(192,57,43,0.45);
    transform: scale(1.05);
  }

  .topbar-dropdown {
    position: absolute;
    right: 0;
    top: calc(100% + 8px);
    background: #fff;
    border: 1px solid rgba(192,57,43,0.1);
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(192,57,43,0.12), 0 2px 8px rgba(0,0,0,0.08);
    z-index: 200;
    overflow: hidden;
  }

  .notif-dropdown { width: 315px; }
  .notif-dropdown-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.85rem 1rem;
    border-bottom: 1px solid rgba(192,57,43,0.08);
    background: linear-gradient(135deg, #fff5f5, #fff);
  }
  .notif-dropdown-header h4 {
    font-size: 0.875rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 0;
  }
  .notif-mark-all-btn {
    background: none;
    border: none;
    cursor: pointer;
    font-size: 0.73rem;
    color: var(--primary);
    font-weight: 600;
    font-family: var(--font-body);
  }
  .notif-empty {
    padding: 2rem;
    text-align: center;
    color: #ccc;
    font-size: 0.82rem;
  }
  .notif-dropdown-footer {
    padding: 0.65rem 1rem;
    text-align: center;
    border-top: 1px solid rgba(192,57,43,0.07);
    background: #fdf8f8;
  }
  .notif-dropdown-footer a {
    font-size: 0.78rem;
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
  }

  .menu-dropdown { width: 225px; }
  .menu-dropdown-header {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.9rem 1rem;
    background: linear-gradient(135deg, #fff5f5 0%, #fff 100%);
    border-bottom: 1px solid rgba(192,57,43,0.08);
  }
  .menu-dropdown-avatar {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: linear-gradient(135deg, #C0392B, #e94560);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 700;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    box-shadow: 0 3px 8px rgba(192,57,43,0.3);
  }
  .menu-dropdown-info { min-width: 0; }
  .menu-dropdown-info .name {
    font-size: 0.845rem;
    font-weight: 700;
    color: #1a1a2e;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }
  .menu-dropdown-info .email {
    font-size: 0.7rem;
    color: #aaa;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
  }

  .topbar-hamburger {
    display: none;
    background: #f8f3f3;
    border: 1.5px solid transparent;
    border-radius: 10px;
    padding: 0.35rem;
    cursor: pointer;
    color: #888;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    transition: all 0.18s;
  }
  .topbar-hamburger:hover {
    background: rgba(192,57,43,0.08);
    color: var(--primary);
  }

  @media (max-width: 1024px) {
    .topbar-hamburger { display: flex; }
    .topbar-search-input { width: 110px; }
    .topbar-search-inner.focused .topbar-search-input { width: 130px; }
  }
  @media (max-width: 480px) {
    .topbar-search { display: none; }
  }
</style>
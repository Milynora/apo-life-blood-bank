<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="UTF-8"/>
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <meta name="csrf-token" content="{{ csrf_token() }}"/>
  <title>{{ $title ?? 'Dashboard' }} — Apo Life</title>
  <link rel="preconnect" href="https://fonts.googleapis.com"/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=DM+Mono:wght@400;500&display=swap" rel="stylesheet"/>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  <link rel="stylesheet" href="{{ asset('css/app.css') }}"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css"/>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css"/>
  @stack('head')
  <style>
    [x-cloak] { display: none !important; }

    body {
      font-family: 'Plus Jakarta Sans', sans-serif;
      background: #f5f0f0;
      color: #1a1a2e;
      min-height: 100vh;
      display: flex;
      flex-direction: row;
    }

    .hidden { display: none !important; }

    /* ── Page wrapper ── */
    .app-page {
      display: flex;
      flex-direction: row;
      min-height: 100vh;
      width: 100%;
      padding: 0.85rem 0.85rem 0.85rem 0;
      gap: 0.85rem;
      box-sizing: border-box;
    }

    /* ── Sidebar ── */
    .app-sidebar {
      width: 245px;
      flex-shrink: 0;
      min-height: calc(100vh - 1.7rem);
      height: calc(100vh - 1.7rem);
      background: #fff;
      border-radius: 18px;
      box-shadow: 0 4px 24px rgba(192,57,43,0.08), 0 1px 4px rgba(0,0,0,0.06);
      display: flex;
      flex-direction: column;
      position: fixed;
      top: 0.85rem;
      left: 0.85rem;
      z-index: 100;
      overflow-y: auto;
      overflow-x: hidden;
      transition: transform 0.25s ease;
      scrollbar-width: none;
      border: 1px solid rgba(192,57,43,0.08);
    }
    .app-sidebar::-webkit-scrollbar { display: none; }

    /* ── Sidebar overlay (mobile) ── */
    .sidebar-overlay {
      display: none;
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.35);
      z-index: 99;
      border-radius: 0;
    }
    .sidebar-overlay.active { display: block; }

    /* ── Main wrapper ── */
    .app-main-wrapper {
      margin-left: calc(245px + 0.85rem + 0.85rem);
      flex: 1;
      display: flex;
      flex-direction: column;
      min-width: 0;
      min-height: 100vh;
      padding-right: 0;
    }

    /* ── Top bar ── */
    .app-topbar {
      height: 56px;
      background: #fff;
      border-radius: 14px;
      box-shadow: 0 2px 12px rgba(192,57,43,0.07), 0 1px 3px rgba(0,0,0,0.05);
      display: flex;
      align-items: center;
      padding: 0 1.25rem;
      position: sticky;
      top: 0.85rem;
      z-index: 50;
      gap: 0.75rem;
      margin-bottom: 1.25rem;
      border: 1px solid rgba(192,57,43,0.07);
    }

    /* ── Hamburger (mobile) ── */
    .topbar-hamburger {
      display: none;
      background: none;
      border: 1px solid rgba(192,57,43,0.15);
      border-radius: 8px;
      padding: 0.35rem;
      cursor: pointer;
      color: #555;
      align-items: center;
      justify-content: center;
    }

    /* ── Main content area ── */
    .app-content {
      flex: 1;
      padding-bottom: 1rem;
    }

    /* ── Mobile breakpoint ── */
    @media (max-width: 1024px) {
      .app-page {
        padding: 0;
      }
      .app-sidebar {
        top: 0;
        left: 0;
        border-radius: 0 18px 18px 0;
        height: 100vh;
        min-height: 100vh;
        transform: translateX(-100%);
      }
      .app-sidebar.sidebar-open {
        transform: translateX(0);
        box-shadow: 4px 0 32px rgba(192,57,43,0.15);
      }
      .app-main-wrapper {
        margin-left: 0;
        padding: 0.75rem;
      }
      .topbar-hamburger {
        display: flex;
      }
      .app-topbar {
        top: 0;
        border-radius: 12px;
      }

      #modal-portal { position:fixed; inset:0; pointer-events:none; z-index:9999; }
#modal-portal > * { pointer-events:all; }
    }

    #modal-portal button[type="submit"] {
  outline: none !important;
  border-radius: 10px !important;
  font-family: 'DM Sans', sans-serif !important;
  font-size: 0.875rem !important;
  font-weight: 600 !important;
  color: #fff !important;
  border: none !important;
  cursor: pointer !important;
  min-height: 42px !important;
  width: 100% !important;
  display: flex !important;
  align-items: center !important;
  justify-content: center !important;
}

  </style>
</head>
<body>
<div class="app-page">

  {{-- Sidebar --}}
  @include('components.navbar-app')

  {{-- Overlay for mobile --}}
  <div class="sidebar-overlay" id="sidebar-overlay"></div>

  {{-- Main wrapper --}}
  <div class="app-main-wrapper">

    {{-- Top bar --}}
    @include('components.navbar-app-topbar')

    {{-- Flash messages --}}
    @include('components.flash-message')

    {{-- Page content --}}
    <div class="app-content">
      {{ $slot }}
    </div>

  </div>

</div>

  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/flatpickr.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/countup.js@2.8.0/dist/countUp.umd.js"></script>
  <script>
    AOS.init({ duration: 450, easing: 'ease-out-cubic', once: true, offset: 20 });

    // Flash messages
    function dismissFlash(el) {
      el.style.transition = 'all 0.35s ease';
      el.style.opacity = '0';
      el.style.transform = 'translateX(120%)';
      setTimeout(() => el.remove(), 350);
    }
    document.querySelectorAll('.flash-msg').forEach(el => setTimeout(() => dismissFlash(el), 5000));
    document.querySelectorAll('.flash-close').forEach(btn => btn.addEventListener('click', () => dismissFlash(btn.closest('.flash-msg'))));

    // Sidebar mobile toggle
    const sidebarToggle  = document.getElementById('sidebar-toggle');
    const sidebar        = document.getElementById('app-sidebar');
    const sidebarOverlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
      sidebar.classList.add('sidebar-open');
      sidebarOverlay.classList.add('active');
      document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
      sidebar.classList.remove('sidebar-open');
      sidebarOverlay.classList.remove('active');
      document.body.style.overflow = '';
    }

    if (sidebarToggle) {
      sidebarToggle.addEventListener('click', () => {
        sidebar.classList.contains('sidebar-open') ? closeSidebar() : openSidebar();
      });
    }
    if (sidebarOverlay) {
      sidebarOverlay.addEventListener('click', closeSidebar);
    }

    // Flatpickr
    document.querySelectorAll('.flatpickr-date').forEach(el => flatpickr(el, { dateFormat: 'm/d/Y', allowInput: true }));
    document.querySelectorAll('.flatpickr-datetime').forEach(el => flatpickr(el, { enableTime: true, dateFormat: 'm/d/Y h:i K', minDate: 'today', allowInput: true }));
  
  // Modal open/close via custom events
  document.addEventListener('open-modal', e => {
    const el = document.getElementById('modal-overlay-' + e.detail);
    if (el) el.style.display = 'block';
  });
  document.addEventListener('close-modal', e => {
    document.querySelectorAll('[id^="modal-overlay-"]').forEach(el => el.style.display = 'none');
  });
  // Per-modal close events
  document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('[id^="modal-overlay-"]').forEach(el => {
      const id = el.id.replace('modal-overlay-', '');
      document.addEventListener('close-modal-' + id, () => el.style.display = 'none');
    });
  });

  // Alpine open-modal dispatch compatibility
  window.addEventListener('open-modal', e => {
    const el = document.getElementById('modal-overlay-' + e.detail);
    if (el) el.style.display = 'block';
  });
  window.addEventListener('close-modal', () => {
    document.querySelectorAll('[id^="modal-overlay-"]').forEach(el => el.style.display = 'none');
  });
  
  function openModal(id) {
  const el = document.getElementById('modal-overlay-' + id);
  if (el) {
    el.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }
}

function closeModal(id) {
  if (id && id !== 'null' && id !== null) {
    const el = document.getElementById('modal-overlay-' + id);
    if (el) el.style.display = 'none';
  } else {
    document.querySelectorAll('[id^="modal-overlay-"]').forEach(el => el.style.display = 'none');
  }
  document.body.style.overflow = '';
}

// Alpine $dispatch('open-modal', 'id') compatibility
window.addEventListener('open-modal', e => openModal(e.detail));

// Alpine $dispatch('close-modal') compatibility — with or without detail
window.addEventListener('close-modal', e => closeModal(e.detail));
document.addEventListener('close-modal', () => closeModal());

// Escape key closes all modals
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') closeModal();
});

  </script>

  @stack('scripts')
  @stack('modals')

  {{-- Global confirm portal --}}
  <div id="modal-portal"
    x-data="{ open: false, data: {} }"
    x-on:open-confirm.window="open = true; data = $event.detail"
    x-on:close-confirm.window="open = false"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="open = false"
    @click.self="open = false"
    style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:99999; background:rgba(0,0,0,0.5); backdrop-filter:blur(2px);">

    <div
      x-transition:enter="transition ease-out duration-200"
      x-transition:enter-start="opacity-0 scale-95"
      x-transition:enter-end="opacity-100 scale-100"
      x-transition:leave="transition ease-in duration-150"
      x-transition:leave-start="opacity-100 scale-100"
      x-transition:leave-end="opacity-0 scale-95"
      style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:16px; width:90%; max-width:420px; box-shadow:0 20px 60px rgba(0,0,0,0.2); overflow:hidden;">

      <div style="padding:2rem 2rem 0; text-align:center;">

        {{-- Danger icon (red) --}}
        <div x-show="data.type === 'danger'"
          style="width:60px; height:60px; border-radius:50%; background:rgba(192,57,43,0.1); border:2px solid rgba(192,57,43,0.2); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
          <svg width="26" height="26" fill="none" stroke="#E74C3C" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>

        {{-- Warning icon (orange) --}}
        <div x-show="data.type === 'warning'"
          style="width:60px; height:60px; border-radius:50%; background:rgba(230,126,34,0.1); border:2px solid rgba(230,126,34,0.2); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
          <svg width="26" height="26" fill="none" stroke="#E67E22" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
          </svg>
        </div>

        {{-- Info icon (blue) --}}
        <div x-show="data.type !== 'danger' && data.type !== 'warning'"
          style="width:60px; height:60px; border-radius:50%; background:rgba(41,128,185,0.1); border:2px solid rgba(41,128,185,0.2); display:flex; align-items:center; justify-content:center; margin:0 auto 1.25rem;">
          <svg width="26" height="26" fill="none" stroke="#3498DB" stroke-width="1.8" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
        </div>

        <h3 style="font-size:1.1rem; font-weight:700; color:#1a1a2e; margin-bottom:0.5rem;" x-text="data.title"></h3>
        <p style="font-size:0.875rem; color:#888; line-height:1.6; margin-bottom:1.5rem;" x-text="data.message"></p>
      </div>

      <div style="padding:0 2rem 2rem; display:flex; gap:0.75rem;">
        <button @click="open = false"
          style="flex:1; min-height:42px; padding:0.7rem 1rem; border-radius:10px; border:1.5px solid #e0e0e0; background:#fff; color:#555; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;"
          onmouseover="this.style.background='#f5f5f5'"
          onmouseout="this.style.background='#fff'">
          Cancel
        </button>
        <form :action="data.action" method="POST" style="flex:1; display:flex;">
          @csrf
          <input type="hidden" name="_method" :value="data.method || 'DELETE'"/>
          <template x-if="data.fields">
            <template x-for="[key, val] in Object.entries(data.fields || {})">
              <input type="hidden" :name="key" :value="val"/>
            </template>
          </template>
          <button type="submit"
            :style="data.type === 'danger'
              ? 'background:linear-gradient(135deg,#C0392B,#e94560); box-shadow:0 3px 12px rgba(192,57,43,0.35);'
              : data.type === 'warning'
              ? 'background:linear-gradient(135deg,#E67E22,#F39C12); box-shadow:0 3px 12px rgba(230,126,34,0.35);'
              : 'background:linear-gradient(135deg,#2980B9,#3498DB); box-shadow:0 3px 12px rgba(41,128,185,0.35);'"
            style="width:100%; min-height:42px; padding:0.7rem 1rem; border-radius:10px; border:none; color:#fff; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body); display:flex; align-items:center; justify-content:center; transition:opacity 0.2s;"
            onmouseover="this.style.opacity='0.88'"
            onmouseout="this.style.opacity='1'"
            x-text="data.confirmLabel || 'Confirm'">
          </button>
        </form>
      </div>
    </div>
  </div>

  {{-- Global logout confirm --}}
  <div id="logout-portal"
    x-data="{ open: false }"
    x-on:open-logout.window="open = true"
    x-show="open"
    x-transition:enter="transition ease-out duration-200"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-150"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    @keydown.escape.window="open = false"
    @click.self="open = false"
    style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:99999; background:rgba(0,0,0,0.5); backdrop-filter:blur(2px);">

    <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:16px; padding:2rem; max-width:380px; width:90%; box-shadow:0 20px 60px rgba(0,0,0,0.2);">
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
        <button @click="open = false"
          style="flex:1; min-height:42px; padding:0.7rem 1rem; border-radius:10px; border:1.5px solid #e0e0e0; background:#fff; color:#555; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body); transition:all 0.2s;"
          onmouseover="this.style.background='#f5f5f5'"
          onmouseout="this.style.background='#fff'">
          Cancel
        </button>
        <form method="POST" action="{{ route('logout') }}" style="flex:1; display:flex;">
          @csrf
          <button type="submit"
            style="width:100%; min-height:42px; padding:0.7rem 1rem; border-radius:10px; border:none; background:linear-gradient(135deg,#C0392B,#e94560); color:#fff; font-size:0.875rem; font-weight:600; cursor:pointer; font-family:var(--font-body); display:flex; align-items:center; justify-content:center; box-shadow:0 3px 12px rgba(192,57,43,0.35); transition:opacity 0.2s;"
            onmouseover="this.style.opacity='0.88'"
            onmouseout="this.style.opacity='1'">
            Sign Out
          </button>
        </form>
      </div>
    </div>
  </div>

</body>
</html>
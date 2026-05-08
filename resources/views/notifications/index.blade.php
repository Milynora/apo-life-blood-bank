<x-app-layout title="Notifications">

  <div class="page-header">
    <div>
      <div class="breadcrumb"><span>Notifications</span></div>
      <h1 class="page-title">Notifications</h1>
      <p class="page-subtitle">Your alerts, updates, and messages.</p>
    </div>

    <div style="display:flex; gap:0.75rem; align-items:center;">
      {{-- Mark All as Read button --}}
      @if(auth()->user()->unreadNotifications()->count() > 0)
        <form method="POST" action="{{ route('notifications.readAll') }}">
          @csrf @method('PATCH')
          <button type="submit" class="btn btn-dash-primary btn-sm">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Mark All as Read
          </button>
        </form>
      @endif

      {{-- Clear Read button with confirm dialog --}}
      @if(auth()->user()->notifications()->whereNotNull('read_at')->exists())
        <div x-data>
          <button type="button"
            @click="$dispatch('open-modal', 'clear-all-read')"
            class="btn btn-sm"
            style="background:#fff; border:1.5px solid rgba(192,57,43,0.3); color:#E74C3C;">
            <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
            </svg>
            Clear Read
          </button>
          <x-confirm-dialog
            id="clear-all-read"
            title="Clear All Read Notifications?"
            message="All read notifications will be permanently deleted. This cannot be undone."
            confirm-label="Clear All"
            confirm-class="btn-danger"
            action="{{ route('notifications.destroyAllRead') }}"
            method="DELETE"/>
        </div>
      @endif
    </div>
  </div>

  <div x-data="{ filter: 'all' }">

    {{-- Filter tabs --}}
    <div style="display:flex; gap:0.5rem; margin-bottom:1.5rem; flex-wrap:wrap;">
      @foreach(['all' => 'All', 'unread' => 'Unread', 'read' => 'Read'] as $val => $lbl)
        <button type="button"
          @click="filter = '{{ $val }}'"
          :class="filter === '{{ $val }}' ? 'notif-tab-active' : 'notif-tab-inactive'"
          class="notif-tab">
          {{ $lbl }}
          @if($val === 'unread')
            @php $uc = auth()->user()->unreadNotifications()->count(); @endphp
            @if($uc > 0)
              <span class="notif-tab-badge" :class="filter==='unread' ? 'notif-tab-badge-active' : ''">
                {{ $uc }}
              </span>
            @endif
          @endif
        </button>
      @endforeach
    </div>

    <div class="dash-card">
      <div class="dash-card-header">
        <h3 class="dash-card-title">
          @php
            $totalRead   = $notifications->getCollection()->filter(fn($n) => !is_null($n->read_at))->count();
            $totalUnread = $notifications->getCollection()->filter(fn($n) => is_null($n->read_at))->count();
          @endphp
          <span x-show="filter==='all'">All Notifications</span>
          <span x-show="filter==='unread'" style="display:none;">Unread</span>
          <span x-show="filter==='read'" style="display:none;">Read</span>
          <span style="font-size:0.75rem; font-weight:400; color:#bbb; margin-left:0.4rem;">
            (<span x-show="filter==='all'">{{ $notifications->total() }}</span><span x-show="filter==='unread'" style="display:none;">{{ $totalUnread }}</span><span x-show="filter==='read'" style="display:none;">{{ $totalRead }}</span>)
          </span>
        </h3>
      </div>

      @if($notifications->isEmpty())
        <div class="dash-card-body" style="padding:4rem 2rem;">
          <x-empty-state title="No notifications yet" message="Notifications about your activities will appear here."/>
        </div>
      @else

        @php
          $grouped = $notifications->getCollection()->groupBy(function($n) {
            if ($n->created_at->isToday())     return 'Today';
            if ($n->created_at->isYesterday()) return 'Yesterday';
            return $n->created_at->format('F d, Y');
          });
        @endphp

        @foreach($grouped as $dateLabel => $items)
          @php
            $hasUnread = $items->filter(fn($n) => is_null($n->read_at))->count() > 0;
            $hasRead   = $items->filter(fn($n) => !is_null($n->read_at))->count() > 0;
          @endphp
          <div
            x-show="filter === 'all'
              || (filter === 'unread' && {{ $hasUnread ? 'true' : 'false' }})
              || (filter === 'read'   && {{ $hasRead   ? 'true' : 'false' }})"
            style="padding:0.55rem 1.5rem; background:#f8f9fc; border-bottom:1px solid var(--border-light); border-top:{{ $loop->first ? 'none' : '1px solid var(--border-light)' }};">
            <span style="font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.1em; color:#bbb;">{{ $dateLabel }}</span>
          </div>

          @foreach($items as $notif)
            @php $isRead = !is_null($notif->read_at); @endphp
            <div
              x-show="filter === 'all'
                || (filter === 'unread' && {{ $isRead ? 'false' : 'true' }})
                || (filter === 'read'   && {{ $isRead ? 'true' : 'false' }})"
              style="border-bottom:1px solid rgba(0,0,0,0.04);">
              <x-notification-item :notification="$notif"/>
            </div>
          @endforeach
        @endforeach

        {{-- All-caught-up state for unread filter --}}
        <div x-show="filter === 'unread' && {{ $totalUnread === 0 ? 'true' : 'false' }}"
          style="padding:3rem 2rem; text-align:center; color:#aaa; font-size:0.875rem; display:none;">
          <svg width="48" height="48" fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24" style="opacity:0.25; margin:0 auto 1rem; display:block;">
            <path stroke-linecap="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
          </svg>
          You're all caught up!
        </div>

        <div class="dash-card-footer">
          <x-pagination :paginator="$notifications"/>
        </div>
      @endif
    </div>

  </div>

  <style>
    .notif-tab {
      display: inline-flex;
      align-items: center;
      gap: 0.45rem;
      padding: 0.55rem 1.25rem;
      border-radius: 10px;
      border: 1.5px solid var(--border-light);
      font-size: 0.84rem;
      font-weight: 600;
      cursor: pointer;
      font-family: var(--font-body);
      transition: all 0.2s;
      background: #fff;
    }
    .notif-tab-active {
      background: linear-gradient(135deg, var(--primary), #e94560) !important;
      color: #fff !important;
      border-color: transparent !important;
      box-shadow: 0 3px 10px rgba(192,57,43,0.3);
    }
    .notif-tab-inactive { color: #888; }
    .notif-tab-inactive:hover {
      background: rgba(192,57,43,0.05);
      border-color: rgba(192,57,43,0.25);
      color: var(--primary);
    }
    .notif-tab-badge {
      background: rgba(192,57,43,0.1);
      color: var(--primary);
      font-size: 0.68rem;
      font-weight: 700;
      padding: 0.1rem 0.5rem;
      border-radius: 20px;
      transition: all 0.2s;
    }
    .notif-tab-badge-active {
      background: rgba(255,255,255,0.25) !important;
      color: #fff !important;
    }
  </style>

</x-app-layout>
@props(['notification'])

@php
  $data   = $notification->data;
  $type   = $data['type'] ?? 'system';
  $isRead = !is_null($notification->read_at);

  $iconMap = [
    'account_approved'       => ['path' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'color' => '#27AE60', 'bg' => 'rgba(39,174,96,0.12)'],
    'account_rejected'       => ['path' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                                                                   'color' => '#E74C3C', 'bg' => 'rgba(192,57,43,0.12)'],
    'appointment_scheduled'  => ['path' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',                                                                                                                 'color' => '#2980B9', 'bg' => 'rgba(41,128,185,0.12)'],
    'appointment_approved'   => ['path' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                                                                                            'color' => '#27AE60', 'bg' => 'rgba(39,174,96,0.12)'],
    'appointment_cancelled'  => ['path' => 'M6 18L18 6M6 6l12 12',                                                                                                                                                                                     'color' => '#E67E22', 'bg' => 'rgba(230,126,34,0.12)'],
    'donation_recorded'      => ['path' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z',                                                                            'color' => '#E74C3C', 'bg' => 'rgba(192,57,43,0.12)'],
    'request_status_changed' => ['path' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2',                                                                       'color' => '#9B59B6', 'bg' => 'rgba(155,89,182,0.12)'],
    'blood_unit_expiring'    => ['path' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z',                                                                   'color' => '#F39C12', 'bg' => 'rgba(243,156,18,0.12)'],
    'system'                 => ['path' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                                                                               'color' => '#636e72', 'bg' => 'rgba(99,110,114,0.12)'],
  ];
  $icon = $iconMap[$type] ?? $iconMap['system'];
@endphp

{{-- PATCH to notifications.read — marks as read then redirects to the notification's URL --}}
<form method="POST" action="{{ route('notifications.read', $notification->id) }}" style="margin:0;">
  @csrf @method('PATCH')
  <button type="submit"
    style="display:flex; gap:1rem; padding:1rem 1.5rem; width:100%; text-align:left;
           transition:background 0.2s; position:relative; cursor:pointer;
           background:{{ $isRead ? 'transparent' : 'rgba(192,57,43,0.025)' }};
           border:none; border-bottom:1px solid rgba(0,0,0,0.05);
           font-family:var(--font-body);"
    onmouseover="this.style.background='rgba(192,57,43,0.04)'"
    onmouseout="this.style.background='{{ $isRead ? 'transparent' : 'rgba(192,57,43,0.025)' }}'">

    {{-- Unread left bar --}}
    @if(!$isRead)
      <div style="position:absolute; left:0; top:0; bottom:0; width:3px; background:var(--primary); border-radius:0 2px 2px 0;"></div>
    @endif

    {{-- Icon --}}
    <div style="width:42px; height:42px; border-radius:12px; background:{{ $icon['bg'] }}; display:flex; align-items:center; justify-content:center; flex-shrink:0;">
      <svg width="18" height="18" fill="none" stroke="{{ $icon['color'] }}" stroke-width="1.8" viewBox="0 0 24 24">
        <path stroke-linecap="round" d="{{ $icon['path'] }}"/>
      </svg>
    </div>

    {{-- Text --}}
    <div style="flex:1; min-width:0;">
      <div style="font-size:0.875rem; color:{{ $isRead ? '#555' : '#1a1a2e' }}; font-weight:{{ $isRead ? '400' : '500' }}; line-height:1.5; margin-bottom:0.3rem; text-align:left;">
        {{ $data['message'] ?? 'You have a new notification.' }}
      </div>
      <div style="font-size:0.75rem; color:#aaa; display:flex; align-items:center; gap:0.4rem;">
        <svg width="11" height="11" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        {{ $notification->created_at->diffForHumans() }}
        <span style="color:#ddd;">•</span>
        <span style="text-transform:capitalize; color:#bbb;">{{ str_replace('_', ' ', $type) }}</span>
      </div>
    </div>

    {{-- Unread dot --}}
    @if(!$isRead)
      <div style="width:8px; height:8px; border-radius:50%; background:var(--primary); flex-shrink:0; margin-top:6px; align-self:flex-start;"></div>
    @endif

  </button>
</form>
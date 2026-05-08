@props([
  'title',
  'value',
  'icon',
  'color'    => 'red',
  'trend'    => null,
  'trendDir' => 'up',
  'link'     => null,
  'linkLabel'=> 'View all',
  'prefix'   => '',
  'suffix'   => '',
])

@php
  $colorMap = [
    'red'    => ['gradient' => 'linear-gradient(135deg, #C0392B, #e94560)', 'shadow' => 'rgba(192,57,43,0.35)'],
    'blue'   => ['gradient' => 'linear-gradient(135deg, #2980B9, #3498DB)', 'shadow' => 'rgba(41,128,185,0.35)'],
    'green'  => ['gradient' => 'linear-gradient(135deg, #27AE60, #2ECC71)', 'shadow' => 'rgba(39,174,96,0.35)'],
    'yellow' => ['gradient' => 'linear-gradient(135deg, #E67E22, #F39C12)', 'shadow' => 'rgba(230,126,34,0.35)'],
    'purple' => ['gradient' => 'linear-gradient(135deg, #8E44AD, #9B59B6)', 'shadow' => 'rgba(142,68,173,0.35)'],
    'teal'   => ['gradient' => 'linear-gradient(135deg, #16A085, #1ABC9C)', 'shadow' => 'rgba(22,160,133,0.35)'],
    'pink'   => ['gradient' => 'linear-gradient(135deg, #e94560, #f06292)', 'shadow' => 'rgba(233,69,96,0.35)'],
    'gray'   => ['gradient' => 'linear-gradient(135deg, #636e72, #b2bec3)', 'shadow' => 'rgba(99,110,114,0.35)'],
    'navy'   => ['gradient' => 'linear-gradient(135deg, #2C3E50, #34495E)', 'shadow' => 'rgba(44,62,80,0.35)'],
    'orange' => ['gradient' => 'linear-gradient(135deg, #D35400, #E67E22)', 'shadow' => 'rgba(211,84,0,0.35)'],
    'cyan'   => ['gradient' => 'linear-gradient(135deg, #0984e3, #74b9ff)', 'shadow' => 'rgba(9,132,227,0.35)'],
    'lime'   => ['gradient' => 'linear-gradient(135deg, #6ab04c, #badc58)',  'shadow' => 'rgba(106,176,76,0.35)'],
  ];
  $c = $colorMap[$color] ?? $colorMap['red'];
@endphp

<div style="background:{{ $c['gradient'] }}; border-radius:12px; padding:1.1rem 1.25rem; display:flex; align-items:center; gap:1rem; box-shadow:0 4px 15px {{ $c['shadow'] }}; transition:all 0.2s;"
  onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 25px {{ $c['shadow'] }}'"
  onmouseout="this.style.transform=''; this.style.boxShadow='0 4px 15px {{ $c['shadow'] }}'">

  {{-- Icon --}}
  <div style="width:44px; height:44px; border-radius:0; background:rgba(255,255,255,0.2); display:flex; align-items:center; justify-content:center; flex-shrink:0;">
    <svg width="20" height="20" fill="none" stroke="#fff" stroke-width="1.8" viewBox="0 0 24 24">
      <path stroke-linecap="round" d="{{ $icon }}"/>
    </svg>
  </div>

  {{-- Text --}}
  <div style="flex:1; min-width:0;">
    <div style="font-size:0.72rem; color:rgba(255,255,255,0.8); text-transform:uppercase; letter-spacing:0.06em; margin-bottom:3px;">{{ $title }}</div>
    <div style="font-size:1.45rem; font-weight:800; color:#fff; line-height:1;">{{ $prefix }}{{ is_numeric($value) ? number_format($value) : $value }}{{ $suffix }}</div>
    @if($trend !== null)
      <div style="font-size:0.72rem; font-weight:600; margin-top:4px; display:flex; align-items:center; gap:3px; color:rgba(255,255,255,0.85);">
        @if($trendDir === 'up')
          <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        @else
          <svg width="10" height="10" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
        @endif
        {{ $trend }}
      </div>
    @endif
  </div>

  {{-- Link --}}
  @if($link)
    <a href="{{ $link }}"
      style="font-size:0.72rem; color:rgba(255,255,255,0.85); text-decoration:none; font-weight:600; white-space:nowrap; flex-shrink:0; background:rgba(255,255,255,0.15); padding:0.3rem 0.65rem; border-radius:8px; transition:all 0.2s;"
      onmouseover="this.style.background='rgba(255,255,255,0.25)'"
      onmouseout="this.style.background='rgba(255,255,255,0.15)'">
      {{ $linkLabel }} →
    </a>
  @endif

</div>
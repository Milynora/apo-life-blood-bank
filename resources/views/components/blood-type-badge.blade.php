@props(['type'])

@php
  $slugMap = [
    'A+'  => 'ap',  'A-'  => 'am',
    'B+'  => 'bp',  'B-'  => 'bm',
    'AB+' => 'abp', 'AB-' => 'abm',
    'O+'  => 'op',  'O-'  => 'om',
  ];
  $slug = $slugMap[$type] ?? 'op';
@endphp

<span class="badge badge-bt badge-bt-{{ $slug }}">{{ $type }}</span>
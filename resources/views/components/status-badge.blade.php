@props(['status', 'size' => 'normal'])

@php
  // 1. Extract the string value if it's an Enum, otherwise keep the string
  $statusString = $status instanceof \UnitEnum ? $status->value : $status;
  
  // 2. Normalize for the map
  $normalizedStatus = strtolower($statusString);

  $map = [
    'pending'    => 'pending',
    'approved'   => 'approved',
    'rejected'   => 'rejected',
    'fulfilled'  => 'fulfilled',
    'successful' => 'success',
    'failed'     => 'failed',
    'suspended'  => 'suspended',
    'cancelled'  => 'cancelled',
    'completed'  => 'completed',
    'no_show'    => 'cancelled',
    'fit'        => 'fit',
    'unfit'      => 'unfit',
    'available'  => 'available',
    'reserved'   => 'reserved',
    'used'       => 'used',
    'expired'    => 'expired',
  ];

  $cls = $map[$normalizedStatus] ?? 'pending';
  $label = ucfirst(str_replace('_', ' ', $statusString));
@endphp

<span class="badge badge-{{ $cls }}" @if($size === 'sm') style="font-size:0.7rem; padding:0.2rem 0.6rem;" @endif>
  {{ $label }}
</span>
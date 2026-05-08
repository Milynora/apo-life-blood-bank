@props(['id', 'title' => '', 'size' => 'md'])

@php
  $maxWidths = ['sm' => '420px', 'md' => '520px', 'lg' => '700px', 'xl' => '900px'];
  $maxW = $maxWidths[$size] ?? '520px';
@endphp

@push('modals')
<div
  id="modal-overlay-{{ $id }}"
  style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; z-index:99999; background:rgba(0,0,0,0.5); backdrop-filter:blur(2px);"
  onclick="if(event.target===this) closeModal('{{ $id }}')">

  <div
    style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); background:#fff; border-radius:16px; width:90%; max-width:{{ $maxW }}; max-height:90vh; overflow-y:auto; box-shadow:0 20px 60px rgba(0,0,0,0.15);"
    onclick="event.stopPropagation()">

    @if($title)
      <div style="display:flex; align-items:center; justify-content:space-between; padding:1.25rem 1.5rem; border-bottom:1px solid #f0f0f0;">
        <h3 style="font-size:1rem; font-weight:700; color:#1a1a2e; margin:0;">{{ $title }}</h3>
        <button
          onclick="closeModal('{{ $id }}')"
          style="width:30px; height:30px; border-radius:8px; border:none; background:#f5f5f5; color:#888; cursor:pointer; display:flex; align-items:center; justify-content:center;"
          onmouseover="this.style.background='#eee'"
          onmouseout="this.style.background='#f5f5f5'">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
            <path stroke-linecap="round" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>
    @endif

    <div style="padding:1.5rem;">
      {{ $slot }}
    </div>

  </div>
</div>
@endpush
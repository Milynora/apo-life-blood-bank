@props(['title' => 'Nothing here yet', 'message' => '', 'action' => null, 'actionLabel' => 'Add New'])

<div class="empty-state">
  <svg fill="none" stroke="currentColor" stroke-width="1" viewBox="0 0 24 24">
    <path stroke-linecap="round" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
  </svg>
  <h3>{{ $title }}</h3>
  @if($message)
    <p>{{ $message }}</p>
  @endif
  @if($action)
    <div style="margin-top:1.5rem;">
      <a href="{{ $action }}" class="btn btn-dash-primary btn-sm">{{ $actionLabel }}</a>
    </div>
  @endif
</div>
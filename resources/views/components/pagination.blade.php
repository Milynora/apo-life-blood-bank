@props(['paginator'])

@if($paginator->hasPages())
  <div style="display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:1rem; margin-top:1.5rem; padding-top:1.25rem; border-top:1px solid var(--border-light);">

    {{-- Info text --}}
    <div style="font-size:0.8rem; color:#888;">
  Showing
  <strong style="color:#333;">{{ $paginator->lastItem() }}</strong>
  of
  <strong style="color:#333;">{{ $paginator->total() }}</strong>
  entries
</div>

    {{-- Page buttons --}}
    <div class="pagination">

      {{-- Previous --}}
      @if($paginator->onFirstPage())
        <span class="disabled" aria-disabled="true" aria-label="Previous">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
        </span>
      @else
        <a href="{{ $paginator->previousPageUrl() }}" aria-label="Previous" rel="prev">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M15 19l-7-7 7-7"/></svg>
        </a>
      @endif

      {{-- Page numbers --}}
      @foreach($paginator->getUrlRange(max(1, $paginator->currentPage() - 2), min($paginator->lastPage(), $paginator->currentPage() + 2)) as $page => $url)
        @if($page == $paginator->currentPage())
          <span class="active" aria-current="page">{{ $page }}</span>
        @else
          <a href="{{ $url }}">{{ $page }}</a>
        @endif
      @endforeach

      {{-- Next --}}
      @if($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" aria-label="Next" rel="next">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
        </a>
      @else
        <span class="disabled" aria-disabled="true" aria-label="Next">
          <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" d="M9 5l7 7-7 7"/></svg>
        </span>
      @endif

    </div>
  </div>
@endif
@props([
  'id',
  'title'        => 'Are you sure?',
  'message'      => 'This action cannot be undone.',
  'confirmLabel' => 'Confirm',
  'confirmClass' => 'btn-danger',
  'action'       => '',
  'method'       => 'DELETE',
  'fields'       => [],
])

<div
  x-data
  x-on:open-modal.window="
    if ($event.detail === '{{ $id }}') {
      $dispatch('open-confirm', {
        title:        '{{ addslashes($title) }}',
        message:      '{{ addslashes($message) }}',
        confirmLabel: '{{ addslashes($confirmLabel) }}',
        type:         '{{ Str::contains($confirmClass, 'danger') ? 'danger' : 'info' }}',
        action:       '{{ $action }}',
        method:       '{{ $method }}',
        fields:       {{ json_encode((object)$fields) }}
      })
    }
  ">
</div>
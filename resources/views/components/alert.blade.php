@props(['title', 'type', 'messages'])

<div id="alert-container" class="alert alert-{{ $type }} fade show alert-dismissible" role="alert" style="display: {{ $display }};">
    <strong class="font-weight-bolder">{{ $title }}</strong>
    <div id="error-messages">
        @foreach ($messages as $message)
          <p class="mb-0">{{ $message }}</p>
        @endforeach
    </div>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>

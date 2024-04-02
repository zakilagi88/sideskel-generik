@php
    $data = $getState();
@endphp

<div class="w-full h-screen" style="height: 80vh;">
    <embed src="{{ asset('/storage/' . $data->dok_path) }}" type="application/pdf" class="w-full h-full" />
</div>

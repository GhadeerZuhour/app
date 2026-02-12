@props([
    'title' => '',
    'value' => '',
    'color' => 'secondary',
])

<div class="col-md-3">
    <div class="card text-white bg-{{ $color }} p-3">
        <h6 class="mb-1">{{ $title }}</h6>
        <h3 class="mb-0">{{ $value }}</h3>
    </div>
</div>

@props(['route', 'label', 'count' => null, 'params' => []])

<a href="{{ route($route, $params) }}" style="width: 150px"
    class="text-center text-decoration-none border rounded p-3 bg-secondary-subtle mt-3 d-flex flex-column align-items-center">
    {{ $label }}
    @if ($count > 0)
        <span style="width: 30px; height: 30px;"
            class="d-flex justify-content-center align-items-center p-2 rounded-circle text-white bg-warning">
            {{ $count }}
        </span>
    @endif
</a>

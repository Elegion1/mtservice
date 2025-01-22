<button class="btn btn-warning btn-sm" data-bs-toggle="modal"
    data-bs-target="#edit{{ $id }}Modal" data-id="{{ $id }}"
    onclick="window.showModal('{{ $id }}', {{ json_encode($data) }})">
    {{ $label ?? 'Modifica' }}
</button>

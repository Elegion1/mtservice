@if (isset($label))
    <button class="btn btn-sm m-0 p-0" data-bs-toggle="modal" data-bs-target="#edit{{ $id }}Modal"
        data-id="{{ $id }}" onclick="window.showModal('{{ $id }}', {{ json_encode($data) }})">
        <i class="bi bi-pencil-square"></i>
    </button>
@else
    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#edit{{ $id }}Modal"
        data-id="{{ $id }}" onclick="window.showModal('{{ $id }}', {{ json_encode($data) }})">
        Modifica
    </button>
@endif

<div class="modal fade" id="edit{{ $id }}Modal" tabindex="-1" aria-labelledby="{{ $id }}ModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="{{ $id }}ModalLabel">{{ $title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="edit{{ $id }}Form" action="" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method($method ?? 'PUT')
                    {{ $slot }}
                    <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                </form>
            </div>
        </div>
    </div>
</div>

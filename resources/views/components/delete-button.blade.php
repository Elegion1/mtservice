<form id="delete-form-{{ $model->id }}" action="{{ route($route, $model) }}" method="POST"
    style="display:inline-block;">
    @csrf
    @method('DELETE')
    @if (isset($label))
        <button type="submit" class="btn text-danger btn-sm" onclick="confirmDelete({{ $model->id }})">
            <i class="bi bi-trash3-fill"></i>
        </button>
    @else
        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $model->id }})">
            Elimina
        </button>
    @endif
</form>

<script>
    function confirmDelete(modelId) {
        const form = document.getElementById('delete-form-' + modelId);
        const confirmation = confirm("Sei sicuro di voler eliminare questo elemento?");

        if (confirmation) {
            form.submit();
        }
    }
</script>

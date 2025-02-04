<form id="delete-{{ $route }}-form-{{ $model->id }}" action="{{ route($route . '.destroy', $model) }}"
    method="POST" style="display:inline-block;">
    @csrf
    @method('DELETE')
    @if (isset($label))
        <button type="button" class="btn text-danger btn-sm"
            onclick="confirmDelete('{{ $model->id }}', '{{ $route }}')">
            <i class="bi bi-trash3-fill"></i>
        </button>
    @else
        <button type="button" class="btn btn-danger btn-sm"
            onclick="confirmDelete('{{ $model->id }}', '{{ $route }}')">
            Elimina
        </button>
    @endif
</form>

<script>
    function confirmDelete(modelId, route) {
        // Trova il modulo associato all'elemento da eliminare
        const form = document.getElementById(`delete-${route}-form-${modelId}`);

        // Mostra una finestra di conferma
        const confirmation = confirm("Sei sicuro di voler eliminare questo elemento?");

        // Se confermato, invia il modulo
        if (confirmation) {
            form.submit();
        }
    }
</script>

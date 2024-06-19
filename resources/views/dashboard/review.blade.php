<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione Recensioni</h1>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('reviews.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3 col-3">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3 col-7">
                    <label for="title" class="form-label">Titolo</label>
                    <input type="text" class="form-control" id="title" name="title" required>
                </div>
                <div class="mb-3 col-2">
                    <label for="rating" class="form-label">Valutazione</label>
                    <input type="number" class="form-control" id="rating" name="rating" required min="1" max="5">
                </div>
                <div class="mb-3 col-12">
                    <label for="body" class="form-label">Recensione</label>
                    <textarea class="form-control" id="body" name="body" required></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi Recensione</button>
        </form>
        <hr>
        <h2>Tutte le Recensioni</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Titolo</th>
                    <th>Recensione</th>
                    <th>Valutazione</th>
                    <th>Data di aggiunta</th>
                    <th>Data di modifica</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($reviews as $review)
                    <tr>
                        <td>{{ $review->id }}</td>
                        <td>{{ $review->name }}</td>
                        <td>{{ $review->title }}</td>
                        <td>{{ $review->body }}</td>
                        <td>{{ $review->rating }}</td>
                        <td>{{ $review->created_at }}</td>
                        <td>{{ $review->updated_at }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editReviewModal" data-id="{{ $review->id }}"
                                data-name="{{ $review->name }}" data-title="{{ $review->title }}" 
                                data-body="{{ $review->body }}" data-rating="{{ $review->rating }}">Modifica</button>
                            <form action="{{ route('reviews.destroy', $review) }}" method="POST"
                                style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Elimina</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Modale per Modifica Recensione -->
    <div class="modal fade" id="editReviewModal" tabindex="-1" aria-labelledby="editReviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editReviewModalLabel">Modifica Recensione</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editReviewForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_title" class="form-label">Titolo</label>
                            <input type="text" class="form-control" id="edit_title" name="title" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_body" class="form-label">Recensione</label>
                            <textarea class="form-control" id="edit_body" name="body" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="edit_rating" class="form-label">Valutazione</label>
                            <input type="number" class="form-control" id="edit_rating" name="rating" required min="1" max="5">
                        </div>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var editReviewModal = document.getElementById('editReviewModal');
            editReviewModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var title = button.getAttribute('data-title');
                var body = button.getAttribute('data-body');
                var rating = button.getAttribute('data-rating');

                var modal = this;
                modal.querySelector('#edit_name').value = name;
                modal.querySelector('#edit_title').value = title;
                modal.querySelector('#edit_body').value = body;
                modal.querySelector('#edit_rating').value = rating;

                var form = modal.querySelector('#editReviewForm');
                form.action = '{{ url("dashboard/reviews") }}/' + id;
            });
        });
    </script>
</x-dashboard-layout>

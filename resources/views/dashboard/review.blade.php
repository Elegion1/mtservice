<x-dashboard-layout>

    <a href="{{ route('dashboard.jobs') }}">Visualizza i jobs</a>
    <h1>Gestione Recensioni</h1>

    <div class="row">
        <div class="col-12 col-md-6">
            <!-- Form di Filtro per Stato Recensione -->
            <form id="filterForm">
                <div class="row">
                    <div class="col-6">
                        <label for="status" class="form-label">Filtra per Stato</label>
                        <select id="statusFilter" class="form-select">
                            <option value="">Tutti</option>
                            <option value="pending">In attesa</option>
                            <option value="confirmed">Approvata</option>
                            <option value="rejected">Rifiutata</option>
                        </select>
                    </div>
                    <div class="col-3 justify-content-start align-items-end d-flex">
                        <button type="button" class="btn btn-primary mt-4" id="filterButton">Filtra</button>
                    </div>
                </div>
            </form>
            <button id="createReviewBtn" class="btn btn-success mt-3">Crea recensione</button>
        </div>

        <div id="createReviewForm" class="col-12 col-md-6 d-none">
            <form action="{{ route('reviews.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="mb-3 col-12 col-md-3">
                        <label for="name" class="form-label">Nome</label>
                        <input type="text" class="form-control form_input_focused" id="name" name="name"
                            required>
                    </div>
                    <div class="mb-3 col-12 col-md-7">
                        <label for="title" class="form-label">Titolo</label>
                        <input type="text" class="form-control form_input_focused" id="title" name="title"
                            required>
                    </div>
                    <div class="mb-3 col-12 col-md-2">
                        <label for="rating" class="form-label">Valutazione</label>
                        <input type="number" class="form-control form_input_focused" id="rating" name="rating"
                            required min="1" max="5">
                    </div>
                    <div class="mb-3 col-12 col-md-12">
                        <label for="body" class="form-label">Recensione</label>
                        <textarea class="form-control form_input_focused" id="body" name="body" required></textarea>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Aggiungi Recensione</button>
            </form>
        </div>
    </div>


    <hr>
    @if (request()->header('User-Agent') && preg_match('/Mobile|Android|iPhone/i', request()->header('User-Agent')))
        {{-- Vista mobile: Card --}}
        <div class="overflow-y-auto border-bottom rounded" style="height: 55vh">
            @foreach ($reviews as $review)
                <div class="card mb-3 review-card" data-status="{{ $review->status }}">
                    <div class="card-body">
                        <p class="card-text"><strong>Nome:</strong> {{ $review->name }}</p>
                        <p class="card-text"><strong>Titolo:</strong> {{ $review->title }}</p>
                        <p class="card-text"><strong>Recensione:</strong> {{ $review->body }}</p>
                        <p class="card-text"><strong>Valutazione:</strong> {{ $review->rating }}</p>
                        <p class="card-text"><strong>Stato:</strong><x-status :status="$review->status" /></p>
                        <p class="card-text"><strong>Prenotazione:</strong>
                            {{ $review->booking ? true : 'Non ancora recensita' }}</p>
                        <p class="card-text"><strong>Data di aggiunta:</strong> {{ $review->created_at }}</p>
                        <p class="card-text"><strong>Data di modifica:</strong> {{ $review->updated_at }}</p>
                        <div class="d-flex justify-content-end">
                            <x-edit-button :id="'Review'" :data="$review" />
                            <x-delete-button :route="'reviews'" :model="$review" />
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Vista desktop: Tabella --}}
        <div class="table-responsive">
            <table class="table table-striped table-sm">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Titolo</th>
                        <th>Recensione</th>
                        <th>Valutazione</th>
                        <th>Stato</th>
                        <th>Prenotazione</th>
                        <th>Data di aggiunta</th>
                        <th>Data di modifica</th>
                        <th>Azione</th>
                    </tr>
                </thead>
                <tbody id="reviewsTableBody">
                    @foreach ($reviews as $review)
                        <tr class="review-row" data-status="{{ $review->status }}">
                            <td>{{ $review->id }}</td>
                            <td>{{ $review->name }}</td>
                            <td>{{ $review->title }}</td>
                            <td>{{ $review->body }}</td>
                            <td>{{ $review->rating }}</td>
                            <td><x-status :status="$review->status" /></td>
                            <td>{{ $review->booking ? true : 'Non ancora recensita' }}</td>
                            <td>{{ $review->created_at }}</td>
                            <td>{{ $review->updated_at }}</td>
                            <td>
                                <x-edit-button :id="'Review'" :data="$review" />
                                <x-delete-button :route="'reviews'" :model="$review" />
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <!-- Modale per Modifica Recensione -->
    <x-modal :id="'Review'" :title="'Modifica recensione'">
        <div class="mb-3">
            <label for="edit_name" class="form-label">Nome</label>
            <input type="text" class="form-control form_input_focused" id="edit_name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="edit_title" class="form-label">Titolo</label>
            <input type="text" class="form-control form_input_focused" id="edit_title" name="title" required>
        </div>
        <div class="mb-3">
            <label for="edit_body" class="form-label">Recensione</label>
            <textarea class="form-control form_input_focused" id="edit_body" name="body" required></textarea>
        </div>
        <div class="mb-3">
            <label for="edit_rating" class="form-label">Valutazione</label>
            <input type="number" class="form-control form_input_focused" id="edit_rating" name="rating" required
                min="1" max="5">
        </div>
        <div class="mb-3">
            <label for="edit_status" class="form-label">Stato</label>
            <select class="form-select" name="status" id="edit_status">
                <option value="pending">In attesa</option>
                <option value="confirmed">Approvata</option>
                <option value="rejected">Rifiutata</option>
            </select>
        </div>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var createReviewBtn = document.getElementById('createReviewBtn');
            var createReviewForm = document.getElementById('createReviewForm');

            createReviewBtn.addEventListener('click', function() {
                createReviewForm.classList.toggle('d-none');
                createReviewBtn.innerHTML = createReviewForm.classList.contains('d-none') ?
                    'Aggiungi Recensione' : 'Nascondi';
            });

            // Filtra le recensioni in base allo stato selezionato
            const filterButton = document.getElementById('filterButton');
            const statusFilter = document.getElementById('statusFilter');

            filterButton.addEventListener('click', function() {
                const selectedStatus = statusFilter.value;
                const reviewRows = document.querySelectorAll('.review-row');
                const reviewCards = document.querySelectorAll('.review-card');

                console.log(reviewCards, selectedStatus);
                reviewRows.forEach(function(row) {
                    const rowStatus = row.getAttribute('data-status');
                    row.style.display = (selectedStatus === "" || rowStatus === selectedStatus) ?
                        '' : 'none';
                });

                reviewCards.forEach(function(card) {
                    const cardStatus = card.getAttribute('data-status');
                    card.style.display = (selectedStatus === "" || cardStatus === selectedStatus) ?
                        '' : 'none';
                });
            });
        });
    </script>
</x-dashboard-layout>

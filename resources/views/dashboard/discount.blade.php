<x-dashboard-layout>
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Gestione Sconti</h2>
        </div>

        <button id="discountCreateBtn" class="btn btn-success">Crea sconto</button>

        {{-- Form per la creazione dello sconto --}}
        <form class="d-none" id="discountForm" method="POST" action="{{ route('discounts.store') }}">
            @csrf
            <input type="hidden" id="discountId" name="discount_id" value="">

            <div class="mb-3">
                <label for="name_it" class="form-label">Descrizione sconto IT</label>
                <input type="text" class="form-control" id="name_it" name="name_it" required>
                <label for="name_en" class="form-label">Descrizione sconto EN</label>
                <input type="text" class="form-control" id="name_en" name="name_en" required>
            </div>

            <div class="mb-3">
                <label for="percentage" class="form-label">Percentuale di Sconto</label>
                <input type="number" class="form-control" id="percentage" name="percentage" required>
            </div>

            <div class="mb-3">
                <label for="applicable_to" class="form-label">Applicabile a</label>
                <select class="form-control" id="applicable_to" name="applicable_to" required>
                    <option value="all">Tutti</option>
                    <option value="customers">Solo Clienti</option>
                </select>
            </div>

            <p>Categorie di Sconto</p>
            <div class="mb-3">
                <input class="form-check-input" type="checkbox" value="1" id="applies_to_transfer"
                    name="applies_to_transfer">
                <label for="applies_to_transfer" class="form-check-label">Transfer</label>

                <input class="form-check-input" type="checkbox" value="1" id="applies_to_rental"
                    name="applies_to_rental">
                <label for="applies_to_rental" class="form-check-label">Noleggio Auto</label>

                <input class="form-check-input" type="checkbox" value="1" id="applies_to_excursion"
                    name="applies_to_excursion">
                <label for="applies_to_excursion" class="form-check-label">Escursioni</label>
            </div>

            {{-- Sezione per aggiungere periodi di validità --}}
            <div class="mb-3">
                <label for="discount_periods" class="form-label">Periodi di Validità</label>
                <div id="discount_periods">
                    <div class="row mb-2 discount-period">
                        <div class="col-5">
                            <input type="datetime-local" class="form-control" name="periods[0][start]">
                        </div>
                        <div class="col-5">
                            <input type="datetime-local" class="form-control" name="periods[0][end]">
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-secondary" id="addPeriod"><i
                                    class="bi bi-plus-circle"></i></button>
                        </div>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" id="saveDiscount">Salva Sconto</button>
        </form>

        {{-- Tabella degli sconti --}}
        <table class="table table-bordered mt-4">
            <thead>
                <tr>
                    <th>Nome sconto</th>
                    <th>Percentuale</th>
                    <th>Applicabile a</th>
                    <th>Categorie di Sconto</th>
                    <th>Periodi di Sconto</th>
                    <th>Azioni</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($discounts as $discount)
                    <tr>
                        <td>{{ $discount->name_it }}</td>
                        <td>{{ $discount->percentage }}%</td>
                        <td>{{ $discount->applicable_to == 'all' ? 'Tutti' : 'Solo Clienti' }}</td>
                        <td>
                            <ul>
                                @if ($discount->applies_to_transfer)
                                    <li>Transfer</li>
                                @endif
                                @if ($discount->applies_to_rental)
                                    <li>Noleggio Auto</li>
                                @endif
                                @if ($discount->applies_to_excursion)
                                    <li>Escursioni</li>
                                @endif
                            </ul>
                        </td>
                        <td>
                            <ul>
                                @foreach ($discount->discount_periods as $period)
                                    <li>{{ \Carbon\Carbon::parse($period->start)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($period->end)->format('d/m/Y') }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning editDiscount" data-bs-target="#editDiscountModal"
                                data-id="{{ $discount->id }}" data-name_it="{{ $discount->name_it }}"
                                data-name_en="{{ $discount->name_en }}" data-percentage="{{ $discount->percentage }}"
                                data-applicable_to="{{ $discount->applicable_to }}"
                                data-transfer="{{ $discount->applies_to_transfer }}"
                                data-rental="{{ $discount->applies_to_rental }}"
                                data-excursion="{{ $discount->applies_to_excursion }}"
                                data-periods="{{ json_encode($discount->discount_periods) }}">
                                Modifica
                            </button>
                            <x-delete-button :route="'discounts.destroy'" :model="$discount" />
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{-- Modale per la modifica degli sconti --}}
        <div class="modal fade" id="editDiscountModal" tabindex="-1" role="dialog"
            aria-labelledby="editDiscountModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editDiscountModalLabel">Modifica Sconto</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Chiudi"></button>
                    </div>
                    <div class="modal-body">
                        {{-- Lo stesso form utilizzato per la creazione, ma precompilato con i dati --}}
                        <form id="editDiscountForm" method="POST" action="">
                            @csrf
                            @method('PUT')
                            <input type="hidden" id="editDiscountId" name="discount_id" value="">

                            <div class="mb-3">
                                <label for="edit_name_it" class="form-label">Nome Sconto IT</label>
                                <input type="text" class="form-control" id="edit_name_it" name="name_it"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_name_en" class="form-label">Nome Sconto EN</label>
                                <input type="text" class="form-control" id="edit_name_en" name="name_en"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_percentage" class="form-label">Percentuale di Sconto</label>
                                <input type="number" class="form-control" id="edit_percentage" name="percentage"
                                    required>
                            </div>

                            <div class="mb-3">
                                <label for="edit_applicable_to" class="form-label">Applicabile a</label>
                                <select class="form-control" id="edit_applicable_to" name="applicable_to" required>
                                    <option value="all">Tutti</option>
                                    <option value="customers">Solo Clienti</option>
                                </select>
                            </div>

                            <p>Categorie di sconto</p>
                            <div class="mb-3">
                                <input class="form-check-input" type="checkbox" value="1"
                                    id="edit_applies_to_transfer" name="applies_to_transfer">
                                <label for="edit_applies_to_transfer" class="form-check-label">Transfer</label>

                                <input class="form-check-input" type="checkbox" value="1"
                                    id="edit_applies_to_rental" name="applies_to_rental">
                                <label for="edit_applies_to_rental" class="form-check-label">Noleggio Auto</label>

                                <input class="form-check-input" type="checkbox" value="1"
                                    id="edit_applies_to_excursion" name="applies_to_excursion">
                                <label for="edit_applies_to_excursion" class="form-check-label">Escursioni</label>
                            </div>


                            {{-- Sezione per aggiungere periodi di validità --}}
                            <div class="mb-3">
                                <label for="edit_discount_periods" class="form-label">Periodi di Validità</label>
                                <div id="edit_discount_periods">
                                    {{-- I periodi verranno inseriti dinamicamente tramite JavaScript --}}
                                </div>
                                <button type="button" class="btn btn-secondary" id="edit_addPeriod">Aggiungi
                                    Periodo</button>
                            </div>

                            <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var discountCreateBtn = document.getElementById('discountCreateBtn');
            var discountFormCreate = document.getElementById('discountForm');
            discountCreateBtn.addEventListener('click', function() {
                discountFormCreate.classList.toggle('d-none');
                discountCreateBtn.innerHTML = discountFormCreate.classList.contains('d-none') ?
                    'Crea Sconto' : 'Nascondi';
            });
        });

        $(document).ready(function() {
            // Aggiungi un nuovo periodo di validità nella sezione di creazione
            $('#addPeriod').click(function() {
                let index = $('#discount_periods .discount-period').length;
                $('#discount_periods').append(`
                    <div class="row mb-2 discount-period">
                        <div class="col-5">
                            <input type="datetime-local" class="form-control" name="periods[${index}][start]" required>
                        </div>
                        <div class="col-5">
                            <input type="datetime-local" class="form-control" name="periods[${index}][end]" required>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger removePeriod"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                `);
            });

            // Rimuovi un periodo di validità
            $(document).on('click', '.removePeriod', function() {
                $(this).closest('.discount-period').remove();
            });

            // Gestisci il click sul pulsante Modifica
            $('.editDiscount').click(function() {
                let discountId = $(this).data('id');
                let name_it = $(this).data('name_it');
                let name_en = $(this).data('name_en');
                let percentage = $(this).data('percentage');
                let applicableTo = $(this).data('applicable_to');
                let appliesToTransfer = $(this).data('transfer');
                let appliesToRental = $(this).data('rental');
                let appliesToExcursion = $(this).data('excursion');
                let periods = $(this).data('periods');

                console.log(applicableTo, appliesToTransfer, appliesToRental, appliesToExcursion);

                $('#editDiscountId').val(discountId);
                $('#edit_name_it').val(name_it);
                $('#edit_name_en').val(name_en);
                $('#edit_percentage').val(percentage);
                $('#edit_applicable_to').val(applicableTo);
                $('#edit_applies_to_transfer').prop('checked', appliesToTransfer);
                $('#edit_applies_to_rental').prop('checked', appliesToRental);
                $('#edit_applies_to_excursion').prop('checked', appliesToExcursion);

                $('#edit_discount_periods').empty();
                periods.forEach((period, index) => {
                    $('#edit_discount_periods').append(`
                        <div class="row mb-2 discount-period">
                            <div class="col-5">
                                <input type="datetime-local" class="form-control" name="periods[${index}][start]" value="${period.start}" required>
                            </div>
                            <div class="col-5">
                                <input type="datetime-local" class="form-control" name="periods[${index}][end]" value="${period.end}" required>
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn btn-danger removePeriod"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    `);

                });

                $('#editDiscountForm').attr('action', `/dashboard/discounts/${discountId}`);
                $('#editDiscountModal').modal('show');
            });

            // Aggiungi un nuovo periodo di validità nella sezione di modifica
            $(document).on('click', '#edit_addPeriod', function() {
                let index = $('#edit_discount_periods .discount-period').length;
                $('#edit_discount_periods').append(`
                    <div class="row mb-2 discount-period">
                        <div class="col-5">
                            <input type="datetime-local" class="form-control" name="periods[${index}][start]" required>
                        </div>
                        <div class="col-5">
                            <input type="datetime-local" class="form-control" name="periods[${index}][end]" required>
                        </div>
                        <div class="col-2">
                            <button type="button" class="btn btn-danger removePeriod"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                `);
            });

            // Assicurati che i periodi di validità esistenti siano eliminabili
            $(document).on('click', '.removePeriod', function() {
                $(this).closest('.discount-period').remove();
            });

            // Chiudi il modale e ripristina l'azione del form
            $('#editDiscountModal').on('hidden.bs.modal', function() {
                $('#edit_discount_periods').empty();
            });
        });
    </script>


</x-dashboard-layout>

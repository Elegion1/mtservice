<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Gestione clienti</h1>

        <button id="customerCreateBtn" class="btn btn-success">Crea cliente</button>

        <form class="d-none" id="customerFormCreate" action="{{ route('customers.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="mb-3 col-lg-4 col-12">
                    <label for="name" class="form-label">Nome</label>
                    <input type="text" class="form-control form_input_focused" id="name" name="name"
                        required>
                </div>
                <div class="mb-3 col-lg-4 col-12">
                    <label for="surname" class="form-label">Cognome</label>
                    <input type="text" class="form-control form_input_focused" id="surname" name="surname"
                        required>
                </div>
                <div class="mb-3 col-lg-4 col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control form_input_focused" id="email" name="email"
                        required>
                </div>
                <div class="mb-3 col-lg-4 col-12">
                    <label for="phone" class="form-label">Telefono</label>
                    <input type="text" class="form-control form_input_focused" id="phone" name="phone"
                        required>
                </div>
                <div class="mb-3 col-lg-4 col-12">
                    <label for="discount" class="form-label">Sconto %</label>
                    <input type="number" class="form-control form_input_focused" id="discount" name="discount">
                </div>
                <div class="mb-3 col-lg-4 col-12">
                    <label for="body" class="form-label">Note</label>
                    <input type="text" class="form-control form_input_focused" id="body" name="body">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Aggiungi cliente</button>
        </form>

        <hr>
        <table class="table table-sm table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nome</th>
                    <th>Cognome</th>
                    <th>Email</th>
                    <th>Telefono</th>
                    <th>Note</th>
                    <th>Sconto %</th>
                    <th>Data di aggiunta</th>
                    <th>Azione</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($customers as $customer)
                    <tr>
                        <td>{{ $customer->id }}</td>
                        <td>{{ $customer->name }}</td>
                        <td>{{ $customer->surname }}</td>
                        <td><a href="mailto:{{ $customer->email }}">{{ $customer->email }}</a></td>
                        <td><a href="tel:{{ $customer->phone }}">{{ $customer->phone }}</a></td>
                        <td>{{ $customer->body }}</td>
                        <td>{{ $customer->discount }} %</td>
                        <td>{{ $customer->created_at }}</td>
                        <td>
                            <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                data-bs-target="#editCustomerModal" data-id="{{ $customer->id }}"
                                data-name="{{ $customer->name }}" data-surname="{{ $customer->surname }}"
                                data-email="{{ $customer->email }}" data-phone="{{ $customer->phone }}"
                                data-body="{{ $customer->body }}"
                                data-discount="{{ $customer->discount }}">Modifica</button>

                            <form action="{{ route('customers.destroy', $customer) }}" method="POST"
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

    <!-- Modale per Modifica Cliente -->
    <div class="modal fade" id="editCustomerModal" tabindex="-1" aria-labelledby="editCustomerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editCustomerModalLabel">Modifica Cliente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editCustomerForm" action="" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nome</label>
                            <input type="text" class="form-control form_input_focused" id="edit_name" name="name"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_surname" class="form-label">Cognome</label>
                            <input type="text" class="form-control form_input_focused" id="edit_surname"
                                name="surname" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_email" class="form-label">Email</label>
                            <input type="text" class="form-control form_input_focused" id="edit_email"
                                name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_phone" class="form-label">Telefono</label>
                            <input type="text" class="form-control form_input_focused" id="edit_phone"
                                name="phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="edit_discount" class="form-label">Sconto %</label>
                            <input type="number" class="form-control form_input_focused" id="edit_discount"
                                name="discount">
                        </div>
                        <div class="mb-3">
                            <label for="edit_body" class="form-label">Note</label>
                            <input type="text" class="form-control form_input_focused" id="edit_body"
                                name="body">
                        </div>
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var customerCreateBtn = document.getElementById('customerCreateBtn');
            var customerFormCreate = document.getElementById('customerFormCreate');
            customerCreateBtn.addEventListener('click', function() {
                customerFormCreate.classList.toggle('d-none');
                customerCreateBtn.innerHTML = customerFormCreate.classList.contains('d-none') ?
                    'Crea cliente' : 'Nascondi';
            });
            var editCustomerModal = document.getElementById('editCustomerModal');
            editCustomerModal.addEventListener('show.bs.modal', function(event) {
                var button = event.relatedTarget;
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');
                var surname = button.getAttribute('data-surname');
                var email = button.getAttribute('data-email');
                var phone = button.getAttribute('data-phone');
                var discount = button.getAttribute('data-discount');
                var body = button.getAttribute('data-body');

                var modal = this;
                modal.querySelector('#edit_name').value = name;
                modal.querySelector('#edit_surname').value = surname;
                modal.querySelector('#edit_email').value = email;
                modal.querySelector('#edit_phone').value = phone;
                modal.querySelector('#edit_discount').value = discount;
                modal.querySelector('#edit_body').value = body;


                var form = modal.querySelector('#editCustomerForm');
                form.action = '{{ url('dashboard/customers') }}/' + id;
            });
        });
    </script>
</x-dashboard-layout>

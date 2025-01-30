<x-dashboard-layout>

    <h1>Gestione clienti</h1>

    <button id="customerCreateBtn" class="btn btn-success">Crea cliente</button>

    <form class="d-none" id="customerFormCreate" action="{{ route('customers.store') }}" method="POST">
        @csrf
        <div class="row">
            <div class="mb-3 col-lg-4 col-12">
                <label for="name" class="form-label">Nome</label>
                <input type="text" class="form-control form_input_focused" id="name" name="name" required>
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="surname" class="form-label">Cognome</label>
                <input type="text" class="form-control form_input_focused" id="surname" name="surname" required>
            </div>
            <div class="mb-3 col-lg-4 col-12">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control form_input_focused" id="email" name="email" required>
            </div>
            <div class="mb-3 col-lg-4 col-12 d-flex">
                <div>
                    <label for="dial_code" class="form-label">Prefisso</label>
                    <input type="text" class="form-control form_input_focused" id="dial_code" name="dial_code"
                        required>
                </div>
                <div>
                    <label for="phone" class="form-label">Telefono</label>
                    <input type="text" class="form-control form_input_focused" id="phone" name="phone"
                        required>
                </div>
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
                    <td><a href="tel:{{ $customer->dial_code }}{{ $customer->phone }}">{{ $customer->dial_code }}
                            {{ $customer->phone }}</a>
                    </td>
                    <td>{{ $customer->body }}</td>
                    <td>{{ $customer->discount }} %</td>
                    <td>{{ $customer->created_at }}</td>
                    <td>
                        <x-edit-button :id="'Customer'" :data="$customer" />
                        <x-delete-button :route="'customers'" :model="$customer" />
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>



    <!-- Modale per Modifica Cliente -->
    <x-modal :id="'Customer'" :title="'Modifica cliente'">
        <div class="mb-3">
            <label for="edit_name" class="form-label">Nome</label>
            <input type="text" class="form-control form_input_focused" id="edit_name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="edit_surname" class="form-label">Cognome</label>
            <input type="text" class="form-control form_input_focused" id="edit_surname" name="surname" required>
        </div>
        <div class="mb-3">
            <label for="edit_email" class="form-label">Email</label>
            <input type="text" class="form-control form_input_focused" id="edit_email" name="email" required>
        </div>
        <div class="mb-3 d-flex">
            <div>
                <label for="edit_dial_code" class="form-label">Prefisso</label>
                <input type="text" class="form-control form_input_focused" id="edit_dial_code" name="dial_code"
                    required>
            </div>
            <div>
                <label for="edit_phone" class="form-label">Telefono</label>
                <input type="text" class="form-control form_input_focused" id="edit_phone" name="phone" required>
            </div>
        </div>
        <div class="mb-3">
            <label for="edit_discount" class="form-label">Sconto %</label>
            <input type="number" class="form-control form_input_focused" id="edit_discount" name="discount">
        </div>
        <div class="mb-3">
            <label for="edit_body" class="form-label">Note</label>
            <input type="text" class="form-control form_input_focused" id="edit_body" name="body">
        </div>
    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var customerCreateBtn = document.getElementById('customerCreateBtn');
            var customerFormCreate = document.getElementById('customerFormCreate');
            customerCreateBtn.addEventListener('click', function() {
                customerFormCreate.classList.toggle('d-none');
                customerCreateBtn.innerHTML = customerFormCreate.classList.contains('d-none') ?
                    'Crea cliente' : 'Nascondi';
            });

        });
    </script>
</x-dashboard-layout>

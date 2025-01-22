<x-dashboard-layout>
    <div class="container-fluid">
        <h2>Utenti</h2>
        <button id="createUserBtn" class="btn btn-primary">Crea utente</button>

        <form id="createUserForm" class="d-none" id="createUserForm" method="POST" action="{{ route('users.store') }}">
            @csrf
            @method('POST')
            <div class="row">
                <div class="mb-3 col-12 col-md-4">
                    <label for="userName" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="userName" name="name">
                </div>
                <div class="mb-3 col-12 col-md-4">
                    <label for="selectRole" class="form-label">Ruolo</label>
                    <select class="form-select" aria-label="Default select example" id="selectRole" name="role">
                        <option selected>Seleziona un ruolo</option>
                        <option value="admin">Admin</option>
                        <option value="user">User</option>
                    </select>
                </div>
                <div class="mb-3 col-12 col-md-4">
                    <label for="userEmail" class="form-label">Email</label>
                    <input type="email" class="form-control" id="userEmail" name="email">
                </div>
                <div class="mb-3 col-12 col-md-4">
                    <label for="userPassword" class="form-label">Password</label>
                    <input type="password" class="form-control" id="userPassword" name="password">
                </div>
                <div class="mb-3 col-12 col-md-4">
                    <label for="userPasswordConfirmation" class="form-label">Conferma password</label>
                    <input type="password" class="form-control" id="userPasswordConfirmation"
                        name="password_confirmation">
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Crea utente</button>
        </form>


        <table class="table table-sm table-striped mt-5">
            <tr>
                <th>ID</th>
                <th>Nome</th>
                <th>Ruolo</th>
                <th>Email</th>
                <th>Password</th>
                <th>Azioni</th>
            </tr>
            @foreach ($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->role ?? 'n/a' }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if ($user->password)
                            Presente
                        @else
                            Non presente
                        @endif
                    </td>
                    <td>
                        <x-edit-button :id="'User'" :data="$user" />
                        <x-delete-button :route="'users.destroy'" :model="$user" />
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Modal Structure -->

    <x-modal :id="'User'" :title="'Modifica utente'">
        <div class="mb-3">
            <label for="edit_name" class="form-label">Nome</label>
            <input type="text" class="form-control" id="edit_name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="edit_role" class="form-label">Ruolo</label>
            <select class="form-select" id="edit_role" name="role" required>
                <option value="admin">Admin</option>
                <option value="user">User</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="edit_email" class="form-label">Email</label>
            <input type="email" class="form-control" id="edit_email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="edit_password" class="form-label">Password</label>
            <input type="password" class="form-control" id="edit_password" name="password">
            <small>Lascia vuoto se non vuoi cambiare la password.</small>
        </div>
        <div class="mb-3">
            <label for="edit_password_confirmation" class="form-label">Conferma password</label>
            <input type="password" class="form-control" id="edit_password_confirmation" name="password_confirmation">
        </div>

    </x-modal>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var createUserBtn = document.getElementById('createUserBtn');
            var createUserForm = document.getElementById('createUserForm');
            createUserBtn.addEventListener('click', function() {
                createUserForm.classList.toggle('d-none');
                createUserBtn.innerText = createUserForm.classList.contains('d-none') ? 'Crea utente' :
                    'Nascondi';
            });

        });
    </script>

</x-dashboard-layout>

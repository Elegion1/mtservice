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
                        <!-- Edit Button -->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#editUserModal"
                            data-id="{{ $user->id }}" data-name="{{ $user->name }}"
                            data-email="{{ $user->email }}" data-role="{{ $user->role }}">
                            Modifica
                        </button>
                        <!-- Delete Form -->
                        <form action="{{ route('users.destroy', $user) }}" method="POST" style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" type="submit">Elimina</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>
    </div>

    <!-- Modal Structure -->
    <div class="modal fade" id="editUserModal" tabindex="-1" aria-labelledby="editUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editUserModalLabel">Modifica Utente</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editUserForm" method="POST" action="">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="userName" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="userName" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="selectRole" class="form-label">Ruolo</label>
                            <select class="form-select" id="selectRole" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="user">User</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="userEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="userEmail" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="userPassword" class="form-label">Password</label>
                            <input type="password" class="form-control" id="userPassword" name="password">
                            <small>Lascia vuoto se non vuoi cambiare la password.</small>
                        </div>
                        <div class="mb-3">
                            <label for="userPasswordConfirmation" class="form-label">Conferma password</label>
                            <input type="password" class="form-control" id="userPasswordConfirmation"
                                name="password_confirmation">
                        </div>

                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var createUserBtn = document.getElementById('createUserBtn');
            var createUserForm = document.getElementById('createUserForm');
            createUserBtn.addEventListener('click', function() {
                createUserForm.classList.toggle('d-none');
                createUserBtn.innerText = createUserForm.classList.contains('d-none') ? 'Crea utente' :
                    'Nascondi';
            });

            var editUserModal = document.getElementById('editUserModal');

            editUserModal.addEventListener('show.bs.modal', function(event) {
                // Button that triggered the modal
                var button = event.relatedTarget;

                // Extract info from data-* attributes
                var userId = button.getAttribute('data-id');
                var userName = button.getAttribute('data-name');
                var userEmail = button.getAttribute('data-email');
                var userRole = button.getAttribute('data-role');

                // Update the modal's content
                var modalTitle = editUserModal.querySelector('.modal-title');
                var inputName = editUserModal.querySelector('#userName');
                var inputEmail = editUserModal.querySelector('#userEmail');
                var inputRole = editUserModal.querySelector('#selectRole');
                var form = editUserModal.querySelector('#editUserForm');

                // Set the modal title
                modalTitle.textContent = 'Modifica Utente: ' + userName;

                // Set the form fields with the current user data
                inputName.value = userName;
                inputEmail.value = userEmail;
                inputRole.value = userRole;

                // Update the form action URL dynamically
                form.action = '/dashboard/users/' + userId;
            });
        });
    </script>

</x-dashboard-layout>

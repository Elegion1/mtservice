<x-dashboard-layout>
    <div class="container m-5 p-5">
        <h1>Accedi alla dashboard</h1>
        <x-display-error />
        <x-display-message />
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="row">
                <div class="mb-3 col-12">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" class="form-control form_input_focused" id="email"
                        aria-describedby="emailHelp">
                </div>
                <div class="mb-3 col-12">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control form_input_focused" id="password">
                </div>
                <div class="d-flex justify-content-center align-items-center col-12">
                    <button type="submit" class="btn btn-primary">Login</button>
                </div>
            </div>

        </form>
    </div>
</x-dashboard-layout>

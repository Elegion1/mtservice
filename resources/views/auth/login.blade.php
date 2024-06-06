<x-dashboard-layout>
    <div class="container m-5 p-5">
        <h1>Accedi alla dashboard</h1>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" name="email" class="form-control" id="email"
                    aria-describedby="emailHelp">
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password">
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</x-dashboard-layout>
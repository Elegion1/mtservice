<x-dashboard-layout>
    <div class="d-flex justify-content-center align-items-center vh-100 vw-100">
        <div class="container p-1 m-3 border bg-secondary-subtle rounded shadow" style="width:50%; min-width:350px">
            <div class="d-flex justify-content-center align-items-center p-3">
                <a class="text-decoration-none" href="{{route('home')}}" title="Torna indietro"><i class="bi bi-arrow-left-circle"></i></a>
                <p class="text-a mb-0 mx-auto text-uppercase">Accedi alla dashboard</p>
                <i style="width: 16px; height:16px"></i>
            </div>
            <x-display-error />
            <x-display-message />
            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="row p-3">
                    <div class="mb-3 col-12">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" class="form-control form_input_focused" id="email"
                            aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3 col-12">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" name="password" class="form-control form_input_focused" id="password" required>
                    </div>
                    <div class="d-flex justify-content-center align-items-center col-12">
                        <button type="submit" class="btn btn-primary">Accedi</button>
                    </div>
                </div>
                
            </form>
           
        </div>
    </div>
</x-dashboard-layout>

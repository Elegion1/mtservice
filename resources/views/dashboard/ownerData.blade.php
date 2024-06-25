<x-dashboard-layout>
    <div class="container-fluid mt-5">
        <h1>Dati Azienda</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="container">
            @if ($ownerData)
                <form method="POST" action="{{ route('owner.update', ['ownerData' => $ownerData->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="name" class="form-label">Nome:</label>
                                <input type="text" id="name" name="name"
                                    value="{{ old('name', $ownerData->name) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="surname" class="form-label">Cognome:</label>
                                <input type="text" id="surname" name="surname"
                                    value="{{ old('surname', $ownerData->surname) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="companyName" class="form-label">Azienda:</label>
                                <input type="text" id="companyName" name="companyName"
                                    value="{{ old('companyName', $ownerData->companyName) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="address" class="form-label">Indirizzo:</label>
                                <input type="text" id="address" name="address"
                                    value="{{ old('address', $ownerData->address) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="city" class="form-label">Città:</label>
                                <input type="text" id="city" name="city"
                                    value="{{ old('city', $ownerData->city) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="pIva" class="form-label">P.IVA:</label>
                                <input type="text" id="pIva" name="pIva"
                                    value="{{ old('pIva', $ownerData->pIva) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="codFisc" class="form-label">Codice Fiscale:</label>
                                <input type="text" id="codFisc" name="codFisc"
                                    value="{{ old('codFisc', $ownerData->codFisc) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Telefono 1:</label>
                                <input type="text" id="phone" name="phone"
                                    value="{{ old('phone', $ownerData->phone) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="phone2" class="form-label">Telefono 2:</label>
                                <input type="text" id="phone2" name="phone2"
                                    value="{{ old('phone2', $ownerData->phone2) }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="mb-3">
                                <label for="phone3" class="form-label">Telefono 3:</label>
                                <input type="text" id="phone3" name="phone3"
                                    value="{{ old('phone3', $ownerData->phone3) }}" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email:</label>
                                <input type="text" id="email" name="email"
                                    value="{{ old('email', $ownerData->email) }}" class="form-control">
                            </div>
                        </div>

                        <div class="col-8 border rounded">
                            @if ($ownerData->images->count() > 0)
                                <p>Logo</p>
                                {{-- @dd($ownerData->images) --}}
                                @foreach ($ownerData->images as $image)
                                    <div id="image-{{ $image->id }}">
                                        <p>Immagine {{ $image->id }}</p>
                                        <img width="200px" src="{{ Storage::url($image->path) }}" alt="LOGO">
                                        <button type="button" class="btn btn-danger btn-sm"
                                            onclick="deleteImage({{ $image->id }})">Elimina</button>
                                    </div>
                                @endforeach
                            @else
                                <p>Nessuna immagine disponibile</p>
                            @endif
                            <input type="file" class="form-control" id="images" name="images[]" multiple>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-primary">Salva modifiche</button>
                </form>
            @endif
        </div>
    </div>

    <script>
        function deleteImage(imageId) {
            if (confirm('Sei sicuro di voler eliminare questa immagine?')) {
                fetch(`/dashboard/images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`image-${imageId}`).remove();
                        } else {
                            alert('Errore durante l\'eliminazione dell\'immagine.');
                        }
                    })
                    .catch(error => console.error('Errore:', error));
            }
        }
    </script>
</x-dashboard-layout>

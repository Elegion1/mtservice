<x-dashboard-layout>

    <h1 class="mb-4">Dati Azienda</h1>

    <div class="container">
        @if ($ownerData)
            <form method="POST" action="{{ route('owner.update', ['ownerData' => $ownerData->id]) }}"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- Informazioni azienda --}}
                <div class="row g-3">
                    {{-- Nome --}}
                    <div class="col-lg-3 col-md-6 col-12">
                        <label for="name" class="form-label">Nome:</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $ownerData->name) }}"
                            class="form-control">
                    </div>

                    {{-- Cognome --}}
                    <div class="col-lg-3 col-md-6 col-12">
                        <label for="surname" class="form-label">Cognome:</label>
                        <input type="text" id="surname" name="surname"
                            value="{{ old('surname', $ownerData->surname) }}" class="form-control">
                    </div>

                    {{-- Azienda --}}
                    <div class="col-lg-3 col-md-6 col-12">
                        <label for="companyName" class="form-label">Azienda:</label>
                        <input type="text" id="companyName" name="companyName"
                            value="{{ old('companyName', $ownerData->companyName) }}" class="form-control">
                    </div>

                    {{-- Nome sito --}}
                    <div class="col-lg-3 col-md-6 col-12">
                        <label for="siteName" class="form-label">Nome sito:</label>
                        <input type="text" id="siteName" name="siteName"
                            value="{{ old('siteName', $ownerData->siteName) }}" class="form-control">
                    </div>
                </div>

                <div class="row g-3 mt-3">
                    {{-- Città --}}
                    <div class="col-lg-3 col-md-6 col-12">
                        <label for="city" class="form-label">Città:</label>
                        <input type="text" id="city" name="city" value="{{ old('city', $ownerData->city) }}"
                            class="form-control">
                    </div>

                    {{-- Indirizzo --}}
                    <div class="col-lg-3 col-12">
                        <label for="address" class="form-label">Indirizzo:</label>
                        <input type="text" id="address" name="address"
                            value="{{ old('address', $ownerData->address) }}" class="form-control">
                    </div>

                    {{-- Dati fiscali --}}

                    <div class="col-lg-3 col-md-6 col-12">
                        <label for="pIva" class="form-label">P.IVA:</label>
                        <input type="text" id="pIva" name="pIva" value="{{ old('pIva', $ownerData->pIva) }}"
                            class="form-control">
                    </div>
                    <div class="col-lg-3 col-md-6 col-12">
                        <label for="codFisc" class="form-label">Codice Fiscale:</label>
                        <input type="text" id="codFisc" name="codFisc"
                            value="{{ old('codFisc', $ownerData->codFisc) }}" class="form-control">
                    </div>
                </div>

                {{-- Contatti --}}
                <div class="row g-3 mt-3">
                    {{-- Telefono 1 --}}
                    <div class="col-lg-4 col-md-6 col-12">
                        <label for="phoneName" class="form-label">Nome Telefono 1:</label>
                        <input type="text" id="phoneName" name="phoneName"
                            value="{{ old('phoneName', $ownerData->phoneName) }}" class="form-control">
                        <label for="phone" class="form-label mt-2">Telefono 1:</label>
                        <input type="text" id="phone" name="phone"
                            value="{{ old('phone', $ownerData->phone) }}" class="form-control">
                    </div>

                    {{-- Telefono 2 --}}
                    <div class="col-lg-4 col-md-6 col-12">
                        <label for="phone2Name" class="form-label">Nome Telefono 2:</label>
                        <input type="text" id="phone2Name" name="phone2Name"
                            value="{{ old('phone2Name', $ownerData->phone2Name) }}" class="form-control">
                        <label for="phone2" class="form-label mt-2">Telefono 2:</label>
                        <input type="text" id="phone2" name="phone2"
                            value="{{ old('phone2', $ownerData->phone2) }}" class="form-control">
                    </div>

                    {{-- Telefono 3 --}}
                    <div class="col-lg-4 col-md-6 col-12">
                        <label for="phone3Name" class="form-label">Nome Telefono 3:</label>
                        <input type="text" id="phone3Name" name="phone3Name"
                            value="{{ old('phone3Name', $ownerData->phone3Name) }}" class="form-control">
                        <label for="phone3" class="form-label mt-2">Telefono 3:</label>
                        <input type="text" id="phone3" name="phone3"
                            value="{{ old('phone3', $ownerData->phone3) }}" class="form-control">
                    </div>
                </div>

                {{-- Email e WhatsApp --}}
                <div class="row g-3 mt-3">
                    <div class="col-lg-4 col-md-6 col-12">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" id="email" name="email"
                            value="{{ old('email', $ownerData->email) }}" class="form-control">
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <label for="whatsappLink" class="form-label">Link WhatsApp:</label>
                        <input type="url" id="whatsappLink" name="whatsappLink"
                            value="{{ old('whatsappLink', $ownerData->whatsappLink) }}" class="form-control">
                    </div>
                    <div class="col-lg-4 col-md-6 col-12">
                        <label for="facebook" class="form-label">Facebook:</label>
                        <input type="url" id="facebook" name="facebook"
                            value="{{ old('facebook', $ownerData->facebook) }}" class="form-control">
                    </div>
                </div>

                {{-- Gestione Immagini --}}
                <div class="row g-3 mt-3">
                    <div class="col-lg-12">
                        <p class="fw-bold">Logo</p>
                        @if ($ownerData->images->count() > 0)
                            @foreach ($ownerData->images as $image)
                                <div id="image-{{ $image->id }}" class="d-inline-block me-3">
                                    <img src="{{ Storage::url($image->path) }}" alt="Logo"
                                        class="img-thumbnail mb-2" style="width: 200px;">
                                    <button type="button" class="btn btn-danger btn-sm"
                                        onclick="deleteImage({{ $image->id }})">Elimina</button>
                                </div>
                            @endforeach
                        @else
                            <p class="text-muted">Nessuna immagine disponibile</p>
                        @endif
                        <input type="file" id="images" name="images[]" multiple class="form-control mt-3">
                    </div>
                </div>

                {{-- Pulsanti --}}
                <div class="row g-3 mt-4">
                    <div class="col-lg-12 text-end">
                        <button type="submit" class="btn btn-primary">Salva Modifiche</button>
                    </div>
                </div>
            </form>
        @endif
    </div>


    {{-- Script per eliminazione immagini --}}
    <script>
        function deleteImage(imageId) {
            if (confirm('Sei sicuro di voler eliminare questa immagine?')) {
                fetch(`/dashboard/images/${imageId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
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

@if ($errors->any())
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex  justify-content-center">

                <div
                    class="alert my_alert-danger d-flex align-items-center justify-content-center flex-column mt-5 align-middle">

                    @foreach ($errors->all() as $error)
                        <p class="px-2 m-0"><i class="bi bi-exclamation-triangle-fill px-2"></i>{{ $error }}</p>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endif

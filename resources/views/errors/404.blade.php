<x-layout>
    <div class="container my-5 py-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="text-center text-dark rounded p-5 shadow">
                    <div class="h6 text-uppercase">{{ __('ui.notFound') }}</div>

                    <div class="">
                        <p class="fs-5">{{ __('ui.notFoundMsg') }}</p>
                        <a href="{{ route('home') }}" class="btn bg-a text-white">{{ __('ui.goHome') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

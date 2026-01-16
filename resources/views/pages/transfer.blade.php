<x-layout>
    <x-seo-data :seoTitle="$seoTitle" :seoDescription="$seoDescription" />
    <div class="row">
        <div class="col-12 col-xl-6">
            <div class="container p-3">
                <x-show-content :pagine="$pagine" />
            </div>
        </div>
        <div class="col-12 col-xl-6">
            <div class="container my-3 ">
                <x-contact-link />
            </div>
            <h2 class="text-center">{{ __('ui.title2') }}</h2>
            <x-services />
        </div>
        <div class="col-12 mt-5">
            <h2 class="text-center mb-3">{{ __('ui.title3') }}</h2>
            <x-excursions />
        </div>
    </div>
</x-layout>

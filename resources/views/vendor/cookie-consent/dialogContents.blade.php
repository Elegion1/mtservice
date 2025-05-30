<div class="js-cookie-consent cookie-consent fixed-bottom bg-a py-2">
    <div class="container">
        <div class="alert alert-primary d-flex align-items-center justify-content-between rounded">
            <div class="flex-grow-1">
                <p class="m-0 text-dark cookie-consent__message">
                    {!! trans('cookie-consent::texts.message') !!}
                </p>
            </div>
            <div class="mt-2 mt-sm-0">
                <button class="js-cookie-consent-agree btn bg-d text-white cookie-consent__agree">
                    {{ trans('cookie-consent::texts.agree') }}
                </button>
            </div>
        </div>
    </div>
</div>

{{-- <div class="js-cookie-consent cookie-consent fixed bottom-0 inset-x-0 pb-2 z-50">
    <div class="max-w-7xl mx-auto px-6">
        <div class="p-4 md:p-2 rounded-lg bg-yellow-100">
            <div class="flex items-center justify-between flex-wrap">
                <div class="max-w-full flex-1 items-center md:w-0 md:inline">
                    <p class="md:ml-3 text-black cookie-consent__message">
                        {!! trans('cookie-consent::texts.message') !!}
                    </p>
                </div>
                <div class="mt-2 flex-shrink-0 w-full sm:mt-0 sm:w-auto">
                    <button class="js-cookie-consent-agree cookie-consent__agree cursor-pointer flex items-center justify-center px-4 py-2 rounded-md text-sm font-medium text-yellow-800 bg-yellow-400 hover:bg-yellow-300">
                        {{ trans('cookie-consent::texts.agree') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div> --}}
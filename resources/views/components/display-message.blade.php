@if (session('message'))
    <div id="display-info" class="container">
        <div class="row justify-content-center">
            <div class="col-12 d-flex justify-content-center">
                <div id="flash-message"
                    class="alert my-alert-success d-flex align-items-center justify-content-center mt-5 align-middle">
                    <p class="px-2 m-0">{{ session('message') }}</p>
                </div>

            </div>
        </div>
    </div>
@endif


<script>
    let message = document.querySelector('#flash-message')
    setTimeout(() => {
        if (message) {
            message.remove();
        }
    }, 5000);
</script>

<div class="whatsapp">
    <div class="contact-menu">
        <button aria-label="Parla con noi" class="contact-btn shadow">
            <i class="bi bi-chat-text"></i>
        </button>
        <div class="contact-options">
            @if ($ownerdata->phone2)
                <a href="tel:{{ $ownerdata->phone2 }}" class="contact-option call2 shadow text-black" aria-label="Chiama il numero {{ $ownerdata->phone2 }}">
                    <i class="bi bi-telephone"></i>
                </a>
            @endif
            {{-- @if ($ownerdata->phone3)
                <a href="tel:{{ $ownerdata->phone3 }}" class="contact-option call2 shadow text-black">
                    <i class="bi bi-telephone"></i>
                </a>
            @endif --}}
            {{-- <a href="mailto:{{$ownerdata->email}}" class="contact-option email shadow">
                <i class="bi bi-envelope"></i>
            </a> --}}
            @if ($ownerdata->whatsappLink)
                <a href="{{ $ownerdata->whatsappLink }}" class="contact-option whatsappBtn shadow text-d" aria-label="Chatta su WhatsApp">
                    <i class="bi bi-whatsapp"></i>
                </a>
            @endif
        </div>
    </div>
</div>

<script>
    document.querySelector('.contact-btn').addEventListener('click', function() {
        const options = document.querySelector('.contact-options');
        options.style.display = options.style.display === 'block' ? 'none' : 'block';
        options.classList.toggle('show');
    });
</script>

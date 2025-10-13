<div>
    <img src="{{ $src($size ?? 'default') }}"
        @if ($srcset()) srcset="{{ $srcset($size ?? 'default') }}" 
        sizes="(max-width: 600px) 400px, (max-width: 1200px) 800px, 1600px" @endif
        alt="{{ $alt }}" {{ $attributes }} />
</div>

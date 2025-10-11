<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class ResponsiveImage extends Component
{
    public string $image;

    public string $alt;

    public array $sizes;

    public function __construct(string $image, string $alt = '', array $sizes = [400, 800, 1600])
    {
        $this->image = $image;
        $this->alt = $alt ?: pathinfo($image, PATHINFO_FILENAME);
        $this->sizes = $sizes;
    }

    public function src(): string
    {
        $filename = pathinfo($this->image, PATHINFO_FILENAME);
        $base = 'storage/images/resized';
        $size = in_array(800, $this->sizes) ? 800 : $this->sizes[0];
        $ext = file_exists(public_path("$base/$size/$filename.webp")) ? 'webp' : 'jpg';

        // Se il file ridimensionato non esiste, usa l'originale
        if (! file_exists(public_path("$base/$size/$filename.$ext"))) {
            return Storage::url($this->image); // DB già senza 'storage/'
        }

        return asset("$base/$size/$filename.$ext"); // versione ridimensionata
    }

    public function srcset(): ?string
    {
        $filename = pathinfo($this->image, PATHINFO_FILENAME);
        $base = 'storage/images/resized';

        $srcset = [];
        foreach ($this->sizes as $size) {
            $ext = file_exists(public_path("$base/$size/$filename.webp")) ? 'webp' : 'jpg';
            if (file_exists(public_path("$base/$size/$filename.$ext"))) {
                $srcset[] = asset("$base/$size/$filename.$ext")." {$size}w";
            }
        }

        return ! empty($srcset) ? implode(', ', $srcset) : null;
    }

    public function render()
    {
        return view('components.responsive-image');
    }
}

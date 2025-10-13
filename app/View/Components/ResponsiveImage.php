<?php

namespace App\View\Components;

use Illuminate\Support\Facades\Storage;
use Illuminate\View\Component;

class ResponsiveImage extends Component
{
    public string $image;

    public string $alt;

    public array $sizes;

    public ?string $size; // nuovo parametro opzionale

    public function __construct(string $image, string $alt = '', array $sizes = [400, 800, 1600], ?string $size = null)
    {
        $this->image = $image;
        $this->alt = $alt ?: pathinfo($image, PATHINFO_FILENAME);
        $this->sizes = $sizes;
        $this->size = $size;
    }

    public function src(): string
    {
        $filename = pathinfo($this->image, PATHINFO_FILENAME);
        $base = 'storage/images/resized';

        // se è stata scelta una dimensione specifica
        $size = null;
        if ($this->size) {
            $map = ['small' => 400, 'medium' => 800, 'large' => 1600];
            $size = $map[$this->size] ?? $this->sizes[0];
        } else {
            $size = in_array(800, $this->sizes) ? 800 : $this->sizes[0];
        }

        $ext = file_exists(public_path("$base/$size/$filename.webp")) ? 'webp' : 'jpg';

        // Se il file ridimensionato non esiste, usa l'originale
        if (! file_exists(public_path("$base/$size/$filename.$ext"))) {
            return Storage::url($this->image);
        }

        return asset("$base/$size/$filename.$ext");
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

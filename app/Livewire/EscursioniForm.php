<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Excursion;

class EscursioniForm extends Component
{
    public function render()
    {   
        $excursions = Excursion::all();
        return view('livewire.escursioni-form', compact('excursions'));
    }
}

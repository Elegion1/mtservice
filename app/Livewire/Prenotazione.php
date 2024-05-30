<?php

namespace App\Livewire;

use Livewire\Component;

class Prenotazione extends Component
{
    public $currentForm = 'escursioni';

    public function showEscursioni()
    {
        $this->currentForm = 'escursioni';
    }

    public function showTransfer()
    {
        $this->currentForm = 'transfer';
    }

    public function render()
    {
        return view('livewire.prenotazione');
    }
}

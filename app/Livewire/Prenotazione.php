<?php

namespace App\Livewire;

use Livewire\Component;

class Prenotazione extends Component
{
    // public $showFormTransfer = true;
    // public $showFormEscursione = false;

    // public function showFormTransfer()
    // {
    //     $this->showFormTransfer = true;
    //     $this->showFormEscursione = false;
    // }

    // public function showFormEscursione()
    // {
    //     $this->showFormTransfer = false;
    //     $this->showFormEscursione = true;
    // }
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
        return view('livewire.prenotazione', compact($buttoncolor = 'buttoncolor'));
    }
}

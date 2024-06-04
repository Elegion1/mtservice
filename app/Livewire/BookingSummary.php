<?php

namespace App\Livewire;

use Livewire\Component;

class BookingSummary extends Component
{
    public $bookingData;

    public function mount($bookingData)
    {
        
        $this->bookingData = $bookingData;
    }

    public function render()
    {
        return view('livewire.booking-summary', [
            'bookingData' => $this->bookingData,
        ]);
    }
}

<?php

namespace App\Traits;

trait HasSteps
{
    /**
     * Gestisce il cambio di step e notifica il genitore.
     */
    public function goToStep($step)
    {
        // Verifica se la proprietà esiste nel componente
        if (property_exists($this, 'currentStep')) {
            $this->currentStep = $step;

            // Lancia l'evento verso il genitore
            $this->dispatch('stepChanged', step: $step);
        }
    }
}

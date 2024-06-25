<?php

namespace Database\Seeders;

use App\Models\OwnerData;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OwnerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OwnerData::create([
            'name' => 'Antonino',
            'surname' => 'Tranchida',
            'companyName' => 'M.T. Service',
            'address' => 'Via F.lli di Falco, 40',
            'city' => '91027 Paceco (TP)',
            'pIva' => '02644360816',
            'codFisc' => 'TRN NNN 73 R04 G208I',
            'phone' => '+39 3283650762', // Inserisci il numero di telefono se disponibile
            'phone2' => '+39 3931181111', // Inserisci il secondo numero di telefono se necessario
            'phone3' => '+39 3773911945', // Inserisci il terzo numero di telefono se necessario
            'email' => 'mtservice@mail.com'
        ]);
    }
}

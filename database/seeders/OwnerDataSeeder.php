<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\OwnerData;
use Illuminate\Database\Seeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class OwnerDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ownerData = OwnerData::create([
            'name' => 'Antonino',
            'surname' => 'Tranchida',
            'companyName' => 'M.T. Service',
            'address' => 'Via F.lli di Falco, 40',
            'city' => '91027 Paceco (TP)',
            'pIva' => '02644360816',
            'codFisc' => 'TRN NNN 73 R04 G208I',
            'phoneName' => 'Maurizio', 
            'phone2Name' => 'Giuseppe',
            'phone3Name' => 'Maurizio',
            'phone' => '+39 3283650762', // Inserisci il numero di telefono se disponibile
            'phone2' => '+39 3931181111', // Inserisci il secondo numero di telefono se necessario
            'phone3' => '+39 3773911945', // Inserisci il terzo numero di telefono se necessario
            'email' => 'mtservice@mail.com'
        ]);

        $imagePaths = [
            'public/media/logo.png',
        ];

        foreach ($imagePaths as $imagePath) {
            // Verifica se il file esiste nel percorso specificato
            if (File::exists($imagePath)) {
                // Ottieni il nome del file originale
                $filename = pathinfo($imagePath, PATHINFO_FILENAME);

                // Ottieni l'estensione del file originale
                $extension = File::extension($imagePath);

                // Prepara il percorso di destinazione nella directory storage/public/images
                $destinationPath = 'images/' . $filename . '.' . $extension;

                // Copia il file nella directory di storage pubblica
                Storage::disk('public')->put($destinationPath, File::get($imagePath));

                // Crea il record Image associato a OwnerData
                Image::create([
                    'path' => $destinationPath,
                    'owner_data_id' => $ownerData->id,
                ]);
            }
        }

        
    }
}

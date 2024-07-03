<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cars = [
            [
                'name' => 'Fiat Panda',
                'description' => '1.0 Hybrid 70cv',
                'price' => '100',
                'imagePath' => 'public/media/cars/panda.png'
            ],
            [
                'name' => 'Volkswagen Up',
                'description' => '1.0 60cv',
                'price' => '100',
                'imagePath' => 'public/media/cars/up.png'
            ],
            [
                'name' => 'Toyota Aygo',
                'description' => '1.0 68cv',
                'price' => '100',
                'imagePath' => 'public/media/cars/aygo.png'
            ]
        ];

        foreach ($cars as $car) {
            $newCar = Car::create([
                'name' => $car['name'],
                'description' => $car['description'],
                'price' => $car['price'],
            ]);

            // Verifica se il file esiste nel percorso specificato
            if (File::exists($car['imagePath'])) {
                // Ottieni il nome del file originale
                $filename = pathinfo($car['imagePath'], PATHINFO_FILENAME);

                // Ottieni l'estensione del file originale
                $extension = File::extension($car['imagePath']);

                // Prepara il percorso di destinazione nella directory storage/public/images
                $destinationPath = 'images/' . $filename . '.' . $extension;

                // Copia il file nella directory di storage pubblica
                Storage::disk('public')->put($destinationPath, File::get($car['imagePath']));

                // Crea il record Image associato al Car appena creato
                Image::create([
                    'path' => $destinationPath,
                    'car_id' => $newCar->id,
                ]);
            }
        }
    }
}
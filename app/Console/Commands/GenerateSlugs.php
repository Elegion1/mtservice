<?php

namespace App\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;

class GenerateSlugs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:slugs {model} {sourceColumn} {slugColumn}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera slug per un modello esistente "Modello" "Colonna Sorgente" "Colonna Slug"';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $modelName = $this->argument('model');
        $sourceColumn = $this->argument('sourceColumn');
        $slugColumn = $this->argument('slugColumn');

        $modelClass = "App\\Models\\$modelName";

        if (!class_exists($modelClass)) {
            $this->error("Il modello $modelClass non esiste.");
            return;
        }

        $items = $modelClass::all();
        $this->info("Trovati " . $items->count() . " record in $modelClass");

        foreach ($items as $item) {
            $originalSlug = Str::slug($item->$sourceColumn);
            $slug = $originalSlug;
            $counter = 1;

            // Evita conflitti di slug
            while ($modelClass::where($slugColumn, $slug)->where('id', '!=', $item->id)->exists()) {
                $slug = $originalSlug . '-' . $counter++;
            }

            $item->$slugColumn = $slug;
            $item->save();

            $this->info("Record ID {$item->id} -> slug: $slug");
        }

        $this->info("Slug generati con successo!");
    }
}

<?php

use App\Models\Partner;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('link');
            $table->timestamps();
        });

        $partners = [
            ['name' => 'Turismo Italia', 'link' => 'https://www.turismoitalia.it'],
            ['name' => 'Vacanze Italia', 'link' => 'https://www.vacanzeit.it'],
            ['name' => 'Viaggi Sicuri', 'link' => 'https://www.viaggisicuri.it'],
            ['name' => 'Escursioni Piemonte', 'link' => 'https://www.escursionipiemonte.it'],
            ['name' => 'Laghi del Nord', 'link' => 'https://www.laghidelnord.it'],
            ['name' => 'Trekking Toscana', 'link' => 'https://www.trekkingtoscana.it'],
            ['name' => 'Vacanze in Umbria', 'link' => 'https://www.vacanzeinumbria.it'],
            ['name' => 'Maratea Vacanze', 'link' => 'https://www.marateavacanze.it'],
            ['name' => 'Tour delle Dolomiti', 'link' => 'https://www.tourdolomiti.it'],
            ['name' => 'Sicilia Travel', 'link' => 'https://www.siciliatravel.it'],
            ['name' => 'Montagne d\'Abruzzo', 'link' => 'https://www.montagnedabruzzo.it'],
            ['name' => 'Campania Escursioni', 'link' => 'https://www.campaniaescursioni.it'],
            ['name' => 'Sardegna Vacanze', 'link' => 'https://www.sardegnavacanze.it'],
            ['name' => 'Tour del Lazio', 'link' => 'https://www.tourdellazio.it'],
            ['name' => 'Veneto Tour', 'link' => 'https://www.venetotour.it'],
            ['name' => 'Puglia Escursioni', 'link' => 'https://www.pugliaescursioni.it'],
            ['name' => 'Calabria Travel', 'link' => 'https://www.calabriatravel.it'],
            ['name' => 'Emilia Romagna Vacanze', 'link' => 'https://www.emiliaromagnavacanze.it'],
            ['name' => 'Friuli Venezia Giulia Tour', 'link' => 'https://www.friuliveneziagiuliatour.it'],
            ['name' => 'Abruzzo Escursioni', 'link' => 'https://www.abruzzoescursioni.it']
        ];

        foreach($partners as $partner) {
            Partner::create($partner);
        };
        
        // Puoi utilizzare questo array direttamente nel tuo codice PHP per eseguire le operazioni desiderate.
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partners');
    }
};

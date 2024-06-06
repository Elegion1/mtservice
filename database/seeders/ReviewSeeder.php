<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'name' => 'Marco Rossi',
                'title' => 'Servizio eccezionale!',
                'body' => 'Ho usufruito del servizio di noleggio auto e sono rimasto molto soddisfatto. Auto pulita e in ottime condizioni, personale cortese e professionale. Consigliatissimo!',
                'rating' => 5,
            ],
            [
                'name' => 'Anna Bianchi',
                'title' => 'Esperienza positiva',
                'body' => 'Ho prenotato un transfer per l\'aeroporto e tutto è stato impeccabile. Autista puntuale, auto confortevole e prezzo conveniente. Sicuramente tornerò a utilizzare questo servizio.',
                'rating' => 4,
            ],
            [
                'name' => 'Giuseppe Verdi',
                'title' => 'Assolutamente soddisfatto',
                'body' => 'Ho noleggiato un\'auto per un viaggio di lavoro e sono rimasto molto soddisfatto. Auto moderna e pulita, servizio rapido e efficiente. Sicuramente da consigliare.',
                'rating' => 5,
            ],
            [
                'name' => 'Laura Neri',
                'title' => 'Consigliato!',
                'body' => 'Ho prenotato un\'escursione e sono rimasta piacevolmente sorpresa dalla professionalità del servizio. Guida esperta e cortese, itinerario interessante e ben organizzato. Voto alto!',
                'rating' => 4,
            ],
            [
                'name' => 'Simone Gialli',
                'title' => 'Esperienza indimenticabile',
                'body' => 'Ho partecipato a un tour organizzato e devo dire che è stato fantastico. Luoghi meravigliosi, gruppo simpatico e guida competente. Non vedo l\'ora di ripetere l\'esperienza.',
                'rating' => 5,
            ],
            [
                'name' => 'Francesca Rosso',
                'title' => 'Servizio impeccabile',
                'body' => 'Ho prenotato un trasferimento per una serata speciale e sono rimasta molto soddisfatta. Autista cortese, auto di lusso e servizio professionale. Davvero eccellente.',
                'rating' => 5,
            ],
            [
                'name' => 'Davide Marroni',
                'title' => 'Ottimo noleggio',
                'body' => 'Ho affittato un\'auto per un weekend fuori città e sono rimasto molto soddisfatto. Auto in ottime condizioni, prezzo conveniente e servizio rapido. Consigliato a tutti!',
                'rating' => 4,
            ],
            [
                'name' => 'Elena Blu',
                'title' => 'Consigliatissimo',
                'body' => 'Ho usufruito del servizio di noleggio auto e devo dire che è stato tutto perfetto. Auto pulita, personale gentile e disponibile, prezzo competitivo. Voto alto!',
                'rating' => 5,
            ],
            [
                'name' => 'Federico Arancione',
                'title' => 'Servizio professionale',
                'body' => 'Ho prenotato un transfer per un viaggio di lavoro e sono rimasto molto soddisfatto. Autista cortese, auto confortevole e prezzo adeguato. Sicuramente da consigliare.',
                'rating' => 4,
            ],
            [
                'name' => 'Silvia Verde',
                'title' => 'Esperienza positiva',
                'body' => 'Ho noleggiato un\'auto per un breve viaggio e sono rimasta molto soddisfatta. Auto pulita e in ottime condizioni, servizio rapido e cortese. Sicuramente tornerò a utilizzare questo servizio.',
                'rating' => 4,
            ],
            [
                'name' => 'Maria Rossi',
                'title' => 'Esperienza Fantastica!',
                'body' => 'Abbiamo trascorso una giornata indimenticabile, il servizio è stato impeccabile e l\'auto era in condizioni perfette. Consigliato!',
                'rating' => 5,
            ],
            [
                'name' => 'Luca Bianchi',
                'title' => 'Servizio Eccellente',
                'body' => 'Il personale è stato molto professionale e cortese. L\'auto era pulita e confortevole. Sicuramente torneremo!',
                'rating' => 5,
            ],
            [
                'name' => 'Giulia Verdi',
                'title' => 'Consigliato!',
                'body' => 'Ho noleggiato un\'auto per un weekend fuori città e sono rimasta molto soddisfatta. Ottimo rapporto qualità-prezzo.',
                'rating' => 4,
            ],
            [
                'name' => 'Andrea Esposito',
                'title' => 'Molto Soddisfatto',
                'body' => 'Il trasferimento è stato puntuale e il conducente era molto gentile. Sicuramente utilizzeremo di nuovo questo servizio.',
                'rating' => 5,
            ],
            [
                'name' => 'Chiara Neri',
                'title' => 'Esperienza Positiva',
                'body' => 'Abbiamo prenotato un\'escursione e siamo rimasti piacevolmente sorpresi dalla bellezza dei luoghi visitati. Grazie!',
                'rating' => 4,
            ],
            [
                'name' => 'Marco Gialli',
                'title' => 'Fantastico Weekend',
                'body' => 'Grazie al noleggio dell\'auto abbiamo potuto esplorare nuovi posti e vivere un weekend all\'insegna dell\'avventura. Consigliatissimo!',
                'rating' => 5,
            ],
            [
                'name' => 'Anna Rosa',
                'title' => 'Trasferimento Confortevole',
                'body' => 'Il trasferimento è stato comodo e senza intoppi. Il veicolo era spazioso e pulito. Ottima esperienza.',
                'rating' => 4,
            ],
            [
                'name' => 'Francesco Marrone',
                'title' => 'Escursione Memorabile',
                'body' => 'Abbiamo prenotato un\'escursione guidata e il nostro guida è stato molto competente e appassionato. Un\'esperienza da ripetere.',
                'rating' => 5,
            ],
            [
                'name' => 'Laura Blu',
                'title' => 'Auto Nuova e Pulita',
                'body' => 'Il veicolo che abbiamo noleggiato era praticamente nuovo e perfettamente pulito. Molto soddisfatti del servizio.',
                'rating' => 5,
            ],
            [
                'name' => 'Roberto Verdi',
                'title' => 'Servizio Impeccabile',
                'body' => 'Il servizio di trasferimento è stato impeccabile. Il conducente era puntale e cortese. Consigliato a tutti.',
                'rating' => 5,
            ],
        ];

        foreach($reviews as $review) {
            Review::create([
                'name' => $review['name'],
                'title' => $review['title'],
                'body' => $review['body'],
                'rating' => $review['rating'],
            ]);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            ['name' => 'Home', 'link' => 'home', 'show' => 1, 'order' => 0],
            ['name' => 'Transfer', 'link' => 'transfer', 'show' => 1, 'order' => 1],
            ['name' => 'Escursioni', 'link' => 'escursioni', 'show' => 1, 'order' => 2],
            ['name' => 'Noleggio Auto', 'link' => 'noleggio', 'show' => 1, 'order' => 3],
            ['name' => 'Prezzi e destinazioni', 'link' => 'prezziDestinazioni', 'show' => 1, 'order' => 4],
            ['name' => 'Dicono di noi', 'link' => 'diconoDiNoi', 'show' => 1, 'order' => 5],
            ['name' => 'FAQ', 'link' => 'faq', 'show' => 1, 'order' => 6],
            ['name' => 'Su di noi', 'link' => 'home', 'show' => 1, 'order' => 7],
            ['name' => 'Contatti', 'link' => 'contattaci', 'show' => 1, 'order' => 8],
            ['name' => 'Partners', 'link' => 'partners', 'show' => 1, 'order' => 9],
            ['name' => 'Dashboard', 'link' => 'dashboard', 'show' => 1, 'order' => 10]
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}

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
            ['name' => 'Home', 'link' => 'home', 'order' => 0],
            ['name' => 'Transfer', 'link' => 'transfer', 'order' => 1],
            ['name' => 'Escursioni', 'link' => 'escursioni', 'order' => 2],
            ['name' => 'Noleggio Auto', 'link' => 'noleggio', 'order' => 3],
            ['name' => 'Prezzi e destinazioni', 'link' => 'prezziDestinazioni', 'order' => 4],
            ['name' => 'Dicono di noi', 'link' => 'diconoDiNoi', 'order' => 5],
            ['name' => 'FAQ', 'link' => 'faq', 'order' => 6],
            ['name' => 'Su di noi', 'link' => 'home', 'order' => 7],
            ['name' => 'Contatti', 'link' => 'contattaci', 'order' => 8],
            ['name' => 'Partners', 'link' => 'partners', 'order' => 9],
            ['name' => 'Dashboard', 'link' => 'dashboard', 'order' => 10]
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}

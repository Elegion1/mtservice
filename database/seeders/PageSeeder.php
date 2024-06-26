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
            ['name' => 'Transfer', 'link' => 'transfer'],
            ['name' => 'Escursioni', 'link' => 'escursioni'],
            ['name' => 'Noleggio Auto', 'link' => 'noleggio'],
            ['name' => 'Prezzi e destinazioni', 'link' => 'prezziDestinazioni'],
            ['name' => 'Dicono di noi', 'link' => 'diconoDiNoi'],
            ['name' => 'FAQ', 'link' => 'faq'],
            ['name' => 'Su di noi', 'link' => 'home'],
            ['name' => 'Contatti', 'link' => 'contattaci'],
            ['name' => 'Partners', 'link' => 'partners'],
            ['name' => 'Dashboard', 'link' => 'dashboard']
        ];

        foreach ($pages as $page) {
            Page::create($page);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            ['name' => 'review_request_default_time', 'value' => '24', 'type' => 'text'], // Esempio di valore
            ['name' => 'review_request_delay_days', 'value' => '7', 'type' => 'text'],   // Esempio di valore
            ['name' => 'log_max_character_length', 'value' => '255', 'type' => 'text'],  // Esempio di valore
            ['name' => 'booking_pending_expire_time', 'value' => '48', 'type' => 'text'], // Esempio di valore
            ['name' => 'booking_rejected_notification', 'value' => '1', 'type' => 'text'], // Esempio di valore
            ['name' => 'create_customer', 'value' => '1', 'type' => 'text'],  // Esempio di valore
            ['name' => 'default_header_image', 'value' => 'image_path.jpg', 'type' => 'text'], // Esempio di valore
        ];

        foreach($settings as $setting) {
            Setting::create($setting);
        }
    }
}

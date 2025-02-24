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
            ['name' => 'review_request_default_time', 'value' => '10:00', 'type' => 'time'],
            ['name' => 'review_request_delay_days', 'value' => '7', 'type' => 'number'],
            ['name' => 'email_notification', 'value' => '1', 'type' => 'number'],
            ['name' => 'log_max_character_length', 'value' => '255', 'type' => 'number'],
            ['name' => 'booking_pending_expire_time', 'value' => '48', 'type' => 'number'],
            ['name' => 'booking_rejected_notification', 'value' => '1', 'type' => 'number'],
            ['name' => 'create_customer', 'value' => '1', 'type' => 'number'],
            ['name' => 'send_whatsapp_message', 'value' => '0', 'type' => 'number'],
            ['name' => 'transfer_return_minimum_wait_time_minutes', 'value' => '30', 'type' => 'number'],
            ['name' => 'default_header_image', 'value' => 'image_path.jpg', 'type' => 'url'],
            ['name' => 'minimum_rent_days', 'value' => '', 'type' => 'number'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}

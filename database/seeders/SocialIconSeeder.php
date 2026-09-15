<?php

namespace Database\Seeders;

use App\Models\SocialIcon;
use Illuminate\Database\Seeder;

class SocialIconSeeder extends Seeder
{
    public function run(): void
    {
        $icons = [
            ['name' => 'Facebook', 'icon_url' => 'social_icons/facebook.png'],
            ['name' => 'Instagram', 'icon_url' => 'social_icons/instagram.png'],
            ['name' => 'WhatsApp', 'icon_url' => 'social_icons/whatsapp.png'],
            ['name' => 'Telegram', 'icon_url' => 'social_icons/telegram.png'],
            ['name' => 'TikTok', 'icon_url' => 'social_icons/tiktok.png'],
        ];

        foreach ($icons as $icon) {
            SocialIcon::firstOrCreate(['name' => $icon['name']], $icon);
        }
    }
}

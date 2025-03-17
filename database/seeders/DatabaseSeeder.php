<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            CategorySeeder::class,
            CountrySeeder::class,
            RoleSeeder::class,
            UserRoleSeeder::class,
        ]);

        Setting::create([
            'site_name' => 'WhiteSSH',
            'site_url' => 'https://example.com',
            'site_logo' => 'https://example.com/logo.png',
            'site_favicon' => 'https://example.com/favicon.png',
            'tunnel' => json_encode([
                'username' => 'vvip_',
                'limit' => 5,
                'trial_limit' => 0,
                'timelimit' => "30"
            ]),
            'total_accounts' => [
                'ssh' => 0,
                'vmess' => 0,
                'vless' => 0,
                'trojan' => 0,
                'shadowsocks' => 0,
                'socks5' => 0,
            ],
            'api_price' => json_encode([
                "prices" => [
                    "30 Days" => 30,
                    "60 Days" => 60,
                    "90 Days" => 90,
                ]
            ])
        ]);
    }
}

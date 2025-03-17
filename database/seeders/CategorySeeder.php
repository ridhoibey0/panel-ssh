<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'SSH',
                'slug' => 'ssh',
            ],
            [
                'name' => 'Vmess',
                'slug' => 'vmess',
            ],
            [
                'name' => 'Vless',
                'slug' => 'vless',
            ],
            [
                'name' => 'Trojan',
                'slug' => 'trojan',
            ],
            [
                'name' => 'Shadowsocks',
                'slug' => 'shadowsocks'
            ],
            [
                'name' => 'Socks 5',
                'slug' => 'socks-5'
            ]
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}

<?php

namespace Database\Seeders;

use App\Models\PaymentChannel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = 
        [
            [
                'name' => 'BRI',
                'code' => 'BRI',
                'logo' => 'BRI.png'
            ],
            [
                'name' => 'BNI',
                'code' => 'BNI',
                'logo' => 'BNI.png'
            ],
            [
                'name' => 'Mandiri',
                'code' => 'MANDIRI',
                'logo' => 'Mandiri.png'
            ],
            [
                'name' => 'Permata Bank',
                'code' => 'PERMATA',
                'logo' => 'Permata.png'
            ],
            [
                'name' => 'BSI Bank',
                'code' => 'BSI',
                'logo' => 'BSI.png'
            ],
            [
                'name' => 'BCA',
                'code' => 'BCA',
                'logo' => 'BCA.png'
            ],
        ];

        foreach ($channels as $channel) {
            PaymentChannel::create($channel);
        }
    }
}

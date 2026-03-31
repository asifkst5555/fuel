<?php

namespace Database\Seeders;

use App\Models\CrowdReport;
use App\Models\FuelStatus;
use App\Models\Station;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
                User::updateOrCreate([
            'email' => 'admin@hathazari.info',
        ], [
            'name' => 'Hathazari Admin',
            'password' => Hash::make('admin@1234'),
            'role' => User::ROLE_ADMIN,
            'email_verified_at' => now(),
        ]);

        Station::query()->delete();

        $stations = [
            [
                'name' => 'পোস্টার ফিলিং স্টেশন',
                'location' => 'কর্ণিয়ার নদীর পাড়, হাটহাজারী পৌরসভা',
                'octane' => true,
                'diesel' => true,
            ],
            [
                'name' => 'এম আলম ফিলিং স্টেশন',
                'location' => 'বাস স্ট্যান্ড, হাটহাজারী, চট্টগ্রাম',
                'octane' => false,
                'diesel' => true,
            ],
            [
                'name' => 'হাট এম. ছিদ্দিক ফিলিং স্টেশন',
                'location' => 'নোয়াপাড়া মাদ্রাসা সংলগ্ন, বাস স্ট্যান্ড, হাটহাজারী, চট্টগ্রাম',
                'octane' => true,
                'diesel' => true,
            ],
            [
                'name' => 'টি.এম সোনারগাঁও ফিলিং স্টেশন',
                'location' => 'ইসলামিয়া হাট, ফতেপুর, হাটহাজারী, চট্টগ্রাম',
                'octane' => false,
                'diesel' => false,
            ],
            [
                'name' => 'জন জন ফিলিং স্টেশন',
                'location' => 'নলিয়ারহাট, খোশার দ্বিতীয় গেট, হাটহাজারী, চট্টগ্রাম',
                'octane' => false,
                'diesel' => true,
            ],
            [
                'name' => 'বিআরটিসি ফিলিং স্টেশন',
                'location' => 'নতুন পাড়া, বিবিরহাট অফিস সংলগ্ন, হাটহাজারী, চট্টগ্রাম',
                'octane' => false,
                'diesel' => true,
            ],
            [
                'name' => 'মুস্ত ফিলিং স্টেশন',
                'location' => 'নলিয়ারহাট পুরাতন বাস স্ট্যান্ড, হাটহাজারী, চট্টগ্রাম',
                'octane' => true,
                'diesel' => true,
            ],
            [
                'name' => 'মেসার্স আলাল ফিলিং স্টেশন',
                'location' => 'ফতেপুর, উপজেলা হাটহাজারী, চট্টগ্রাম',
                'octane' => false,
                'diesel' => true,
            ],
            [
                'name' => 'লতিফ এন্ড ব্রাদার্স ফিলিং স্টেশন',
                'location' => 'নলিয়ারহাট নতুন ব্রিজ সংলগ্ন, হাটহাজারী, চট্টগ্রাম',
                'octane' => false,
                'diesel' => true,
            ],
            [
                'name' => 'হাজী মোহাম্মদ হক ফিলিং স্টেশন',
                'location' => 'বড়দিঘীর পাড়, হাটহাজারী, চট্টগ্রাম',
                'octane' => false,
                'diesel' => true,
            ],
        ];

        foreach ($stations as $stationData) {
            $station = Station::updateOrCreate(
                ['name' => $stationData['name']],
                ['location' => $stationData['location']]
            );

            FuelStatus::updateOrCreate([
                'station_id' => $station->id,
            ], [
                'octane' => $stationData['octane'],
                'diesel' => $stationData['diesel'],
                'created_at' => now()->subHours(4),
                'updated_at' => now()->subMinutes(fake()->numberBetween(1, 20)),
            ]);

            $station->crowdReports()->create([
                'crowd_level' => CrowdReport::LEVEL_LOW,
                'ip_address' => '127.0.0.1',
                'created_at' => now()->subMinutes(fake()->numberBetween(2, 25)),
                'updated_at' => now()->subMinutes(fake()->numberBetween(1, 15)),
            ]);
        }
    }
}

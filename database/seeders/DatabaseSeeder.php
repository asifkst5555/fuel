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
            ['name' => 'লতিফ এন্ড ব্রাদার্স ফিলিং স্টেশন', 'location' => 'চট্টগ্রাম-খাগড়াছড়ি মহাসড়ক, ফরহাদাবাদ, হাটহাজারী, চট্টগ্রাম', 'dealer' => 'যমুনা', 'octane' => true, 'petrol' => true, 'diesel' => true],
            ['name' => 'মুছা ফিলিং স্টেশন', 'location' => 'হাটহাজারী-নাজিরহাট সড়ক এলাকা, হাটহাজারী বাসস্ট্যান্ডের আশপাশ, চট্টগ্রাম', 'dealer' => 'যমুনা', 'octane' => true, 'petrol' => false, 'diesel' => true],
            ['name' => 'জম জম ফিলিং স্টেশন', 'location' => 'চট্টগ্রাম-রাঙামাটি-খাগড়াছড়ি সড়ক, ওয়ার্ড ১, হাটহাজারী, চট্টগ্রাম', 'dealer' => 'মেঘনা', 'octane' => true, 'petrol' => false, 'diesel' => false],
            ['name' => 'বিআরটিসি ফিলিং স্টেশন', 'location' => 'বিআরটিসি বাস ডিপো এলাকা, এন১০৬ / চট্টগ্রাম-রাঙামাটি-খাগড়াছড়ি সড়ক, চট্টগ্রাম', 'dealer' => 'যমুনা', 'octane' => false, 'petrol' => true, 'diesel' => true],
            ['name' => 'হাজী এম. ছিদ্দিক ফিলিং স্টেশন', 'location' => 'হাটহাজারী উপজেলা, চট্টগ্রাম', 'dealer' => 'যমুনা', 'octane' => false, 'petrol' => false, 'diesel' => true],
            ['name' => 'হাজী মোহাং ইসলাম ফিলিং স্টেশন', 'location' => 'চট্টগ্রাম-রাঙামাটি সড়ক, বিসিএসআইআর আবাসিক এলাকা, লালিয়ারহাট, হাটহাজারী, চট্টগ্রাম', 'dealer' => 'মেঘনা', 'octane' => false, 'petrol' => false, 'diesel' => true],
            ['name' => 'এম আলম ফিলিং স্টেশন', 'location' => '১৫৫, রামগড় রোড, আধুনিক হাসপাতালের দক্ষিণ পাশে, হাটহাজারী, চট্টগ্রাম', 'dealer' => 'পদ্মা', 'octane' => false, 'petrol' => false, 'diesel' => true],
            ['name' => 'মেসার্স আলাওল ফিলিং স্টেশন', 'location' => 'ফতেপুর, হাটহাজারী, চট্টগ্রাম', 'dealer' => 'মেঘনা', 'octane' => false, 'petrol' => false, 'diesel' => true],
            ['name' => 'পেরেন্টস ফিলিং স্টেশন', 'location' => 'চট্টগ্রাম-রাঙামাটি মহাসড়ক, হাটহাজারী, চট্টগ্রাম', 'dealer' => 'মেঘনা', 'octane' => false, 'petrol' => false, 'diesel' => false],
            ['name' => 'বি.এন সোনারগাও ফিলিং স্টেশন', 'location' => 'অক্সিজেন-কুয়াইশ লিংক রোড, বঙ্গবন্ধু অ্যাভিনিউ রোড, চট্টগ্রাম', 'dealer' => 'মেঘনা', 'octane' => false, 'petrol' => false, 'diesel' => false],
        ];

        foreach ($stations as $stationData) {
            $station = Station::updateOrCreate(
                ['name' => $stationData['name']],
                ['location' => $stationData['location'], 'dealer' => $stationData['dealer']]
            );

            FuelStatus::updateOrCreate([
                'station_id' => $station->id,
            ], [
                'octane' => $stationData['octane'],
                'petrol' => $stationData['petrol'],
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

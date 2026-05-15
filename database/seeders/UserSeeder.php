<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder {
    public function run(): void {
   User::create([
    'name'              => 'Dr. Ayşe Kaya',
    'email'             => 'bronze@vetapp.com',
    'password'          => Hash::make('password'),
    'clinic_name'       => 'Pati Veteriner Kliniği',
    'slug'              => 'pati-veteriner',
    'phone'             => '05321234567',
    'subscription_plan' => 'bronze',
    'is_active'         => true,
]);

User::create([
    'name'              => 'Dr. Mehmet Yılmaz',
    'email'             => 'silver@vetapp.com',
    'password'          => Hash::make('password'),
    'clinic_name'       => 'Patika Hayvan Hastanesi',
    'slug'              => 'patika-hastanesi',
    'phone'             => '05331234567',
    'subscription_plan' => 'silver',
    'is_active'         => true,
]);

User::create([
    'name'              => 'Dr. Zeynep Arslan',
    'email'             => 'gold@vetapp.com',
    'password'          => Hash::make('password123'),
    'clinic_name'       => 'VetLife Hayvan Kliniği',
    'slug'              => 'vetlife-klinik',
    'phone'             => '05341234567',
    'subscription_plan' => 'gold',
    'is_active'         => true,
]);
    }
}
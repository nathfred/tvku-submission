<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Employee;
use App\Models\Submission;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('id_ID');

        // ADMIN (HRD)
        // User::create([
        //     'name' => 'Admin HRD',
        //     'role' => 'admin-hrd',
        //     'gender' => 'male',
        //     'ktp' => $faker->unique()->nik(),
        //     'address' => 'Jl. Nakula 1 No. 5-11, Semarang',
        //     'birth' => '1990-01-01',
        //     'last_education' => 'HRD TVKU',
        //     'phone' => '08123456789',
        //     'email' => 'hrd@tvku.tv',
        //     'email_verified_at' => now(),
        //     'password' => bcrypt('TVKU12345'), // password
        //     'remember_token' => Str::random(10),
        // ]);

        // ADMIN (DIVISION)
        $name_array = ['Divisi IT', 'Divisi Produksi', 'Divisi Teknikal Support', 'Divisi Marketing', 'Divisi Keuangan', 'Divisi Umum', 'Divisi Human Resources', 'Divisi News'];
        $division_array = ['IT', 'Produksi', 'Teknikal Support', 'Marketing', 'Keuangan', 'Umum', 'Human Resources', 'News'];
        $gender = $faker->randomElement(['male', 'female']);

        // for ($i = 0; $i < 8; $i++) {
        //     User::create([
        //         'name' => $name_array[$i],
        //         'role' => 'admin-divisi',
        //         'gender' => 'male',
        //         'ktp' => $faker->unique()->nik(),
        //         'address' => 'Jl. Nakula 1 No. 5-11, Semarang',
        //         'birth' => '1990-01-01',
        //         'last_education' => $division_array[$i] . ' TVKU',
        //         'phone' => '08123456789' . $i * 2,
        //         'email' => 'divisi' . trim(strtolower(str_replace(' ', '', $division_array[$i]))) . '@tvku.tv',
        //         'email_verified_at' => now(),
        //         'password' => bcrypt('TVKU1234'), // password
        //         'remember_token' => Str::random(10),
        //     ]);
        // }
        User::create([
            'name' => 'Divisi HRD Keuangan',
            'role' => 'admin-divisi',
            'gender' => 'male',
            'ktp' => $faker->unique()->nik(),
            'address' => 'Jl. Nakula 1 No. 5-11, Semarang',
            'birth' => '1990-01-01',
            'last_education' => 'HRD Keuangan TVKU',
            'phone' => '08123456789987',
            'email' => 'divisihrdkeuangan@tvku.tv',
            'email_verified_at' => now(),
            'password' => bcrypt('TVKU1234'), // password
            'remember_token' => Str::random(10),
        ]);


        // USER
        // DISABLED FOR DEPLOYMENT
        // User::factory(env('SAMPLE_USER', 10))->create();
        // for ($i = 2; $i < 12; $i++) {
        //     User::create([
        //         'id' => $i,
        //         'name' => $faker->name,
        //         'role' => 'employee',
        //         'ktp' => $faker->unique()->nik(),
        //         'address' => $faker->streetAddress() . ', ' . $this->faker->state(),
        //         'birth' => $faker->dateTimeBetween,
        //         'last_education' => 'Universitas Dian Nuswantoro',
        //         'phone' => $faker->phoneNumber(),
        //         'email' => $faker->unique()->safeEmail(),
        //         'email_verified_at' => now(),
        //         'password' => bcrypt('123123'), // password
        //         'remember_token' => Str::random(10),
        //     ]);
        // }

        // EMPLOYEE
        // Employee::factory(env('SAMPLE_USER', 10))->create();
        // DISABLED FOR DEPLOYMENT
        // $position_array = ['Manager', 'Kepala', 'Anggota'];
        // $division_array = ['IT', 'Produksi', 'Teknis', 'Marketing', 'Keuangan', 'Umum', 'HRD'];
        // for ($i = 8; $i < env('SAMPLE_USER', 10) + 8; $i++) {
        //     Employee::create([
        //         'user_id' => $i,
        //         'npp' => mt_rand(1000, 9000),
        //         'position' => $position_array[array_rand($position_array)],
        //         'division' => $division_array[array_rand($division_array)],
        //         'joined' => mt_rand(1995, 2021)
        //     ]);
        // }

        // SUBMISSION
        // DISABLED FOR DEPLOYMENT
        // Submission::factory(5)->create();
    }
}

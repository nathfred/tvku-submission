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
        User::create([
            'name' => 'Admin HRD',
            'role' => 'admin-hrd',
            'gender' => 'male',
            'ktp' => $faker->unique()->nik(),
            'address' => 'Jl. Nakula 1 No. 5-11, Semarang',
            'birth' => '2000-08-25',
            'last_education' => 'Universitas HRD',
            'phone' => '08123456789',
            'email' => 'hrd@tvku.tv',
            'email_verified_at' => now(),
            'password' => bcrypt('123123'), // password
            'remember_token' => Str::random(10),
        ]);

        // ADMIN (DIVISION)
        $name_array = ['Ansori IT', 'Budi Produksi', 'Charlie Teknikal', 'Denny Marketing', 'Eko Finansial', 'Felix Umum'];
        $division_array = ['IT', 'Produksi', 'Teknikal', 'Marketing', 'Keuangan', 'Umum'];
        $gender = $faker->randomElement(['male', 'female']);

        for ($i = 0; $i < 6; $i++) {
            User::create([
                'name' => $name_array[$i],
                'role' => 'admin-divisi',
                'gender' => 'male',
                'ktp' => $faker->unique()->nik(),
                'address' => 'Jl. Pemuda ' . $i . ' No. ' . $i * 2 . ', Semarang',
                'birth' => '2000-09-24',
                'last_education' => 'Universitas ' . $division_array[$i],
                'phone' => '0898765431' . $i * 2,
                'email' => 'divisi' . trim(strtolower($division_array[$i])) . '@tvku.tv',
                'email_verified_at' => now(),
                'password' => bcrypt('123123'), // password
                'remember_token' => Str::random(10),
            ]);
        }

        // USER
        User::factory(env('SAMPLE_USER', 10))->create();
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
        $position_array = ['Manager', 'Kepala', 'Anggota'];
        $division_array = ['IT', 'Produksi', 'Teknikal', 'Marketing', 'Keuangan', 'Umum'];
        for ($i = 8; $i < env('SAMPLE_USER', 10) + 8; $i++) {
            Employee::create([
                'user_id' => $i,
                'npp' => mt_rand(1000, 9000),
                'position' => $position_array[array_rand($position_array)],
                'division' => $division_array[array_rand($division_array)],
                'joined' => mt_rand(1995, 2021)
            ]);
        }

        // SUBMISSION
        Submission::factory(5)->create();
    }
}

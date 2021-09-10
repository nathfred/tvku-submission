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

        // ADMIN
        User::create([
            'name' => 'Admin TVKU',
            'role' => 'admin',
            'ktp' => $faker->nik(),
            'address' => 'Jl. Nakula 1 No. 5-11, Semarang',
            'birth' => '2000-08-25',
            'last_education' => 'Universitas TVKU',
            'phone' => '08123456789',
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('123123'), // password
            'remember_token' => Str::random(10),
        ]);

        // USER
        User::factory(env('SAMPLE_USER', 10))->create();

        // EMPLOYEE
        Employee::factory(env('SAMPLE_USER', 10))->create();

        // SUBMISSION
        Submission::factory(5)->create();
    }
}

<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class EmployeeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Employee::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $position_array = ['Manager', 'Head', 'Member'];
        $division_array = ['Production', 'IT', 'Technical', 'Marketing', 'Financial', 'Common'];
        return [
            'id' => mt_rand(1, env('SAMPLE_USER', 10)),
            'user_id' => mt_rand(1, env('SAMPLE_USER', 10)),
            'npp' => mt_rand(1000, 9000),
            'division' => $division_array[array_rand($division_array)],
            'position' => $position_array[array_rand($position_array)],
            'joined' => mt_rand(1995, 2021)
        ];
    }
}

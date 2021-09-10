<?php

namespace Database\Factories;

use App\Models\Submission;
use Illuminate\Database\Eloquent\Factories\Factory;

class SubmissionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Submission::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $description_array = [
            'Pergi',
            'Liburan',
            'Keluarga Sakit',
            'Keluarga Nikah',
            'Keperluan Lainnya',
            'Pekerjaaan Lain'
        ];
        $status_array = ['Past', 'Upcoming'];
        $approval_array = ['Accepted', 'Declined', 'Unconfirmed'];
        $approval_desc_array = ['Saya ijinkan', 'Saya tidak ijinkan', 'Alasan kurang konkrit!'];

        return [
            'id' => mt_rand(1, env('SAMPLE_USER', 5)),
            'employee_id' => mt_rand(1, env('SAMPLE_USER', 5)),
            'description' => $description_array[array_rand($description_array)],
            'start_date' => $this->faker->dateTimeThisYear(),
            'end_date' => $this->faker->dateTimeThisYear(),
            'status' => $status_array[array_rand($status_array)],
            'approval' => $approval_array[array_rand($approval_array)],
            'approval_description' => $approval_desc_array[array_rand($approval_desc_array)]
        ];
    }
}

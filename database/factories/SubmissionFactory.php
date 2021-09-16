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
        $type_array = ['Sakit', 'Lainnya'];
        $description_array = [
            'Pergi',
            'Liburan',
            'Keluarga Sakit',
            'Keluarga Nikah',
            'Keperluan Lainnya',
            'Pekerjaaan Lain'
        ];
        $status_array = ['Past', 'Ongoing', 'Upcoming'];
        $approval_array = ['', 'TRUE', 'FALSE'];
        $approval_desc_array = ['Saya ijinkan', 'Saya tidak ijinkan', 'Alasan kurang konkrit!', ' '];

        return [
            'employee_id' => mt_rand(1, env('SAMPLE_USER', 5)),
            'type' => $type_array[array_rand($type_array)],
            'description' => $description_array[array_rand($description_array)],
            'start_date' => $this->faker->dateTimeThisYear(),
            'end_date' => $this->faker->dateTimeThisYear(),
            // 'duration_in_days' => 0,
            // 'status' => $status_array[array_rand($status_array)],
            // 'division_id' => mt_rand(2, 6),
            'division_approval' => NULL,
            'division_signed_date' => NULL,
            // 'hrd_id' => '1',
            'hrd_approval' => NULL,
            'hrd_signed_date' => NULL,
            'attachment' => NULL
        ];
    }
}

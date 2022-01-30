<?php

namespace Database\Factories;

use Carbon\Carbon;
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
            'Keperluan Pribadi',
            'Kepentingan Mendadak',
            'Pekerjaaan Lain',
        ];
        $status_array = ['Past', 'Ongoing', 'Upcoming'];
        $approval_array = [0, 1];
        $approval_desc_array = ['Saya ijinkan', 'Saya tidak ijinkan', 'Alasan kurang konkrit!', ' '];

        $hrd_approve = mt_rand(0, 1);
        $division_approve = mt_rand(0, 1);
        $hrd_approve = 1;
        $division_approve = 1;
        $hrd_signed_date = NULL;
        $division_signed_date = NULL;

        $random_date = $this->faker->dateTimeBetween('-6 months');
        if ($hrd_approve == 0) {
            $hrd_signed_date = $random_date;
        } else {
            $hrd_signed_date = $random_date;
        }
        if ($division_approve == 0) {
            $division_signed_date = $random_date;
        } else {
            $division_signed_date = $random_date;
        }

        $random_date2 = new Carbon($random_date);
        $random_date2->addDays(mt_rand(1, 10));

        $random_timestamp = new Carbon($random_date);
        $random_timestamp->subDays(mt_rand(1, 7));

        return [
            'employee_id' => mt_rand(1, env('SAMPLE_USER', 5)),
            'type' => $type_array[array_rand($type_array)],
            'description' => $description_array[array_rand($description_array)],
            'start_date' => $random_date,
            'end_date' => $random_date2,
            'division_approval' => $division_approve,
            'division_signed_date' => $division_signed_date,
            'hrd_approval' => $hrd_approve,
            'hrd_signed_date' => $hrd_signed_date,
            'attachment' => NULL,
            'created_at' => $random_timestamp,
            'updated_at' => $random_timestamp,
        ];
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

class CreateSubmissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->string('type'); // SAKIT ATAU LAINNYA
            $table->string('description')->nullable(); // KETERANGAN IJIN
            $table->date('start_date');
            $table->date('end_date');
            // $table->integer('duration_in_days')->nullable();
            $table->string('status')->nullable(); // AKAN ATAU SEDANG ATAU SUDAH BERLANGSUNG
            // $table->string('division_id')->nullable(); // ID DIVISI MANAGER
            $table->boolean('division_approval')->nullable(); // TANDA TANGAN DIVISI MANAGER
            $table->date('division_signed_date')->nullable(); // TANGGAL TANDA TANGAN DIVISI MANAGER
            // $table->string('hrd_id')->nullable(); // ID HRD MANAGER
            $table->boolean('hrd_approval')->nullable(); // TANDA TANGAN HRD MANAGER
            $table->date('hrd_signed_date')->nullable(); // TANGGAL TANDA TANGAN HRD MANAGER
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('submissions');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkTimeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('work_time_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attendance_day_id')->constrained()->cascadeOnDelete();
            $table->datetime('start_time')->nullable();
            $table->datetime('end_time')->nullable();
            $table->text('reason');
            $table->tinyInteger('approval')->unsigned()->comment('ステータス 1:承認待ち、2:承認済み');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('work_time_requests');
    }
}

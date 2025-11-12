<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->default(DB::raw('gen_random_uuid()'));
            $table->unsignedBigInteger('host_id');
            $table->string('name', 100);
            $table->string('email', 50);
            $table->string('phone_number', 20)->nullable();
            $table->date('date');
            $table->string('time_start', 5);
            $table->string('time_end', 5);
            $table->string('note')->nullable();
            $table->string('status')->default('upcoming');
            $table->timestamps();

            $table->foreign('host_id')->references('id')->on('host_details');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};

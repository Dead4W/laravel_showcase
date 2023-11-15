<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->id();

            $table->uuid()->default(DB::raw('gen_random_uuid()'));

            $table->string('company');
            $table->string('model_family');
            $table->string('model_number');

            $table->unsignedInteger('family_id');

            $table->unsignedInteger('user_id')->unique()->nullable();
            $table->foreign('user_id')
                ->references('id')->on('users');

            $table->timestamps();

            $table->index('uuid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};

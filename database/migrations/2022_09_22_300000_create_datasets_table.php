<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('course', 40);
            $table->string('activity', 40);
            $table->unsignedSmallInteger('year');
            $table->string('semester', 20);
            $table->string('name', 100)->nullable()->default(NULL);
            $table->unsignedInteger('num_responses')->default(0);
            $table->timestamps();
            $table->timestamp('statistics_updated_at')->nullable();
            $table->json('labels');
            $table->json('statistics')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('datasets');
    }
};

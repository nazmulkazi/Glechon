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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('current_team_id')->nullable();
            $table->string('name', 100);
            $table->string('email', 100)->unique();
            $table->string('role', 50)->nullable();
            $table->json('permissions')->nullable()->default(null);
            $table->boolean('can_login')->default(1);
            $table->string('password', 60)->nullable();
            $table->rememberToken();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
            $table->string('profile_photo_path', 2048)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

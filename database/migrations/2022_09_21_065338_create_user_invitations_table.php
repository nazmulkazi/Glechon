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
        Schema::create('user_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inviter_id')->nullable()->constrained('users')->nullOnDelete(); // user id of the inviter
            $table->string('invitee')->unique(); // email address of the invitee
            $table->string('invitation_code', 16)->nullable();
            $table->timestamp('invited_at')->useCurrent();
            $table->string('status', 2)->default('SN');
            // status: SN -> sent, RG -> registered, RV -> revoked
            // users can change their email address. thus, we need to store user id of the invitee for future reference
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_invitations');
    }
};

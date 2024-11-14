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
        Schema::create('responses', function (Blueprint $table) {
            $table->foreignId('dataset_id')->onDelete('cascade');
            $table->unsignedSmallInteger('response_id');
            $table->unsignedSmallInteger('sentence_index');
            $table->text('sentence');
            
            $table->primary(['dataset_id', 'response_id', 'sentence_index']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('responses');
    }
};

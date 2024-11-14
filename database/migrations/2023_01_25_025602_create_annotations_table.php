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
        Schema::create('annotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dataset_id')->onDelete('cascade');
            $table->unsignedSmallInteger('response_id');
            
            // for imported datasets an annotator's id may not match an existing user
            // therefore, it cannot be a foreign id
            // we will access annotations by auth::id only.
            // however, we will access annotations of all users only when exporting.
            $table->unsignedSmallInteger('annotator_id');
            
            $table->timestamps();
            $table->json('labels');
            $table->json('context_sentence_indices');
            
            $table->unique(['dataset_id', 'response_id', 'annotator_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('annotations');
    }
};

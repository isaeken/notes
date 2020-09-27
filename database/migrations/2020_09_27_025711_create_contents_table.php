<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->enum('state', \App\Enums\States::asArray())->default(\App\Enums\States::Active);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('note_id')->references('id')->on('notes');
            $table->ipAddress('ip_address')->nullable()->default(null);
            $table->string('user_agent')->nullable()->default(null);
            $table->text('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('contents');
    }
}

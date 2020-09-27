<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->enum('state', \App\Enums\States::asArray())->default(\App\Enums\States::Active);
            $table->enum('type', \App\Enums\NoteType::asArray())->default(\App\Enums\NoteType::Private);
            $table->foreignId('user_id')->references('id')->on('users');
            $table->ipAddress('ip_address')->nullable()->default(null);
            $table->string('user_agent')->nullable()->default(null);
            $table->boolean('comments')->default(true);
            $table->string('title');
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
        Schema::dropIfExists('notes');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('no_birthday_configs', function (Blueprint $table) {
            $table->id();
            $table->text('intro_text');
            $table->text('main_body');    // Antes: phrase_title
            $table->text('closing_text');
            $table->string('sign_off');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('no_birthday_configs');
    }
};

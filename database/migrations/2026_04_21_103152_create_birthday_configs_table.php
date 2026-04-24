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
        Schema::create('birthday_configs', function (Blueprint $table) {
            $table->id();
            $table->string('banner_url');
            $table->text('intro_text');
            $table->text('main_body');    // Antes: footer_text
            $table->text('closing_text'); // Antes: motivation_prompt
            $table->string('sign_off');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('birthday_configs');
    }
};

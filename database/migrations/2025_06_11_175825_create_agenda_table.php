<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('agenda', function (Blueprint $table) {
    //         $table->id();
    //         $table->string('titulo', 120);
    //         $table->unsignedBigInteger('user_id');
    //         $table->datetime('data_inicio');
    //         $table->datetime('data_fim')->nullable();
    //         $table->string('descricao', 255);

    //         $table->foreign('user_id')->references('id')->on('users')
    //             ->onDelete('cascade')->onUpdate('cascade');
    //     });
    // }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};

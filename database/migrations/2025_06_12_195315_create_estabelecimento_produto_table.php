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
        Schema::create('estabelecimento_produto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('produto_id');
            $table->unsignedBigInteger('estabelecimento_id');
            $table->timestamps();

            $table->foreign('estabelecimento_id')->references('id')->on('estabelecimento')
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('produto_id')->references('id')->on('produtos')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estabelecimento_produto');
    }
};

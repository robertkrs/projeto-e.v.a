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
        Schema::create('estabelecimento', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cep', 10)->nullable();
            $table->string('endereco', 250)->nullable();
            $table->string('numero', 250)->nullable();
            $table->string('complemento', 250)->nullable();
            $table->string('bairro', 250)->nullable();
            $table->string('cidade', 250);
            $table->string('estado', 50);
            $table->string('foto_fachada')->nullable();
            $table->string('descricao');
            $table->unsignedBigInteger('usuario_id');

            $table->timestamps();

            $table->foreign('usuario_id')->references('id')->on('users')
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estabelecimento');
    }
};

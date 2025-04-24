<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComentariosRecompensasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios_recompensas', function (Blueprint $table) {
            $table->id();
            $table->longText('comentario');
            $table->integer('valoracion');
            $table->foreignId('users_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('recompensas_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('comentarios_recompensas');
    }
}

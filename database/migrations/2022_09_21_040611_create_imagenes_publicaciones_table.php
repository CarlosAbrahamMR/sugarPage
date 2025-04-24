<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagenesPublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagenes_publicaciones', function (Blueprint $table) {
            $table->foreignId('publicaciones_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('imagenes_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['publicaciones_id', 'imagenes_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagenes_publicaciones');
    }
}

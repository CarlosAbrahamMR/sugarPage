<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagenesRecompensasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagenes_recompensas', function (Blueprint $table) {
            $table->foreignId('recompensas_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('imagenes_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['recompensas_id', 'imagenes_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagenes_recompensas');
    }
}

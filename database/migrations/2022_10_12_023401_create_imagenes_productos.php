<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagenesProductos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imagenes_productos', function (Blueprint $table) {
            $table->foreignId('producto_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('imagenes_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->primary(['producto_id', 'imagenes_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('imagenes_productos');
    }
}

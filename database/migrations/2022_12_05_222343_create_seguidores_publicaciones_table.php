<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeguidoresPublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seguidores_publicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_origen_id')->index()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('users_destino_id')->index()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('seguidores_publicaciones');
    }
}

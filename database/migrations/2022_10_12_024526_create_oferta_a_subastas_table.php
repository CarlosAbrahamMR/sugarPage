<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOfertaASubastasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('oferta_a_subastas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subastas_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('users_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->integer('precio')->nullable();
            $table->string('estatus')->nullable();
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
        Schema::dropIfExists('oferta_a_subastas');
    }
}

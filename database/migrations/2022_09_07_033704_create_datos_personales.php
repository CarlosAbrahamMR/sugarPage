<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDatosPersonales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('datos_personales', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('nombre', 200);
            $table->integer('edad');
            $table->string('sexo', 20);
            $table->string('color_piel', 50);
            $table->string('color_cabello', 50);
            $table->integer('estatura');
            $table->string('complexion', 100);
            $table->string('idioma', 50);
            $table->string('rango_edad', 6)->nullable();
            $table->string('residencia', 6)->nullable();
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
        Schema::dropIfExists('datos_personales');
    }
}

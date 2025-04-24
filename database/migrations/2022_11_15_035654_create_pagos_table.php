<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_recive')->index()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('user_paga')->index()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('estatus')->nullable();
            $table->string('tipo')->nullable();
            $table->dateTime('fecha_pago')->nullable();
            $table->dateTime('fecha_cobro')->nullable();
            $table->double('importe');
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
        Schema::dropIfExists('pagos');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagosPublicationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pagos_publicaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('publicaciones_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('pagos_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('pagos_publication');
    }
}

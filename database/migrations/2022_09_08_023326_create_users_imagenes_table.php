<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersImagenesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_imagenes', function (Blueprint $table) {

            $table->foreignId('users_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('imagenes_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();

            $table->primary(['users_id', 'imagenes_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_imagenes');
    }
}

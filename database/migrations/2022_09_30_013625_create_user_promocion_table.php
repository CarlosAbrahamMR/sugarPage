<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPromocionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_promocion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->index()->constrained('users')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('promociones_id')->index()->constrained('promociones')->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('status')->default('activa');
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
        Schema::dropIfExists('user_promocion');
    }
}

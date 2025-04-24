<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComentariosPublicacionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comentarios_publicacion', function (Blueprint $table) {
            $table->id();
            $table->foreignId('publicaciones_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('users_id')->index()->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->text('descripcion');
            $table->boolean('deleted')->default(false);
            $table->timestamps();
        });

        Schema::table('comentarios_publicacion', function (Blueprint $table) {
            $table->foreignId('parent_id')->index()->nullable()->constrained('comentarios_publicacion')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('comentarios_publicacion');
    }
}

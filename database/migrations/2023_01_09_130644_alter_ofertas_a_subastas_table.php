<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterOfertasASubastasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('oferta_a_subastas', function (Blueprint $table) {
            $table->dropColumn('precio');
            
        });
        Schema::table('oferta_a_subastas', function (Blueprint $table) {
            
            $table->double('precio')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('oferta_a_subastas', function (Blueprint $table) {
            //
        });
    }
}

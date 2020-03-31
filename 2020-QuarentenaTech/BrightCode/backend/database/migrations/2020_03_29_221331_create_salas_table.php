<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('jogador_x')->nullable();
            $table->string('tipo_jogador_x', 1)->default('G')->comment('[G]uest -- [U]ser')->nullable();
            $table->integer('jogador_o')->nullable();
            $table->string('tipo_jogador_o', 1)->default('G')->comment('[G]uest -- [U]ser')->nullable();

            $table->string('status', 1)->default('A')->comments('[A]guardando -- [G]ame -- [F]inalizado');
            $table->integer('ganhador_id')->nullable();
            $table->string('tipo_ganhador', 1)->default('G')->comment('[G]uest -- [U]ser');

            $table->string('quem_inicia')->nullable();

            $table->string('senha')->nullable();
            $table->string('codigo_sala')->nullable();

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
        Schema::dropIfExists('salas');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJogadasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jogadas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('sala_id')->unsigned();
            $table->foreign('sala_id')->references('id')->on('salas');

            $table->integer('jogador_id');
            $table->string('tipo_jogador', 1)->default('G')->comment('[G]uest -- [U]ser');
            $table->string('casa_jogada', 2)->nullable();
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
        Schema::dropIfExists('jogadas');
    }
}

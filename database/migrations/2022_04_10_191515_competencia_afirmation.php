<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompetenciaAfirmation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('afirmation_competencia', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('afirmation_id')->unsigned();
            $table->bigInteger('competencia_id')->unsigned();

            $table->timestamps();

            $table->foreign('competencia_id')->references('id')->on('competencias')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('afirmation_id')->references('id')->on('afirmations')
                ->onDelete('cascade')
                ->onUpdate('cascade');  
    
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('afirmation_competencia');
    }
}

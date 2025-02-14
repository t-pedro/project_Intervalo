<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CapsuleCompetencia extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('capsule_competencia', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('capsule_id')->unsigned();
            $table->bigInteger('competencia_id')->unsigned();

            $table->timestamps();

            $table->foreign('capsule_id')->references('id')->on('capsules')
                ->onDelete('cascade')
                ->onUpdate('cascade');  

            $table->foreign('competencia_id')->references('id')->on('competencias')
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
        Schema::dropIfExists('capsule_competencia');
    }
}

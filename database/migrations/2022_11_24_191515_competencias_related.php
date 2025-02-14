<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CompetenciasRelated extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('competencias_related', function (Blueprint $table) {
            $table->id();

            $table->bigInteger('competencia_id')->unsigned();
            $table->bigInteger('relate_id')->unsigned();
            $table->text('feedback_approve')->nullable();
            $table->text('feedback_disapprove')->nullable();

            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('competencias_related');
    }
}

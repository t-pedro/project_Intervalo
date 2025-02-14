<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTestDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('test_detail', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('test_id')->unsigned();
            $table->bigInteger('competencia_related_id')->unsigned();
            $table->float('score',5,2); 
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('test_id')->references('id')->on('test')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('competencia_related_id')->references('id')->on('competencias_related')
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
        Schema::dropIfExists('test_detail');
    }
}

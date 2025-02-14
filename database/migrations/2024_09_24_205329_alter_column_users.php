<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('sector_id')->nullable()->after('email');;  

            $table->foreign('sector_id')->references('id')->on('sectores')
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
        Schema::create('users', function (Blueprint $table) {
            // Elimina la restricción de clave foránea
           $table->dropForeign(['sector_id']);
            
           $table->dropColumn('sector_id');
        });
    }
};

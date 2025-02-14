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
        Schema::table('test', function (Blueprint $table) {
           // Elimina la restricción de clave foránea
           $table->dropForeign(['person_id']);
            
           // Modifica la columna para que sea nullable
           $table->bigInteger('person_id')->unsigned()->nullable()->change();
        });
    }

    
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('test', function (Blueprint $table) {
            // Si necesitas revertir los cambios, puedes volver a agregar la restricción de clave foránea
            $table->foreign('person_id')->references('id')->on('persons')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            
            // Si quieres revertir el cambio de nullable a no nullable, puedes revertir la modificación
            $table->bigInteger('person_id')->unsigned()->change();
        });
    }
};

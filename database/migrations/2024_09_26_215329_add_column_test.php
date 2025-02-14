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
            $table->unsignedBigInteger('type_id')->after('status_id');
            $table->unsignedBigInteger('diagnostico_id')->nullable()->after('type_id');

            $table->foreign('type_id')->references('id')->on('test_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');  

            $table->foreign('diagnostico_id')->references('id')->on('diagnosticos')
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
        Schema::create('test', function (Blueprint $table) {
            $table->dropColumn('type_id');
            $table->dropColumn('diagnostico_id');
            $table->dropForeign('type_id');
            $table->dropForeign('diagnostico_id');
        });
    }
};

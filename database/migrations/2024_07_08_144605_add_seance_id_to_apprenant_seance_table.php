<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSeanceIdToApprenantSeanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apprenant_seance', function (Blueprint $table) {
            $table->unsignedBigInteger('seance_id')->nullable();

            $table->foreign('seance_id')
                ->references('id')
                ->on('seances')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('apprenant_seance', function (Blueprint $table) {
            //
        });
    }
}

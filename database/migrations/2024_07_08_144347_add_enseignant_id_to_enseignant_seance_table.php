<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEnseignantIdToEnseignantSeanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('enseignant_seance', function (Blueprint $table) {
            $table->unsignedBigInteger('enseignant_id')->nullable();

            $table->foreign('enseignant_id')
                ->references('id')
                ->on('enseignants')
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
        Schema::table('enseignant_seance', function (Blueprint $table) {
            //
        });
    }
}

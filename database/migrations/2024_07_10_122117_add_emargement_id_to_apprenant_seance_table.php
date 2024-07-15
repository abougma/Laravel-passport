<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmargementIdToApprenantSeanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('apprenant_seance', function (Blueprint $table) {
            $table->unsignedBigInteger('emargement_id')->nullable();

            $table->foreign('emargement_id')
                ->references('id')
                ->on('emargements')
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

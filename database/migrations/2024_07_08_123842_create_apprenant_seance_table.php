<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApprenantSeanceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('apprenant_seance', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('apprenant_id');
            $table->timestamps();

            $table->foreign('apprenant_id')->references('id')->on('apprenants');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('apprenant_seance');
    }
}

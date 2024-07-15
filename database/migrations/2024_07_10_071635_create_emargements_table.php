<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmargementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('emargements', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('seance_id');
            $table->foreign('seance_id')->references('id')->on('seances');
            $table->string('objet_type');
            $table->integer('objet_id');
            $table->integer('date_emargement');
            $table->string('statut_presence');
            $table->string('commentaire');
            $table->integer('date_debut_com');
            $table->integer('date_fin_com');
            $table->string('chemin_sign');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('emargements');
    }
}

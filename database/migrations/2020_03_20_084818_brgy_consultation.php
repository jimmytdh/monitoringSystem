<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BrgyConsultation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('consultation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pat_id');
            $table->date('date_consultation');
            $table->char('comorbid')->nullable();
            $table->string('comorbid_details')->nullable();
            $table->char('home_isolation')->nullable();
            $table->char('fever')->nullable();
            $table->date('date_fever')->nullable();
            $table->char('cough')->nullable();
            $table->date('date_cough')->nullable();
            $table->char('colds')->nullable();
            $table->date('date_colds')->nullable();
            $table->char('sorethroat')->nullable();
            $table->date('date_sorethroat')->nullable();
            $table->char('diarrhea')->nullable();
            $table->date('date_diarrhea')->nullable();
            $table->char('travel')->nullable();
            $table->string('travel_address')->nullable();
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
        Schema::dropIfExists('consultation');
    }
}

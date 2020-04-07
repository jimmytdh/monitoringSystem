<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AdmissionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patadm', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('pat_id');
            $table->integer('age');
            $table->text('status')->nullable();
            $table->dateTime('date_admitted');
            $table->dateTime('date_discharge')->nullable();
            $table->string('disposition')->nullable();
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
        Schema::dropIfExists('patadm');
    }
}

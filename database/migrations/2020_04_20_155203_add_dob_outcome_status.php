<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDobOutcomeStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('consultation', function (Blueprint $table) {
            $table->char('dob',5)->after('date_diarrhea')->nullable();
            $table->date('date_dob')->after('dob')->nullable();
            $table->date('date_travel')->after('travel_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('consultation', function (Blueprint $table) {
            //
        });
    }
}

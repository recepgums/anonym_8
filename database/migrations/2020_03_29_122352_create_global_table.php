<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGlobalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('global', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('title','10000');
            $table->string('password')->nullable();
            $table->string('file_name')->nullable();
            $table->string('ip_address');


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
        Schema::dropIfExists('global');
    }
}

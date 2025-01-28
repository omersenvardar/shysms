<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('paketler', function (Blueprint $table) {
            $table->id();
            $table->string('paketadi', 20)->nullable();
            $table->smallInteger('kontoradeti')->default(0);
            $table->smallInteger('kontorbedeli')->default(0);
            $table->smallInteger('paketbedeli')->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paketler');
    }
};

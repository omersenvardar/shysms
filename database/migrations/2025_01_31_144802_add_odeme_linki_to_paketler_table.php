<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOdemeLinkiToPaketlerTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('paketler', function (Blueprint $table) {
            $table->string('odeme_linki')->nullable()->after('paketbedeli');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('paketler', function (Blueprint $table) {
            $table->dropColumn('odeme_linki');
        });
    }
}

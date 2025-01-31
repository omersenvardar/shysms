<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRecipientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Gönderen kullanıcı ID'si
            $table->string('recipient_nickname'); // Alıcı nickname
            $table->string('recipient_phone', 15); // Alıcı telefon numarası
            $table->timestamps();

            // İlişkiler ve indexler
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade'); // Kullanıcı silindiğinde alıcılar da silinir
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('recipients');
    }
}

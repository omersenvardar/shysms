<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderrsTable extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('paket_id'); // Paket ile ilişki
            $table->unsignedBigInteger('user_id'); // Kullanıcı ile ilişki
            $table->string('order_id'); // Ödeme sağlayıcısının gönderdiği sipariş ID
            $table->decimal('price', 10, 2); // Ödeme tutarı
            $table->string('currency')->default('TL'); // Para birimi
            $table->string('status')->default('bekliyor'); // bekliyor, başarılı, başarısız
            $table->timestamps();

            // Foreign key ilişkileri
            $table->foreign('paket_id')->references('id')->on('paketler')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
}

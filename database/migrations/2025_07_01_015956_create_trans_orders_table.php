<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trans_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_customer');
            $table->string('order_code'); //2011-{id}-unique()
            $table->date('order_end_date');
            $table->tinyInteger('order_status')->default(0)->nullable();
            $table->integer('order_pay')->nullable();
            $table->integer('order_change')->nullable();
            $table->integer('total');
            $table->foreign('id_customer')->references('id')->on('customers')->onDelete('cascade'); //relasi database dari id_customer dan delete secara cascade
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_orders');
    }
};

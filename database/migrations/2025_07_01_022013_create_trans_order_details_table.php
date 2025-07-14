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
        Schema::create('trans_order_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_trans');
            $table->unsignedBigInteger('id_service');
            $table->integer('qty');
            $table->integer('subtotal');
            $table->text('notes')->nullable();
            $table->foreign('id_trans')->references('id')->on('trans_orders')->onDelete('cascade');
            $table->foreign('id_service')->references('id')->on('type_of_service')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trans_order_details');
    }
};

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
        Schema::create('invoice_items', function (Blueprint $table) { //P8
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('name');
            $table->string('barcode');
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->decimal('price', 10, 2);
            $table->decimal('price_total', 10, 2);
            $table->tinyInteger('vat_rate');
            $table->decimal('vat_value', 10, 2);
            $table->decimal('gross_value', 10, 2);
            $table->timestamps();

            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_items');
    }
};

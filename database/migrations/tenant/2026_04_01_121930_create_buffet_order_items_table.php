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
        Schema::create('buffet_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('buffet_order_id')
                ->constrained('buffet_orders')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->foreignId('buffet_item_id')
                ->constrained('buffet_items')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();

            $table->unsignedInteger('quantity')->default(1);
            $table->decimal('price', 12, 2);
            $table->decimal('total', 12, 2);

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('buffet_order_items');
    }
};

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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('invoice_number')->unique()->after('id');
            $table->dateTime('invoice_date')->after('invoice_number');
            $table->text('delivery_address')->nullable()->after('total');
            $table->text('notes')->nullable()->after('delivery_address');
            $table->foreignId('address_id')->nullable()->after('delivery_address')
                ->constrained('addresses')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['invoice_number', 'invoice_date', 'delivery_address', 'notes']);
        });
    }
};

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->foreignId('customer_id')->constrained();
            $table->timestamp('date')->useCurrent();
            $table->text('address');
            $table->enum('status', ['ordered', 'in_process', 'in_route', 'delivered'])->default('ordered');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('orders');
    }
};


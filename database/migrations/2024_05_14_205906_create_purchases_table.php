<?php

use App\PurchaseStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();

            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->decimal('total', 10, 2);

            $table->timestamp('submission_date')->useCurrent()->comment('Date when the purchase plan was submitted');
            $table->timestamp('approval_date')->nullable()->comment('Date when the purchase plan was approved');
            $table->timestamp('completion_date')->nullable()->comment('Date when the purchase was completed');
            $table->timestamp('rejection_date')->nullable()->comment('Date when the purchase plan was rejected');

            $table->string('status', 20)->default(PurchaseStatus::Pending->value)->comment('Status of the purchase plan');

            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('completed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('rejected_by')->nullable()->constrained('users')->nullOnDelete();

            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('virtual_card_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('virtual_card_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['debit', 'credit', 'inquiry']);
            $table->decimal('amount', 10, 2);
            $table->decimal('balance_after', 10, 2);
            $table->string('description')->nullable(); // مثال: "دفع طلب رقم 5"
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');  // الإضافة هنا
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('virtual_card_transactions');
    }
};


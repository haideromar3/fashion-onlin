<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->string('size')->after('product_id');
            $table->string('color')->after('size');
            $table->decimal('price', 10, 2)->after('quantity');
            $table->string('name')->after('price');
            $table->string('image')->nullable()->after('name');

            $table->unique(['user_id', 'product_id', 'size', 'color'], 'user_product_unique');
        });
    }

    public function down()
    {
        Schema::table('carts', function (Blueprint $table) {
            $table->dropUnique('user_product_unique');
            $table->dropColumn(['size', 'color', 'price', 'name', 'image']);
        });
    }
};

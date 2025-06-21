<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->enum('size', ['small', 'medium', 'large', 'xlarge', 'xxlarge'])->nullable()->after('stock');
        $table->enum('color', ['red', 'blue', 'yellow', 'black', 'white'])->nullable()->after('size');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn(['size', 'color']);
    });
}

};

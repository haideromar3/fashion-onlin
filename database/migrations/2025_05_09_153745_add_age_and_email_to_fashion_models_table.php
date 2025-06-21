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
        Schema::table('fashion_models', function (Blueprint $table) {
            $table->integer('age')->nullable()->after('name');
            $table->string('email')->nullable()->after('age');
        });
    }
    
    public function down()
    {
        Schema::table('fashion_models', function (Blueprint $table) {
            $table->dropColumn(['age', 'email']);
        });
    }
    
};

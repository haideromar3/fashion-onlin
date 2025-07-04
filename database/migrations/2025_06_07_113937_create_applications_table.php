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
     Schema::create('applications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('vacancy_id')->constrained()->onDelete('cascade');
    $table->string('name');
    $table->string('email');
    $table->text('message')->nullable();
    $table->string('cv_path');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('applications');
    }
};

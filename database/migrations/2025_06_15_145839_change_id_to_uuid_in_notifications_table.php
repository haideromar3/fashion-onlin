<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
public function up(): void
{
    // 1. إزالة AUTO_INCREMENT
    DB::statement('ALTER TABLE notifications MODIFY id BIGINT UNSIGNED NOT NULL');

    // 2. حذف المفتاح الأساسي
    DB::statement('ALTER TABLE notifications DROP PRIMARY KEY');

    // 3. تغيير نوع id إلى CHAR(36) ليتناسب مع UUID
    DB::statement('ALTER TABLE notifications MODIFY id CHAR(36)');

    // 4. إعادة تعيين المفتاح الأساسي
    DB::statement('ALTER TABLE notifications ADD PRIMARY KEY (id)');
}


public function down(): void
{
    // 1. حذف المفتاح الأساسي
    DB::statement('ALTER TABLE notifications DROP PRIMARY KEY');

    // 2. تغيير نوع id إلى BIGINT + AUTO_INCREMENT
    DB::statement('ALTER TABLE notifications MODIFY id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT');

    // 3. إعادة تعيين المفتاح الأساسي
    DB::statement('ALTER TABLE notifications ADD PRIMARY KEY (id)');
}

};


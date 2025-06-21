<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyNotificationsTableForLaravelDefault extends Migration
{
    public function up()
    {
        Schema::table('notifications', function (Blueprint $table) {
            // حذف الأعمدة التي ليست مطلوبة
            if (Schema::hasColumn('notifications', 'user_id')) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            }
            if (Schema::hasColumn('notifications', 'title')) {
                $table->dropColumn('title');
            }
            if (Schema::hasColumn('notifications', 'message')) {
                $table->dropColumn('message');
            }
            if (Schema::hasColumn('notifications', 'is_read')) {
                $table->dropColumn('is_read');
            }

            // إضافة الأعمدة المطلوبة للنظام الافتراضي
            $table->string('type')->after('id');
            $table->string('notifiable_type')->index()->after('type');
            $table->unsignedBigInteger('notifiable_id')->index()->after('notifiable_type');
            $table->text('data')->nullable()->after('notifiable_id');
            $table->timestamp('read_at')->nullable()->after('data');

            // إضافة الفهارس اللازمة
            $table->index(['notifiable_type', 'notifiable_id']);
        });
    }

    public function down()
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn(['type', 'notifiable_type', 'notifiable_id', 'data', 'read_at']);

            // إعادة الأعمدة القديمة (اختياري حسب حاجتك)
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
        });
    }
}

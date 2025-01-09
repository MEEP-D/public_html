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
        Schema::table('sections', function (Blueprint $table) {
            $table->string('title');              // Cột title (tiêu đề)
            $table->text('info')->nullable();     // Cột info (mô tả thông tin, cho phép null)
            $table->string('audio')->nullable();  // Cột audio (lưu đường dẫn, cho phép null)
            $table->json('group_id')->nullable();   // Cột groups (kiểu JSON, lưu danh sách nhóm)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('sections', function (Blueprint $table) {
            $table->dropColumn(['title', 'info', 'audio', 'groups']); // Xóa các cột nếu rollback
        });
    }
};

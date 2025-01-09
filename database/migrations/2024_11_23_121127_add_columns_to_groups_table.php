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
        Schema::table('groups', function (Blueprint $table) {
            $table->string('title');               // Cột title (tiêu đề)
            $table->text('info')->nullable();      // Cột info (mô tả thông tin, cho phép null)
            $table->text('content')->nullable();   // Cột content (nội dung, cho phép null)
            $table->string('imageUrl')->nullable(); // Cột imageUrl (URL ảnh, cho phép null)
            $table->json('question_id')->nullable(); // Cột questions (kiểu JSON, lưu danh sách câu hỏi)
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('groups', function (Blueprint $table) {
            $table->dropColumn(['title', 'info', 'content', 'imageUrl', 'questions']); // Xóa các cột nếu rollback
        });
    }
};

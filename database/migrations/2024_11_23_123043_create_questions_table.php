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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();                  // Cột id tự tăng
            $table->text('contextHtml');    // Cột contextHtml (ngữ cảnh dưới dạng HTML)
            $table->string('contextImageUrl')->nullable(); // Cột contextImageUrl (URL ảnh ngữ cảnh, cho phép null)
            $table->integer('order');       // Cột order (thứ tự câu hỏi)
            $table->string('title');        // Cột title (tiêu đề câu hỏi)
            $table->text('content');        // Cột content (nội dung câu hỏi)
            $table->text('mean')->nullable(); // Cột mean (giải thích, cho phép null)
            $table->string('imageUrl')->nullable(); // Cột imageUrl (URL ảnh, cho phép null)
            $table->json('answer_id')->nullable(); // Cột answers (danh sách các câu trả lời dưới dạng JSON, cho phép null)
            $table->timestamps();           // Tạo cột created_at và updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('questions'); // Xóa bảng questions nếu rollback
    }
};

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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();                  // Tạo cột id tự tăng
            $table->text('content');        // Cột content (nội dung câu trả lời)
            $table->boolean('is_correct');  // Cột is_correct (kiểm tra câu trả lời đúng hay sai)
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
        Schema::dropIfExists('answers'); // Xóa bảng answers nếu rollback
    }
};

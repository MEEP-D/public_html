<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->string('title');            // Cột title (kiểu chuỗi)
            $table->text('info')->nullable();  // Cột info (kiểu văn bản, cho phép null)
            $table->string('url')->nullable(); // Cột url (kiểu chuỗi, cho phép null)
            $table->json('section_id')->nullable(); // Cột sections (kiểu JSON, cho phép null)
        });
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('quizzes', function (Blueprint $table) {
            $table->dropColumn(['title', 'info', 'url', 'section_id']); // Xóa các cột
        });
    }
};

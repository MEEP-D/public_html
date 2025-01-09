<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu.
     *
     * @var string
     */
    protected $table = 'answers';

    /**
     * Các cột có thể được gán giá trị hàng loạt.
     *
     * @var array
     */
    protected $fillable = [
        'content',
        'is_correct',
        'created_at',
        'updated_at',
    ];

    /**
     * Các mối quan hệ Eloquent.
     */

    // Ví dụ: Mỗi câu trả lời thuộc về một câu hỏi
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }

    /**
     * Các accessor/mutator hoặc phương thức bổ sung.
     */

    // Ví dụ: Trả về trạng thái "Đúng" hoặc "Sai" cho `is_correct`
    public function getIsCorrectTextAttribute()
    {
        return $this->is_correct ? 'Đúng' : 'Sai';
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    /**
     * Tên bảng tương ứng trong cơ sở dữ liệu.
     *
     * @var string
     */
    protected $table = 'questions';

    /**
     * Các cột có thể được gán giá trị hàng loạt.
     *
     * @var array
     */
    protected $fillable = [
        'contextHtml',
        'contextImageUrl',
        'order',
        'title',
        'content',
        'mean',
        'imageUrl',
        'answer_id',
        'created_at',
        'updated_at',
    ];

    /**
     * Các mối quan hệ Eloquent.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class, 'question_id');
    }

    /**
     * Các accessor/mutator hoặc phương thức bổ sung.
     */

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

    // Ví dụ: Trả về một đoạn nội dung ngắn gọn của câu hỏi
    public function getShortContentAttribute()
    {
        return substr($this->content, 0, 100) . '...';
    }
}
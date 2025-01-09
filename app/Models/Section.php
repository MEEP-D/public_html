<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'sections';

    protected $fillable = [
        'name',
        'section_group_id',
        'caption',
        'type',
        'title',
        'info',
        'audio',
        'quiz_id',
    ];

    public function groups()
    {
        return $this->hasMany(Group::class, 'section_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }



    public function children() {
        return $this->hasMany($this, 'section_group_id', 'id');
    }
}
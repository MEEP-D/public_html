<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    protected $table = 'groups';

    protected $fillable = [
        'creator_id',
        'name',
        'discount',
        'status',
        'created_at',
        'title',
        'info',
        'content',
        'imageUrl',
        'section_id',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'group_id');
    }

    public function section()
    {
        return $this->belongsTo(Section::class, 'section_id');
    }

    public function groupUsers()
    {
        return $this->hasMany('App\Models\GroupUser', 'group_id', 'id');
    }

    public function users()
    {
        return $this->hasMany('App\Models\GroupUser', 'id', 'group_id');
    }

    public function groupRegistrationPackage()
    {
        return $this->hasOne('App\Models\GroupRegistrationPackage', 'group_id', 'id');
    }

    public function commissions()
    {
        return $this->hasMany(UserCommission::class, 'user_group_id', 'id');
    }

}
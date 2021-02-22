<?php

namespace App\Models\Publication;

use App\Models\Publication\Comment;
use App\Models\Publication\Like;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'specification_id',
        'photo_path',
        'description',
    ];

    public function specification()
    {
        return $this->hasOne(Specification::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}

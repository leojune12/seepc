<?php

namespace App\Models\Publication;

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
}

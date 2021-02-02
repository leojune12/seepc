<?php

namespace App\Models\Publication;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    use HasFactory;

    protected $fillable = [
        'publication_id',
        'motherboard',
        'cpu',
        'ram',
        'graphics',
        'storage',
        'display',
        'keyboard',
        'mouse',
    ];
}

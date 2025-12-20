<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Note extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'category', 'objective', 'content', 'user_id', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}


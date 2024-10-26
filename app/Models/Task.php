<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = [
       'id', 'title', 'description', 'status', 'due_date', 'user_id', 'image'
    ];
    public function user()
    {
        return $this->belongsTo(User::class,'user_id'); 
    }
}
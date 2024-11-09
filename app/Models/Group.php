<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'avatar', 'user_id'];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function owner() 
    {
        return $this->belongsTo(User::class);
    }

    public function messages() 
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function latestMessage()
    {
        return $this->messages()->one()->latestOfMany();
    }
}

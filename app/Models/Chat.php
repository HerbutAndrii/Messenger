<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['user_one_id', 'user_two_id'];

    public function messages() 
    {
        return $this->morphMany(Message::class, 'messageable');
    }

    public function users() 
    {
        return $this->belongsToMany(User::class);
    }

    public function userOne() 
    {
        return $this->belongsTo(User::class, 'user_one_id');
    }
    
    public function userTwo() 
    {
        return $this->belongsTo(User::class, 'user_two_id');
    }

    public function latestMessage()
    {
        return $this->messages()->one()->latestOfMany();
    }

    public function userAvatar()
    {
        return $this->userOne->id === auth()->user()->id ? $this->userTwo->avatar : $this->userOne->avatar;
    }

    public function userName() 
    {
        $user = $this->userOne->id === auth()->user()->id ? $this->userTwo : $this->userOne;
        $contact = $user->relatedContacts()->where('owner_id', auth()->user()->id)->first();

        return $contact->name ?? $user->name;
    }
}

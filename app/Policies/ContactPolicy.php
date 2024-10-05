<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;

class ContactPolicy
{
    public function view(User $user, Contact $contact)
    {
        return $user->contacts()->where('id', $contact->id)->exists();
    }

    public function update(User $user, Contact $contact)
    {
        return $user->contacts()->where('id', $contact->id)->exists();
    }

    public function delete(User $user, Contact $contact)
    {
        return $user->contacts()->where('id', $contact->id)->exists();
    }
}

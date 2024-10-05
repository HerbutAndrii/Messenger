<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Chat;
use App\Models\Contact;
use App\Models\Message;
use App\Policies\ChatPolicy;
use App\Policies\ContactPolicy;
use App\Policies\MessagePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Chat::class => ChatPolicy::class,
        Message::class => MessagePolicy::class,
        Contact::class => ContactPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        //
    }
}

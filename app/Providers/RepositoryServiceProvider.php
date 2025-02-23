<?php

namespace App\Providers;

use App\Repositories\Abstracts\IAuthRepository;
use App\Repositories\Concretes\AuthRepository;
use App\Repositories\Abstracts\IEventRepository;
use App\Repositories\Concretes\EventRepository;
use App\Services\Abstracts\IAuthService;
use App\Services\Concretes\AuthService;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IAuthRepository::class, AuthRepository::class);
        $this->app->bind(IEventRepository::class, EventRepository::class);
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IEventService::class, EventService::class);

    }
} 
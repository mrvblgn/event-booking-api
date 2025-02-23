<?php

namespace App\Providers;

use App\Repositories\Interfaces\IEventRepository;
use App\Repositories\Concretes\EventRepository;
use App\Repositories\Interfaces\IAuthRepository;
use App\Repositories\Concretes\AuthRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(IEventRepository::class, EventRepository::class);
        $this->app->bind(IAuthRepository::class, AuthRepository::class);
    }
} 
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\Abstracts\IAuthService;
use App\Services\Concretes\AuthService;
use App\Services\Abstracts\IEventService;
use App\Services\Concretes\EventService;
use App\Services\Abstracts\ITicketService;
use App\Services\Concretes\TicketService;
use App\Services\Abstracts\ISeatService;
use App\Services\Concretes\SeatService;
use App\Services\Abstracts\IReservationService;
use App\Services\Concretes\ReservationService;
use App\Repositories\Abstracts\IAuthRepository;
use App\Repositories\Concretes\AuthRepository;
use App\Repositories\Abstracts\IEventRepository;
use App\Repositories\Concretes\EventRepository;
use App\Repositories\Abstracts\ITicketRepository;
use App\Repositories\Concretes\TicketRepository;
use App\Repositories\Abstracts\ISeatRepository;
use App\Repositories\Concretes\SeatRepository;
use App\Repositories\Abstracts\IReservationRepository;
use App\Repositories\Concretes\ReservationRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IAuthService::class, AuthService::class);
        $this->app->bind(IEventService::class, EventService::class);
        $this->app->bind(ITicketService::class, TicketService::class);
        $this->app->bind(ISeatService::class, SeatService::class);
        $this->app->bind(IReservationService::class, ReservationService::class);
        $this->app->bind(IAuthRepository::class, AuthRepository::class);
        $this->app->bind(IEventRepository::class, EventRepository::class);
        $this->app->bind(ITicketRepository::class, TicketRepository::class);
        $this->app->bind(ISeatRepository::class, SeatRepository::class);
        $this->app->bind(IReservationRepository::class, ReservationRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}

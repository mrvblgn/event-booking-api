<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\TicketController;

// Public routes with rate limiting
Route::middleware(['throttle:60,1'])->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::get('/events/{id}/seats', [SeatController::class, 'getEventSeats']);
    Route::get('/venues/{id}/seats', [SeatController::class, 'getVenueSeats']);
});

// Auth routes with stricter rate limiting
Route::middleware(['throttle:30,1'])->group(function () {
    Route::post('/auth/login', [AuthController::class, 'login']);
    Route::post('/auth/register', [AuthController::class, 'register']);
});

// Protected routes with admin middleware
Route::middleware(['auth:api', 'admin'])->group(function () {
    Route::post('/events', [EventController::class, 'store']);
    Route::put('/events/{id}', [EventController::class, 'update']);
    Route::delete('/events/{id}', [EventController::class, 'destroy']);
});

// Protected routes
Route::middleware(['auth:api'])->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::post('/auth/refresh', [AuthController::class, 'refresh']);
    
    // Seat management
    Route::post('/seats/block', [SeatController::class, 'blockSeats']);
    Route::delete('/seats/release', [SeatController::class, 'releaseSeats']);
    
    // Reservations
    Route::get('/reservations', [ReservationController::class, 'index']);
    Route::post('/reservations', [ReservationController::class, 'store']);
    Route::get('/reservations/{id}', [ReservationController::class, 'show']);
    Route::post('/reservations/{id}/confirm', [ReservationController::class, 'confirm']);
    Route::delete('/reservations/{id}', [ReservationController::class, 'destroy']);
    
    // Tickets
    Route::get('/tickets', [TicketController::class, 'index']);
    Route::get('/tickets/{id}', [TicketController::class, 'show']);
    Route::get('/tickets/{id}/download', [TicketController::class, 'download']);
    Route::post('/tickets/{id}/transfer', [TicketController::class, 'transfer']);
});
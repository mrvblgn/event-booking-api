use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\ReservationController;

// Event routes
Route::prefix('events')->group(function () {
    Route::get('/', [EventController::class, 'index']);
    Route::get('/{id}', [EventController::class, 'show']);
    Route::get('/{id}/seats', [SeatController::class, 'getEventSeats']);
    
    Route::middleware(['auth:api', 'admin'])->group(function () {
        Route::post('/', [EventController::class, 'store']);
        Route::put('/{id}', [EventController::class, 'update']);
        Route::delete('/{id}', [EventController::class, 'destroy']);
    });
});

// Venue routes
Route::prefix('venues')->group(function () {
    Route::get('/{id}/seats', [SeatController::class, 'getVenueSeats']);
});

// Auth routes
Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api');
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
});

// Protected routes
Route::middleware('auth:api')->group(function () {
    // Seat routes
    Route::prefix('seats')->group(function () {
        Route::post('/block', [SeatController::class, 'blockSeats']);
        Route::delete('/release', [SeatController::class, 'releaseSeats']);
    });

    // Reservation routes
    Route::prefix('reservations')->group(function () {
        Route::post('/', [ReservationController::class, 'store']);
        Route::get('/', [ReservationController::class, 'index']);
        Route::get('/{id}', [ReservationController::class, 'show']);
        Route::post('/{id}/confirm', [ReservationController::class, 'confirm']);
        Route::delete('/{id}', [ReservationController::class, 'destroy']);
    });
}); 
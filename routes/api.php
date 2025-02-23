use App\Http\Controllers\EventController;
use App\Http\Controllers\AuthController;

Route::middleware('auth:api')->group(function () {
    Route::post('/events', [EventController::class, 'store']);
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:api');
}); 
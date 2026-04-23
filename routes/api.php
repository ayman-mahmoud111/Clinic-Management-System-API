<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\PatientController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\Api\MedicalRecordController;
use App\Http\Controllers\Api\BillController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('/logout',[AuthController::class,'logout']);



Route::prefix('admin')
    ->middleware(['auth:sanctum', 'role:admin'])
    ->group(function () {

        Route::get('/dashboard', function () {
            return response()->json([
                'message' => 'Admin Dashboard'
            ]);
        });

        Route::apiResource('/patients', PatientController::class);
        Route::apiResource('/appointments', AppointmentController::class);
        Route::apiResource('/medical-records', MedicalRecordController::class);
        Route::apiResource('/bills', BillController::class);
    });

Route::prefix('receptionist')
    ->middleware(['auth:sanctum', 'role:receptionist'])
    ->group(function () {

        // 👤 Patients
        Route::apiResource('/patients', PatientController::class);

        // 📅 Appointments
        Route::apiResource('/appointments', AppointmentController::class);

        // ♻ Restore
        Route::post('/patients/{id}/restore', [PatientController::class, 'restore']);
        Route::post('/appointments/{id}/restore', [AppointmentController::class, 'restore']);
    });

    Route::prefix('doctor')
    ->middleware(['auth:sanctum', 'role:doctor'])
    ->group(function () {

        //  Doctor sees his own appointments
        Route::get('/appointments', [AppointmentController::class, 'index']);

        //  Medical Records 
        Route::apiResource('/medical-records', MedicalRecordController::class);

        //  View bills 
        Route::get('/bills', [BillController::class, 'index']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/bills', [BillController::class, 'index']);
    Route::post('/bills', [BillController::class, 'store']);
    Route::post('/bills/pay/{bill}', [BillController::class, 'pay']);
});

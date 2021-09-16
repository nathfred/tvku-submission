<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SubmissionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/logout', [UserController::class, 'logout']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => ['auth', 'admin'], 'prefix' => 'admin'], function () {
    Route::get('/index', [AdminController::class, 'index'])->name('admin-index');
});

Route::group(['middleware' => ['auth', 'employee'], 'prefix' => 'employee'], function () {
    Route::get('/index', [EmployeeController::class, 'index'])->name('employee-index');

    Route::get('/profile', [EmployeeController::class, 'show'])->name('employee-profile');
    Route::post('/profile', [EmployeeController::class, 'create'])->name('employee-profile-post');
    // Route::post('/profile/edit', [EmployeeController::class, 'edit'])->name('employee-profile-edit');

    Route::get('/submission', [SubmissionController::class, 'index'])->name('employee-submission');
    Route::get('/submission/create', [SubmissionController::class, 'create'])->name('employee-submission-create');
    Route::post('/submission/create', [SubmissionController::class, 'store'])->name('employee-submission-create-post');
});

require __DIR__ . '/auth.php';

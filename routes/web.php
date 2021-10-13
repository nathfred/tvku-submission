<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminHRDController;
use App\Http\Controllers\AdminDivisiController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\SubmissionController;
use App\Http\Controllers\SuperController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PDFController2;
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

Route::get('/home', [UserController::class, 'home']);

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::group(['middleware' => ['auth', 'super'], 'prefix' => 'super'], function () {
    Route::get('/index', [SuperController::class, 'index'])->name('super-index');
    Route::get('/admin', [SuperController::class, 'admin'])->name('super-admin');

    Route::get('/create/user', [SuperController::class, 'create_user'])->name('super-create-user');
    Route::post('/save/user', [SuperController::class, 'save_user'])->name('super-save-user');
    Route::post('/save/employee', [SuperController::class, 'save_employee'])->name('super-save-employee');

    Route::get('/show/user/{id}', [SuperController::class, 'show_user'])->name('super-show-user');
    Route::get('/show/admin/{id}', [SuperController::class, 'show_admin'])->name('super-show-admin');

    Route::post('/edit/user/{id}', [SuperController::class, 'edit_user'])->name('super-edit-user');
    Route::post('/edit/employee/{id}', [SuperController::class, 'edit_employee'])->name('super-edit-employee');

    Route::get('/delete/user/{id}', [SuperController::class, 'delete_user'])->name('super-delete-user');
});

Route::group(['middleware' => ['auth', 'adminhrd'], 'prefix' => 'adminhrd'], function () {
    Route::get('/index', [AdminHRDController::class, 'index'])->name('adminhrd-index');

    Route::get('/submission', [AdminHRDController::class, 'show'])->name('adminhrd-submission');
    Route::get('/submission/{id}/{acc}', [AdminHRDController::class, 'acc_submission'])->name('adminhrd-submission-acc');
    Route::get('/archive', [AdminHRDController::class, 'archive'])->name('adminhrd-archive');

    Route::get('/employee', [AdminHRDController::class, 'employees'])->name('adminhrd-employee');
});

Route::group(['middleware' => ['auth', 'admindivisi'], 'prefix' => 'admindivisi'], function () {
    Route::get('/index', [AdminDivisiController::class, 'index'])->name('admindivisi-index');

    Route::get('/submission', [AdminDivisiController::class, 'show'])->name('admindivisi-submission');
    Route::get('/submission/{id}/{acc}', [AdminDivisiController::class, 'acc_submission'])->name('admindivisi-submission-acc');
    Route::get('/archive', [AdminDivisiController::class, 'archive'])->name('admindivisi-archive');

    Route::get('/employee', [AdminDivisiController::class, 'employees'])->name('admindivisi-employee');
});

Route::group(['middleware' => ['auth', 'employee'], 'prefix' => 'employee'], function () {
    // Route::get('/index', [EmployeeController::class, 'index'])->name('employee-index');

    Route::get('/profile', [EmployeeController::class, 'show'])->name('employee-profile');
    Route::post('/profile', [EmployeeController::class, 'create'])->name('employee-profile-post');
    // Route::post('/profile/edit', [EmployeeController::class, 'edit'])->name('employee-profile-edit');

    Route::get('/submission', [SubmissionController::class, 'index'])->name('employee-submission');
    Route::get('/submission/create', [SubmissionController::class, 'create'])->name('employee-submission-create');
    Route::post('/submission/create', [SubmissionController::class, 'store'])->name('employee-submission-create-post');
});

Route::group(['middleware' => ['auth',]], function () {
    Route::get('/pdf/{month}', [PDFController::class, 'createPDF'])->name('create-pdf');
    Route::get('/pdf/{month}/{division}', [PDFController2::class, 'createPDF'])->name('create-pdf2');

    Route::post('/pdf-employee', [PDFController::class, 'createPDFEmployee'])->name('create-pdf-employee');

    Route::get('/pdf-submission/{id}', [PDFController::class, 'createPDFSubmission'])->name('create-pdf-submission');

    Route::get('/pdf-archive', [PDFController::class, 'createPDFArchive'])->name('create-pdf-archive');
    Route::get('/pdf-archive/{division}', [PDFController2::class, 'createPDFArchive'])->name('create-pdf-archive2');
});


require __DIR__ . '/auth.php';

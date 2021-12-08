<?php

use App\Models\Student;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FineController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\HouseController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\OfficerController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\RemoveHouseStudentController;
use App\Http\Controllers\StudentPaymentController;
use App\Http\Controllers\StudentWithFinesController;
use App\Http\Controllers\StudentWithHouseController;

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
    return view('auth.login');
});

Auth::routes();
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
    Route::get('/student/data',[StudentController::class,'getStudent'])->name('student.get.data');
    Route::get('/student/select/',[StudentController::class,'selectStudent'])->name('student.select.data');
    Route::get('/student/without/house/',[StudentController::class,'withoutHouse'])->name('student.without.house');
    Route::get('/student/without_house/',[StudentController::class,'getStudentWithoutHouse'])->name('student.without.house.get');
    Route::get('/student/with_fines/data',[StudentWithFinesController::class,'getData'])->name('student.with.fines.get');
    Route::get('/student/with_fines/',[StudentWithFinesController::class,'index'])->name('student.with.fines.index');
    Route::get('/student/with_houses/data',[StudentWithHouseController::class,'getData'])->name('student.with.houses.get');
    Route::get('/student/with_houses/',[StudentWithHouseController::class,'index'])->name('student.with.houses.index');
    Route::resource('student', StudentController::class)->except('create','edit');
    Route::post('/student_house/{student}',[StudentController::class,'attachHouse'])->name('student.attach.house');
    Route::post('/student_fine/{student}',[StudentController::class,'attachFine'])->name('student.attach.fine');
    Route::get('/department/data',[DepartmentController::class,'getDepartment'])->name('department.get.data');
    Route::resource('department', DepartmentController::class)->except('create','edit');
    Route::get('/course/data',[CourseController::class,'getCourse'])->name('course.get.data');
    Route::resource('course', CourseController::class)->except('create','edit');
    Route::get('/fine/data',[FineController::class,'getFine'])->name('fine.get.data');
    Route::get('/fine/select',[FineController::class,'selectFine'])->name('fine.select.data');
    Route::resource('fine', FineController::class)->except('create','edit');
    Route::get('/house/data/',[HouseController::class,'getHouse'])->name('house.get.data');
    Route::get('/house/select/',[HouseController::class,'selectHouse'])->name('house.select.data');
    Route::resource('house', HouseController::class)->except('create','edit');
    Route::get('/officer/data',[OfficerController::class,'getOfficer'])->name('officer.get.data');
    Route::resource('officer', OfficerController::class)->except('create','edit');
    Route::get('/account/data',[UserController::class,'getUser'])->name('user.get.data');
    Route::resource('account', UserController::class)->except('create','edit');
    Route::get('student/payment/data', [StudentPaymentController::class,'getData'])->name('student.payment.get');
    Route::get('student_payment', [StudentPaymentController::class,'index'])->name('student_payment.index');
    Route::get('student_payment/{student}', [StudentPaymentController::class,'showFines'])->name('student_payment.show.fines');
    Route::post('student_payment/{student}', [StudentPaymentController::class,'payFines'])->name('student_payment.pay.fines');
    Route::post('student_remove_house/{student}', [RemoveHouseStudentController::class,'removeHouse'])->name('student.remove.house');
});


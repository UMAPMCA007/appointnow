<?php

use App\Http\Controllers\staff\StaffScheduleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Enums\GuardType;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\Admin\HomeController as AdminHomeController;
use App\Http\Controllers\Business\HomeController as BusinessHomeController;
use App\Http\Controllers\Staff\HomeController as StaffHomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UserRoleController;
use App\Http\Controllers\Admin\BusinessController as AdminBusinessController;
use App\Http\Controllers\Admin\BusinessOwnerController as AdminBusinessOwnerController;
use App\Http\Controllers\Admin\BusinessPlanController;
use App\Http\Controllers\Business\BusinessController as BusinessBusinessController;
use App\Http\Controllers\Business\BusinessLocationController as BusinessBusinessLocationController;
use App\Http\Controllers\Business\BusinessStaffMemberController as BusinessBusinessStaffMemberController;
use App\Http\Controllers\Business\BusinessServiceController as BusinessBusinessServiceController;
use App\Http\Controllers\Business\CalendarController as BusinessCalendarController;
use App\Http\Controllers\Staff\CalendarController as StaffCalendarController;
use App\Http\Controllers\Business\BusinessServiceSignupFormController;
use App\Http\Controllers\Business\BusinessServiceCategoryController;
use App\Http\Controllers\Business\BusinessScheduleController;

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

//Always redirect to business login dashboard
Route::get('/', function(){ return redirect()->route('business.login.form'); });

// Logout Route
Route::post('/logout', [LoginController::class, 'logout'])
    ->middleware('auth:'.GuardType::ADMIN.','.GuardType::BUSINESS.','.GuardType::STAFF)
    ->name('logout');

//Change system language
Route::get('/language/{language}', [LanguageController::class, 'changeLanguage'])
    ->middleware('auth:'.GuardType::ADMIN.','.GuardType::BUSINESS.','.GuardType::STAFF)
    ->name('language.change');

//Admin Routes
Route::prefix('admin')->name('admin.')->group(function(){
    //Admin Login URLs
    Route::prefix('login')->name('login.')->group(function(){
        Route::get('/', [LoginController::class, 'showAdminLoginForm'])->name('form');
        Route::post('/', [LoginController::class, 'processAdminLoginForm'])->name('post');
    });

    //Home Route
    Route::get('/', [AdminHomeController::class, 'index'])
        ->middleware('auth:'.GuardType::ADMIN)
        ->name('home');

    //User Routes
    Route::prefix('user')->name('user.')->middleware('auth:'.GuardType::ADMIN)->group(function(){
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
        Route::post('/', [UserController::class, 'store'])->name('store');
        Route::patch('/{user}', [UserController::class, 'update'])->name('update');
    });

    //User Roles Routes
    Route::prefix('role')->name('role.')->middleware('auth:'.GuardType::ADMIN)->group( function(){
        Route::get('/', [UserRoleController::class, 'index'])->name('index');
        Route::get('/create', [UserRoleController::class, 'create'])->name('create');
        Route::get('/{userRole}/edit', [UserRoleController::class, 'edit'])->name('edit');
        Route::post('/', [UserRoleController::class, 'store'])->name('store');
        Route::patch('/{userRole}', [UserRoleController::class, 'update'])->name('update');
    });

    //Business Owner Routes
    Route::prefix('businessOwner')->name('businessOwner.')->middleware('auth:'.GuardType::ADMIN)->group(function(){
        Route::get('/', [AdminBusinessOwnerController::class, 'index'])->name('index');
        Route::get('/create', [AdminBusinessOwnerController::class, 'create'])->name('create');
        Route::get('/{businessOwner}/edit', [AdminBusinessOwnerController::class, 'edit'])->name('edit');
        Route::post('/', [AdminBusinessOwnerController::class, 'store'])->name('store');
        Route::patch('/{businessOwner}', [AdminBusinessOwnerController::class, 'update'])->name('update');
    });

    //Business Routes
    Route::prefix('business')->name('business.')->middleware('auth:'.GuardType::ADMIN)->group(function(){
        Route::get('/{businessOwner}/create', [AdminBusinessController::class, 'create'])->name('create');
        Route::get('/{business}/edit', [AdminBusinessController::class, 'edit'])->name('edit');
        Route::post('/{businessOwner}', [AdminBusinessController::class, 'store'])->name('store');
        Route::patch('/{business}', [AdminBusinessController::class, 'update'])->name('update');
    });

    //Business Plans Routes
    Route::prefix('businessPlan')->name('businessPlan.')->middleware('auth:'.GuardType::ADMIN)->group(function(){
        Route::get('/', [BusinessPlanController::class, 'index'])->name('index');
        Route::get('/create', [BusinessPlanController::class, 'create'])->name('create');
        Route::get('/{businessPlan}/edit', [BusinessPlanController::class, 'edit'])->name('edit');
        Route::post('/', [BusinessPlanController::class, 'store'])->name('store');
        Route::patch('/{businessPlan}', [BusinessPlanController::class, 'update'])->name('update');
    });
});

//Business Routes
Route::prefix('business')->name('business.')->group(function (){
    //Business Login URLs
    Route::prefix('login')->name('login.')->group(function(){
        Route::get('/', [LoginController::class, 'showBusinessLoginForm'])->name('form');
        Route::post('/', [LoginController::class, 'processBusinessLoginForm'])->name('post');
    });

    //Home Route
    Route::get('/', [BusinessHomeController::class, 'index'])
        ->middleware([GuardType::BUSINESS])
        ->name('home');

    //Business routes
    Route::prefix('business')->name('business.')->middleware([GuardType::BUSINESS])->group(function(){
        Route::get('/create', [BusinessBusinessController::class, 'create'])->name('create');
        Route::get('/{business}/edit', [BusinessBusinessController::class, 'edit'])->name('edit');
        Route::post('/', [BusinessBusinessController::class, 'store'])->name('store');
        Route::patch('/{business}', [BusinessBusinessController::class, 'update'])->name('update');
    });

    //Business Locations Routes
    Route::prefix('businessLocation')->name('businessLocation.')->middleware([GuardType::BUSINESS])->group(function(){
        Route::get('/create', [BusinessBusinessLocationController::class, 'create'])->name('create');
        Route::get('/{businessLocation}/edit', [BusinessBusinessLocationController::class, 'edit'])->name('edit');
        Route::post('/', [BusinessBusinessLocationController::class, 'store'])->name('store');
        Route::patch('/{businessLocation}', [BusinessBusinessLocationController::class, 'update'])->name('update');
    });

    //Business Staff Members Routes
    Route::prefix('businessStaffMember')->name('businessStaffMember.')->middleware([GuardType::BUSINESS])->group(function(){
        Route::get('/', [BusinessBusinessStaffMemberController::class, 'index'])->name('index');
        Route::get('/create', [BusinessBusinessStaffMemberController::class, 'create'])->name('create');
        Route::get('/{businessStaffMember}/edit', [BusinessBusinessStaffMemberController::class, 'edit'])->name('edit');
        Route::post('/', [BusinessBusinessStaffMemberController::class, 'store'])->name('store');
        Route::patch('/{businessStaffMember}', [BusinessBusinessStaffMemberController::class, 'update'])->name('update');
    });

    //Business Service Routes
    Route::prefix('businessService')->name('businessService.')->middleware([GuardType::BUSINESS])->group(function(){
        Route::get('/', [BusinessBusinessServiceController::class, 'index'])->name('index');
        Route::get('/{businessService}/edit', [BusinessBusinessServiceController::class, 'edit'])->name('edit');
        Route::get('/create', [BusinessBusinessServiceController::class, 'create'])->name('create');
        Route::post('/', [BusinessBusinessServiceController::class, 'store'])->name('store');
        Route::patch('/{businessService}', [BusinessBusinessServiceController::class, 'update'])->name('update');
    });

    //Business service signup form routes
    Route::prefix('businessServiceSignupForm')->name('businessServiceSignupForm.')->middleware([GuardType::BUSINESS])->group(function(){
        Route::get('/', [BusinessServiceSignupFormController::class, 'index'])->name('index');
        Route::get('/create', [BusinessServiceSignupFormController::class, 'create'])->name('create');
        Route::get('/{businessServiceSignupForm}/edit', [BusinessServiceSignupFormController::class, 'edit'])->name('edit');
        Route::post('/', [BusinessServiceSignupFormController::class, 'store'])->name('store');
        Route::post('/field', [BusinessServiceSignupFormController::class, 'field'])->name('field');
        Route::patch('/{businessServiceSignupForm}', [BusinessServiceSignupFormController::class, 'update'])->name('update');
        Route::delete('/{businessServiceSignupFormField}', [BusinessServiceSignupFormController::class, 'deleteField'])->name('deleteField');
    });

    //Business service categories routes
    Route::prefix('businessServiceCategory')->name('businessServiceCategory.')->middleware([GuardType::BUSINESS])->group(function(){
        Route::get('/', [BusinessServiceCategoryController::class, 'index'])->name('index');
        Route::get('/{businessServiceCategory}/edit', [BusinessServiceCategoryController::class, 'edit'])->name('edit');
        Route::get('/create', [BusinessServiceCategoryController::class, 'create'])->name('create');
        Route::post('/', [BusinessServiceCategoryController::class, 'store'])->name('store');
        Route::patch('/{businessServiceCategory}', [BusinessServiceCategoryController::class, 'update'])->name('update');
    });

    //Business schedule Routes
    Route::prefix('businessSchedule')->name('businessSchedule.')->middleware([GuardType::BUSINESS])->group(function(){
        Route::get('/', [BusinessScheduleController::class, 'index'])->name('index');
        Route::get('/{businessSchedule}/edit', [BusinessScheduleController::class, 'edit'])->name('edit');
        Route::get('/create', [BusinessScheduleController::class, 'create'])->name('create');
        Route::post('/', [BusinessScheduleController::class, 'store'])->name('store');
        Route::post('/{businessSchedule}/makeDefault', [BusinessScheduleController::class, 'makeDefault'])->name('makeDefault');
        Route::post('/{businessScheduleDay}/changeDayStatus', [BusinessScheduleController::class, 'changeDayStatus'])->name('changeDayStatus');
        Route::patch('/{businessSchedule}', [BusinessScheduleController::class, 'update'])->name('update');
    });

    //Calendar Routes
    Route::prefix('calendar')->name('calendar.')->middleware([GuardType::BUSINESS])->group(function(){
        Route::get('/', [BusinessCalendarController::class, 'index'])->name('index');
    });
});

//Staff Routes
Route::prefix('staff')->name('staff.')->group(function() {
    //Business Login URLs
    Route::prefix('login')->name('login.')->group(function(){
        Route::get('/', [LoginController::class, 'showStaffLoginForm'])->name('form');
        Route::post('/', [LoginController::class, 'processStaffLoginForm'])->name('post');
    });

    //Home Route
    Route::get('/', [StaffHomeController::class, 'index'])
        ->middleware([GuardType::STAFF])
        ->name("home");

    //Calendar Routes
    Route::prefix('calendar')->name('calendar.')->middleware([GuardType::STAFF])->group(function(){
        Route::get('/', [StaffCalendarController::class, 'index'])->name('index');
    });
    //Schedule Routes
    Route::prefix('schedule')->name('schedule.')->middleware([GuardType::STAFF])->group(function(){
        Route::get('/', [StaffScheduleController::class, 'index'])->name('index');
        Route::get('/{staffSchedule}/edit', [StaffScheduleController::class, 'edit'])->name('edit');
        Route::get('/create', [StaffScheduleController::class, 'create'])->name('create');
        Route::post('/', [StaffScheduleController::class, 'store'])->name('store');
        Route::post('/{staffSchedule}/makeDefault', [StaffScheduleController::class, 'makeDefault'])->name('makeDefault');
        Route::post('/{staffScheduleDay}/changeDayStatus', [StaffScheduleController::class, 'changeDayStatus'])->name('changeDayStatus');
        Route::patch('/{staffSchedule}', [StaffScheduleController::class, 'update'])->name('update');
        Route::post('{staffSchedule}/addHour', [StaffScheduleController::class, 'AddHour'])->name('AddHour');
        Route::patch('{dayId}/updateHour', [StaffScheduleController::class, 'updateHour'])->name('updateHour');

    });


});

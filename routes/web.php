<?php

use App\Http\Controllers\Backend\CategoryController;
use App\Http\Controllers\Backend\CourseController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\adminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\instructorController;
use App\Http\Middleware\instructor;
use App\Http\Middleware\Role;



Route::get('/', [userController::class, 'home'])->name('home');



Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [userController::class, 'UserDashboard'])->name('dashboard');
    Route::get('/user/profile', [userController::class, 'UserProfile'])->name('user.profile');
    Route::post('/user/profile/update', [userController::class, 'UserProfileUpdate'])->name('user.profile.update');
    Route::get('/user/change/password', [userController::class, 'UserChangePassword'])->name('user.change.password');
    Route::post('/user/passwordUpdating', [userController::class, 'UserPasswordUpdate'])->name('user.password.update');
    Route::get('/user/profile/logout', [userController::class, 'UserLogout'])->name('user.logout');

});

require __DIR__ . '/auth.php';


// Admin Dashboard
Route::middleware(['auth', 'verified', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [adminController::class, 'AdminDashboard'])->name('admin.dashboard');
    Route::get('/admin/logout', [adminController::class, 'AdminLogout'])->name('admin.logout');
    Route::get('/admin/profile', [adminController::class, 'AdminProfile'])->name('admin.profile');
    Route::post('/admin/profile/store', [adminController::class, 'Store'])->name('admin.profile.store');
    Route::get('/admin/profile/AdminChangePassword', [adminController::class, 'AdminChangePassword'])->name('admin.profile.ChangePassword');
    Route::post('/admin/profile/AdminpasswordUpdate', [adminController::class, 'adminPasswordUpdate'])->name('admin.password.update');

    // category all Routes
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/category', 'index')->name('all.category');
        Route::get('/category/create', 'create')->name('add.category');
        Route::post('/category/store', 'store')->name('store.category');
        Route::get('/category/edit/{id}', 'edit')->name('edit.category');
        Route::patch('/category/update/{id}', 'update')->name('update.category');
        Route::get('/delete/category/{id}', 'destroy')->name('delete.category');
    });

    // SubCategory all Routes
    Route::controller(CategoryController::class)->group(function () {
        Route::get('/all/subcategory', 'AllSubCategory')->name('all.subcategory');
        Route::get('/subcategory/create', 'CreateSubCategory')->name('add.subcategory');
        Route::post('/subcategory/store', 'StoreSubCategory')->name('store.subcategory');
        Route::get('/subcategory/edit/{id}', 'editSubCategory')->name('edit.subcategory');
        Route::patch('/subcategory/update/{id}', 'updateSubCategory')->name('update.subcategory');
        Route::get('/delete/subcategory/{id}', 'destroySubCategory')->name('delete.subcategory');
    });
    //Instructor all Routes
    Route::controller(adminController::class)->group(function () {
        Route::get('/all/instructor', 'AllInstructor')->name('all.instructor');
        Route::post('/user/StatusUpdate', 'UpdateUserStatus')->name('update.user.status');
        Route::get('/instructor/create', 'CreateInstructor')->name('add.instructor');
    });


});

Route::get('/admin/login', [adminController::class, 'AdminLogin'])->name('admin.login');
Route::get('/become/instructor', [adminController::class, 'BecomeInstructor'])->name('become.instructor');
Route::post('/become/register', [adminController::class, 'StoreInstructor'])->name('store.instructor');


// Instructor Dashboard
Route::middleware(['auth', 'verified', 'instructor'])->group(function () {
    Route::get('/instructor/dashboard', [instructorController::class, 'InstructorDashboard'])->name('instructor');

    Route::get('/instructor/profile', [instructorController::class, 'InstructorProfile'])->name('instructor.profile');
    Route::get('/instructor/profile/instructorChangePassword', [instructorController::class, 'InstructorChangePassword'])->name('instructor.profile.ChangePassword');
    Route::post('/instructor/profile/passwordUpdate', [instructorController::class, 'instructorPasswordUpdate'])->name('instructor.password.update');

    Route::get('/instructor/logout', [instructorController::class, 'InstructorlogOut'])->name('instructor.logout');
    Route::post('/instructor/profile/store', [instructorController::class, 'Store'])->name('instructor.profile.store');

    //Instructor all Routes
    Route::controller(CourseController::class)->group(function () {
        Route::get('/all/courses', 'AllCourse')->name('all.courses');
        Route::get('/add/course', 'AddCourse')->name('add.course');
        Route::get('/subcategory/ajax/{category_id}', 'GetSubCategory');
        Route::post('/add/course', 'StoreCourse')->name('store.course');
        Route::get('/edit/course/{id}', 'EditCourse')->name('edit.course');
        Route::get('/update/course/{id}', 'UpdateCourse')->name('update.course');
        Route::get('/delete/course/{id}', 'Destory')->name('delete.course');
    });

}); // End of Instructor Dashboard

Route::get('/instructor/login', [instructorController::class, 'InstructorLogin'])->name('instructor.login');


// user or Student Dashboard
Route::middleware(['auth', 'verified', 'user'])->group(function () {

});

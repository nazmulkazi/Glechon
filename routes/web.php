<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Str;

use App\Http\Controllers\UserController;
use App\Http\Controllers\DatasetController;

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

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::redirect('/', '/login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    
    /**
     * Views
     */
    
    Route::get('/dashboard', [DatasetController::class, 'index'])->name('dashboard');
    
    // User Controller
    
    /**
     * Route::get('/some-route', [MyController::class, 'someAction'])
     *          ->middleware('custom.authorize:ability1,ability2');
     * 
     * $this->middleware('custom.authorize:ability1,ability2'); // inside function
     */
    
    // Route::middleware(['authorize'])->group(function () {
    //     // Define your routes here
    // });
    
    Route::delete('/users/invitation/{id}', [
        UserController::class, 'revokeInvitation'
    ])->name('invitations.destroy');
    
    Route::resource('users', UserController::class)->except([
        'show', 'edit'
    ]);
    
    // Dataset Controller
    
    Route::get('/dataset/import', [
        DatasetController::class, 'import'
    ])->name('dataset.import');
    
    Route::get('/dataset/annotate/{dataset}', [
        DatasetController::class, 'show'
    ])->name('dataset.annotate');
    
    Route::put('/dataset/annotate/{dataset_id}/{response_id}', [
        DatasetController::class, 'storeAnnotation'
    ])->name('dataset.storeAnnotation');
    
    Route::get('/dataset/statistics/{dataset}', [
        DatasetController::class, 'statistics'
    ])->name('dataset.statistics');
    
    Route::get('/dataset/export/{id}', [
        DatasetController::class, 'export'
    ])->name('dataset.export');
    
    Route::resource('dataset', DatasetController::class);
    
    /**
     * Mailables
     */
    
    Route::get('/mailable/user_invitation', function () {
        return new App\Mail\UserInvitation(Auth::user(), str::upper(str::substr(md5(str::random(16)), 0, 16)));
    });
    
    // Route::get('/dataset/list', function () {
    //     return Inertia::render('Dataset/List');
    // })->name('dataset.list');
    
    // Route::get('/dataset/create', function () {
    //     return Inertia::render('Dataset/Create');
    // })->name('dataset.create');
    
    // Route::get('/dataset/import', function () {
    //     return Inertia::render('Dataset/Import');
    // })->name('dataset.import');
});

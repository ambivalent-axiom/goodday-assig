<?php

use App\Http\Controllers\CurrentUserRoleController;
use App\Http\Controllers\permissionController;
use App\Http\Controllers\Post\PostCreateController;
use App\Http\Controllers\Post\PostDestroyController;
use App\Http\Controllers\Post\PostIndexController;
use App\Http\Controllers\Post\PostLandingController;
use App\Http\Controllers\Post\PostUpdateController;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Role;

Route::get('/{type?}', [PostLandingController::class, 'index'])
    ->where('type', 'news|blog')
    ->defaults('type', 'news')
    ->name('landing');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',
])->group(function () {
    Route::get('/roles', function () {
        return Role::pluck('name');
    });

    Route::put('/roles/{user}', [CurrentUserRoleController::class, 'update'])
        ->name('roles.update');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', function() {
            return redirect()->route('blog.index');
        })->name('dashboard');
        Route::get('/permissions',
            [PermissionController::class, 'index'])
            ->name('permissions.index');
        Route::post('/permissions/{role}',
            [PermissionController::class, 'update'])
            ->name('permissions.update');
        foreach (['blog', 'news'] as $type) {
            Route::group(['prefix' => $type], function () use ($type) {
                Route::get('/', [PostIndexController::class, 'index'])
                    ->defaults('type', $type)
                    ->name("$type.index");
                Route::get('/create', [PostCreateController::class, 'index'])
                    ->defaults('type', $type)
                    ->name("$type.create");
                Route::post('/', [PostCreateController::class, 'store'])
                    ->defaults('type', $type)
                    ->name("$type.store");
                Route::get('/{post}/edit', [PostUpdateController::class, 'index'])
                    ->defaults('type', $type)
                    ->name("$type.edit");
                Route::post('/{post}', [PostUpdateController::class, 'update'])
                    ->defaults('type', $type)
                    ->name("$type.update");
                Route::delete('/{post}', [PostDestroyController::class, 'destroy'])
                    ->defaults('type', $type)
                    ->name("$type.destroy");
            });
        }
    });
});

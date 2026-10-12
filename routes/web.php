<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest')->group(function () {
    Route::inertia('/', 'Welcome')->name('home');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');

    Route::inertia('runs', 'runs/Index')->name('runs.index');
    Route::get('runs/{run}', function (string $run) {
        return Inertia::render('runs/Show', [
            'run' => $run,
        ]);
    })->name('runs.show');

    Route::inertia('agents', 'agents/Index')->name('agents.index');
    Route::inertia('connectors', 'connectors/Index')->name('connectors.index');

    Route::inertia('settings/repositories', 'repositories/Index')->name('repositories.edit');
    Route::inertia('settings/organization', 'organization/Index')->name('organization.edit');
    Route::inertia('settings/billing', 'billing/Index')->name('billing.edit');
});

require __DIR__.'/settings.php';

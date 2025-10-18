<?php

use App\Livewire\Customer\ListCustomers;
use App\Livewire\Items\EditItem;
use App\Livewire\Items\ListInventories;
use App\Livewire\Items\ListItems;
use App\Livewire\Management\ListPaymentMethods;
use App\Livewire\Management\ListUsers;
use App\Livewire\Sales\ListSales;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
    
    Route::prefix('management')->group(function(){
        Route::get('manage-customers', ListCustomers::class)->name('customers.index');

        Route::get('manage-payment-methods', ListPaymentMethods::class)->name('payment.methods.index');

        Route::get('manage-users', ListUsers::class)->name('users.index');
    });

    Route::prefix('inventory-management')->group(function(){

        Route::prefix('items')->group(function(){
            Route::get('/', ListItems::class)->name('items.index');
            Route::get('{record}/edit', EditItem::class)->name('item.edit');
        });
        
    
        Route::get('manage-inventories', ListInventories::class)->name('inventories.index');
    });

    Route::prefix('sales')->group(function(){
        Route::get('manage-sales', ListSales::class)->name('sales.index');
    });
});

require __DIR__.'/auth.php';

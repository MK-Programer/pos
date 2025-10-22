<?php

use App\Livewire\POS;

use App\Livewire\Customers\ListCustomers;
use App\Livewire\Customers\CreateCustomer;
use App\Livewire\Customers\EditCustomer;

use App\Livewire\Inventories\ListInventories;
use App\Livewire\Inventories\CreateInventory;
use App\Livewire\Inventories\EditInventory;

use App\Livewire\Items\ListItems;
use App\Livewire\Items\CreateItem;
use App\Livewire\Items\EditItem;

use App\Livewire\Payment\ListPaymentMethods;
use App\Livewire\Payment\CreatePaymentMethod;
use App\Livewire\Payment\EditPaymentMethod;

use App\Livewire\Users\ListUsers;
use App\Livewire\Users\CreateUser;
use App\Livewire\Users\EditUser;

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
    
    Route::get('pos', POS::class)->name('pos');

    Route::prefix('management')->group(function(){

        Route::prefix('manage-customers')->group(function(){
            Route::get('/', ListCustomers::class)->name('customers.index');
            Route::get('create', CreateCustomer::class)->name('customer.create');
            Route::get('{record}/edit', EditCustomer::class)->name('customer.edit');
        });

        Route::prefix('manage-payment-methods')->group(function(){
            Route::get('/', ListPaymentMethods::class)->name('payment.methods.index');
            Route::get('create', CreatePaymentMethod::class)->name('payment.method.create');
            Route::get('{record}/edit', EditPaymentMethod::class)->name('payment.method.edit');
        });

        Route::prefix('manage-users')->group(function(){
            Route::get('/', ListUsers::class)->name('users.index');
            Route::get('create', CreateUser::class)->name('user.create');
            Route::get('{record}/edit', EditUser::class)->name('user.edit');
        });

    });

    Route::prefix('inventory-management')->group(function(){

        Route::prefix('items')->group(function(){
            Route::get('/', ListItems::class)->name('items.index');
            Route::get('create', CreateItem::class)->name('item.create');
            Route::get('{record}/edit', EditItem::class)->name('item.edit');
        });
        
        Route::prefix('manage-inventories')->group(function(){
            Route::get('/', ListInventories::class)->name('inventories.index');
            Route::get('create', CreateInventory::class)->name('inventory.create');
            Route::get('{record}/edit', EditInventory::class)->name('inventory.edit');
        });
        
    });

    Route::prefix('sales')->group(function(){
        Route::get('manage-sales', ListSales::class)->name('sales.index');
    });
});

require __DIR__.'/auth.php';

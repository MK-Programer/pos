<?php

namespace App\Livewire\Customers;

use App\Livewire\Widgets\Actions\CreateAction;
use App\Livewire\Widgets\Actions\DeleteAction;
use App\Livewire\Widgets\Actions\EditAction;
use App\Models\Customer;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ListCustomers extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->heading('List Customers')
            ->query(fn (): Builder => Customer::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('email')
                    ->sortable(),

                TextColumn::make('phone'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->handleRoute('customer.create'),
            ])
            ->recordActions([
                EditAction::make()->handleRoute('customer.edit'),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.customers.list-customers');
    }
}

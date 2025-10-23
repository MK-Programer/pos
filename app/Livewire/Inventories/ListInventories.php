<?php

namespace App\Livewire\Inventories;

use App\Livewire\Widgets\Actions\CreateAction;
use App\Livewire\Widgets\Actions\DeleteAction;
use App\Livewire\Widgets\Actions\EditAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Inventory;
use Filament\Tables\Columns\TextColumn;
use Livewire\Component;

class ListInventories extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->heading('List Inventories')
            ->query(fn (): Builder => Inventory::query())
            ->columns([
                TextColumn::make('item.name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('quantity')
                    ->badge()
                    ->sortable(),

                TextColumn::make('created_at')
                    ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->handleRoute('inventory.create')
            ])
            ->recordActions([
                EditAction::make()->handleRoute('inventory.edit'),
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
        return view('livewire.inventories.list-inventories');
    }
}

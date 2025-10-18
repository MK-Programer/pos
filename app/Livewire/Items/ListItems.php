<?php

namespace App\Livewire\Items;

use App\Livewire\Widgets\Buttons\DeleteButton;
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
use App\Models\Item;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Livewire\Component;

class ListItems extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Item::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable(),

                TextColumn::make('sku')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('price')
                    ->sortable()
                    ->money(),

                TextColumn::make('status')
                    ->badge(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
            ])
            ->recordActions([
                DeleteButton::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }

    public function render(): View
    {
        return view('livewire.items.list-items');
    }
}

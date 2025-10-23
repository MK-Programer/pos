<?php

namespace App\Livewire\Dashboard;

use App\Models\Sale;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestSales extends TableWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Sale::query()->latest())
            ->columns([
                TextColumn::make('customer.name')
                    ->sortable(),

                TextColumn::make('saleItems.item.name')
                    ->label('Sold Items')
                    ->bulleted()
                    ->limitList(2)
                    ->expandableLimitedList(),

                TextColumn::make('discount')
                    ->money(),

                TextColumn::make('total')
                    ->sortable()
                    ->money(),

                TextColumn::make('paid_amount')
                    ->money(),

                TextColumn::make('paymentMethod.name'),

                TextColumn::make('created_at')
                    ->dateTime('Y-m-d h:m A')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
                //
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    //
                ]),
            ]);
    }
}

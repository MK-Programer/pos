<?php

namespace App\Livewire\Sales;

use App\Livewire\Widgets\Actions\DeleteAction;
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
use Livewire\Component;
use App\Models\Sale;
use Filament\Tables\Columns\TextColumn;

class ListSales extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->heading('List Sales')
            ->query(fn (): Builder => Sale::query())
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
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->recordActions([
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
        return view('livewire.sales.list-sales');
    }
}

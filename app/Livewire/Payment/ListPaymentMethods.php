<?php

namespace App\Livewire\Payment;

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
use Livewire\Component;
use App\Models\PaymentMethod;
use Filament\Tables\Columns\TextColumn;

class ListPaymentMethods extends Component implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithTable;
    use InteractsWithSchemas;

    public function table(Table $table): Table
    {
        return $table
            ->heading('List Payment Methods')
            ->query(fn (): Builder => PaymentMethod::query())
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('description')
                    ->limit(50),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make()->handleRoute('payment.method.create'),
            ])
            ->recordActions([
                EditAction::make()->handleRoute('payment.method.edit'),
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
        return view('livewire.payment.list-payment-methods');
    }
}

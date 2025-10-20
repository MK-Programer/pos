<?php

namespace App\Livewire\Management;

use App\Livewire\Widgets\Notifications\Notify;
use App\Models\PaymentMethod;
use Exception;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditPaymentMethod extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public PaymentMethod $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Payment Method')
                    ->description('Update the information below to edit this payment method.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        Textarea::make('description')
                            ->nullable(),
                    ]),
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
        try{
            $data = $this->form->getState();

            $this->record->update($data);

            Notify::send('Updated successfully', "Payment Method {$this->record->name} has been updated successfully");
        }catch(Exception $e){
            Notify::send('Update failure', "Failed to update Payment Method {$this->record->name}");
        }
    }

    public function render(): View
    {
        return view('livewire.management.edit-payment-method');
    }
}

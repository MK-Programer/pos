<?php

namespace App\Livewire\Payment;

use App\Livewire\Widgets\Notifications\Notify;
use App\Models\PaymentMethod;
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

class CreatePaymentMethod extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Create Payment Method')
                    ->description('Fill in the information below to create payment method.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        Textarea::make('description')
                            ->nullable(),
                    ]),
            ])
            ->statePath('data')
            ->model(PaymentMethod::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = PaymentMethod::create($data);

        $this->form->model($record)->saveRelationships();
    
        Notify::send('Created successfully', "Payment Method {$record->name} has been created successfully");
    }

    public function render(): View
    {
        return view('livewire.payment.create-payment-method');
    }
}

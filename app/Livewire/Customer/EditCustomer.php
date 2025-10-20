<?php

namespace App\Livewire\Customer;

use App\Livewire\Widgets\Notifications\Notify;
use App\Models\Customer;
use Exception;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditCustomer extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Customer $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Customer')
                    ->description('Update the information below to edit this customer.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('email')
                            ->nullable()
                            ->email()
                            ->unique(ignoreRecord: true),

                        TextInput::make('phone')
                            ->nullable()
                            ->tel()
                            ->unique(ignoreRecord: true),
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

            Notify::send('Updated successfully', "Customer {$this->record->name} has been updated successfully");
        }catch(Exception $e){
            Notify::send('Update failure', "Failed to update Customer {$this->record->name}");
        }
    }

    public function render(): View
    {
        return view('livewire.customer.edit-customer');
    }
}

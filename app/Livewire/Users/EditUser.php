<?php

namespace App\Livewire\Users;

use App\Enums\NotificationType;
use App\Livewire\Widgets\Notifications\Notify;
use App\Models\User;
use Exception;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditUser extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public User $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit User')
                    ->description('Update the information below to edit this user.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required(),

                        Select::make('role')
                            ->options([
                                'cashier' => 'Cashier', 
                                'admin' => 'Admin',
                                'other' => 'Other',
                            ])
                            ->native(false)
                            ->required(),
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

            Notify::send('Updated successfully', "User {$this->record->name} has been updated successfully");
        }catch(Exception $e){
            Notify::send('Update failure', "Failed to update User {$this->record->name}", NotificationType::DANGER);
        }
    }

    public function render(): View
    {
        return view('livewire.users.edit-user');
    }
}

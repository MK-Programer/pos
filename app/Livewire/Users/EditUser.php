<?php

namespace App\Livewire\Users;

use App\Livewire\Widgets\Notifications\Notify;
use App\Models\User;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
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
        $data = $this->form->getState();

        $this->record->update($data);

        Notify::send('Updated successfully', "User {$this->record->name} has been updated successfully");
    }

    public function render(): View
    {
        return view('livewire.users.edit-user');
    }
}

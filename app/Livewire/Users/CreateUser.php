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

class CreateUser extends Component implements HasActions, HasSchemas
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
                Section::make('Create User')
                    ->description('Fill in the information below to create user.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('email')
                            ->email()
                            ->unique()
                            ->required(),

                        TextInput::make('password')
                            ->password()
                            ->revealable()
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
            ->model(User::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = User::create($data);

        $this->form->model($record)->saveRelationships();

        Notify::send('Created successfully', "User {$record->name} has been created successfully");
    }

    public function render(): View
    {
        return view('livewire.users.create-user');
    }
}

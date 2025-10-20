<?php

namespace App\Livewire\Items;

use App\Livewire\Widgets\Notifications\Notify;
use App\Models\Item;
use Exception;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class CreateItem extends Component implements HasActions, HasSchemas
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
                Section::make('Create Item')
                    ->description('Fill in the information below to create an item.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('sku')
                            ->unique()
                            ->required(),

                        TextInput::make('price')
                            ->prefix('$')
                            ->numeric()
                            ->required(),

                        ToggleButtons::make('status')
                            ->label('Is Active?')
                            ->options([
                                'active' => 'Yes',
                                'inactive' => 'No'
                            ])
                            ->grouped()
                            ->default('active'),
                    ]),
            ])
            ->statePath('data')
            ->model(Item::class);
    }

    public function create(): void
    {
        try{
            $data = $this->form->getState();

            $record = Item::create($data);

            $this->form->model($record)->saveRelationships();

            Notify::send('Created successfully', "Item {$record->name} has been created successfully");
        }catch(Exception $e){
            Notify::send('Creation failure', "Failed to create Item {$record->name}");
        }
    }

    public function render(): View
    {
        return view('livewire.items.create-item');
    }
}

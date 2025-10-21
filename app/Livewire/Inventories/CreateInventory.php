<?php

namespace App\Livewire\Inventories;

use App\Livewire\Widgets\Notifications\Notify;
use App\Models\Inventory;
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

class CreateInventory extends Component implements HasActions, HasSchemas
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
                Section::make('Create Inventory')
                    ->description('Fill in the information below to create an item inventory.')
                    ->columns(2)
                    ->schema([
                        Select::make('item_id')
                            ->relationship('item', 'name')
                            ->searchable()
                            ->preload()
                            ->native(false)
                            ->required(),
                            
                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->rules([
                                'gt:0'
                            ]),
                    ])
            ])
            ->statePath('data')
            ->model(Inventory::class);
    }

    public function create(): void
    {
        $data = $this->form->getState();

        $record = Inventory::create($data);

        $this->form->model($record)->saveRelationships();
        
        Notify::send('Created successfully', "Item {$record->item->name} inventory has been created successfully");
    }

    public function render(): View
    {
        return view('livewire.inventories.create-inventory');
    }
}

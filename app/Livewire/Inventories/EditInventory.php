<?php

namespace App\Livewire\Inventories;

use App\Enums\NotificationType;
use App\Livewire\Widgets\Notifications\Notify;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use App\Models\Inventory;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Livewire\Component;

class EditInventory extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Inventory $record;

    public ?array $data = [];

    public function mount(): void
    {
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Item Inventory')
                    ->description('Update the information below to edit this item inventory.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('item_name')
                            ->formatStateUsing(fn($record) => $record->item->name)
                            ->disabled()
                            ->dehydrated(false),
                            
                        TextInput::make('quantity')
                            ->numeric()
                            ->required(),
                    ])
            ])
            ->statePath('data')
            ->model($this->record);
    }

    public function save(): void
    {
       try{
            $data = $this->form->getState();

            $this->record->update($data);

            Notify::send('Updated successfully', "Inventory for {$this->record->item->name} has been updated successfully");
        }catch(Exception $e){
            Notify::send('Update failure', "Failed to update the Inventory of {$this->record->item->name}", NotificationType::DANGER);
        }
    }

    public function render(): View
    {
        return view('livewire.inventories.edit-inventory');
    }
}

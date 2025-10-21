<?php

namespace App\Livewire\Items;

use App\Enums\NotificationType;
use App\Livewire\Widgets\Notifications\Notify;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Illuminate\Contracts\View\View;
use App\Models\Item;
use Exception;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Livewire\Component;

class EditItem extends Component implements HasActions, HasSchemas
{
    use InteractsWithActions;
    use InteractsWithSchemas;

    public Item $record;

    public ?array $data = [];

    public function mount(): void
    {
        // it populate the default values from db
        $this->form->fill($this->record->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Edit Item')
                    ->description('Update the information below to edit this item.')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('sku')
                            ->unique(ignoreRecord: true)
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
                            ->grouped(),
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

            Notify::send('Updated successfully', "Item {$this->record->name} has been updated successfully");
        }catch(Exception $e){
            Notify::send('Update failure', "Failed to update Item {$this->record->name}", NotificationType::DANGER);
        }
    }

    public function render(): View
    {
        return view('livewire.items.edit-item');
    }
}

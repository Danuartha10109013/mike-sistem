<?php

namespace App\Filament\Resources\CommodityResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label('Number')
                    ->required()
                    ->readOnly()
                    ->unique(ignoreRecord: true)
                    ->maxLength(18)
                    ->minLength(18)
                    ->placeholder('Enter the number of the asset'),
                Forms\Components\TextInput::make('name')
                    ->label('Name')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Enter the name of the asset'),
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->required()
                    ->type('number')
                    ->placeholder('Enter the quantity of the asset'),
                Forms\Components\Select::make('brand_id')
                    ->label('Brand')
                    ->required()
                    ->options(Brand::pluck('name', 'id')->toArray())
                    ->placeholder('Select the category of the asset'),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->required()
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->placeholder('Select the category of the asset'),
                Forms\Components\Select::make('room_id')
                    ->label('Room')
                    ->required()
                    ->options(Room::pluck('name', 'id')->toArray())
                    ->placeholder('Select the room of the asset'),
                Forms\Components\Select::make('condition')
                    ->label('Condition')
                    ->required()
                    ->options([
                        'new' => 'New',
                        'used' => 'Used',
                        'damaged' => 'Damaged',
                    ])
                    ->placeholder('Select the condition of the asset'),
                Forms\Components\DatePicker::make('date')
                    ->label('Date')
                    ->required()
                    ->placeholder('Select the date of the asset'),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->required()
                    ->options(User::pluck('name', 'id')->toArray())
                    ->placeholder('Select the user of the asset'),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

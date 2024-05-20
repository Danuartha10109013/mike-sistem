<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditAsset extends EditRecord
{
    protected static string $resource = AssetResource::class;

    public static function getNavigationLabel(): string
    {
        return __('asset.navigation.edit_asset');
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label(__('asset.column.number'))
                    ->required()
                    ->readOnly()
                    ->unique(ignoreRecord: true)
                    ->maxLength(18)
                    ->minLength(18)
                    ->placeholder(__('asset.placeholder.number')),
                Forms\Components\TextInput::make('name')
                    ->label(__('asset.column.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('asset.placeholder.name')),
                Forms\Components\TextInput::make('quantity')
                    ->label(__('asset.column.quantity'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('asset.placeholder.quantity')),
                Forms\Components\Select::make('brand_id')
                    ->label(__('brand.title'))
                    ->required()
                    ->options(Brand::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.brand')),
                Forms\Components\Select::make('category_id')
                    ->label(__('category.title'))
                    ->required()
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.category')),
                Forms\Components\Select::make('room_id')
                    ->label(__('room.title'))
                    ->required()
                    ->options(Room::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.room')),
                Forms\Components\Select::make('condition')
                    ->label(__('asset.column.condition'))
                    ->required()
                    ->options([
                        'new' => 'New',
                        'used' => 'Used',
                        'damaged' => 'Damaged',
                    ])
                    ->placeholder(__('asset.placeholder.condition')),
                Forms\Components\DatePicker::make('date')
                    ->label(__('asset.column.date'))
                    ->required()
                    ->placeholder(__('asset.placeholder.date')),
                Forms\Components\Select::make('user_id')
                    ->label(__('asset.column.user'))
                    ->required()
                    ->options(User::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.user')),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return self::getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Asset Updated')
            ->body('The asset was updated successfully.');
    }
}

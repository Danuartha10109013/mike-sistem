<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use App\Models\Penyusutan;
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
                    ->default(1)
                    ->type('number')
                    ->hidden()
                    ->placeholder(__('asset.placeholder.quantity')),
                // Forms\Components\Select::make('penyusutan_id')
                //     ->label(__('Decrease'))
                //     ->required()
                //     ->searchable()
                //     ->options(
                //         Penyusutan::all()->mapWithKeys(function ($item) {
                //             return [$item->id => $item->nama_kelompok . ' - ' . $item->kelompok  . ' ('.$item->masa_manfaat.' Tahun)'];
                //         })
                //     )
                //     ->placeholder(__('Select Decrease')),
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
                    // Checkbox to determine if asset is held by user or in room
                    
                // Forms\Components\Checkbox::make('is_held_by_user')
                //     ->label(__('asset.column.held_by_user'))
                //     ->reactive()
                //     ->dehydrated(false)
                //     ->default(function ($get, $record) {
                //          // Jika sedang edit dan user_id terisi, return true
                //         if ($record && $record->user_id !== null) {
                //             return true;
                //         }
                //         // Jika tidak (room_id terisi atau keduanya null), return false
                //         return false;
                //     })
                //     ->afterStateUpdated(function ($set) {
                //         $set('user_id', null);
                //         $set('room_id', null);
                //     }),
                    
                // Forms\Components\Select::make('user_id')
                //     ->label(__('asset.column.user'))
                //     ->options(User::pluck('name', 'id')->toArray())
                //     ->placeholder(__('asset.placeholder.user'))
                //     ->hidden(fn ($get) => !$get('is_held_by_user'))
                //     ->required(fn ($get) => $get('is_held_by_user')),
                    
                // Forms\Components\Select::make('room_id')
                //     ->label(__('room.title'))
                //     ->options(Room::pluck('name', 'id')->toArray())
                //     ->placeholder(__('asset.placeholder.room'))
                //     ->hidden(fn ($get) => $get('is_held_by_user'))
                //     ->required(fn ($get) => !$get('is_held_by_user')),

                Forms\Components\Checkbox::make('is_held_by_user')
                ->label(__('asset.column.held_by_user'))
                ->reactive()
                ->dehydrated(false)
                ->afterStateUpdated(function ($state, $set) {
                    if ($state) {
                        $set('room_id', null);
                    } else {
                        $set('user_id', null);
                    }
                }),

                Forms\Components\Select::make('user_id')
                    ->label(__('asset.column.user'))
                    ->options(User::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.user'))
                    ->visible(fn ($get) => $get('is_held_by_user'))
                    ->required(fn ($get) => $get('is_held_by_user')),

                Forms\Components\Select::make('room_id')
                    ->label(__('room.title'))
                    ->options(Room::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.room'))
                    ->visible(fn ($get) => !$get('is_held_by_user'))
                    ->required(fn ($get) => !$get('is_held_by_user')),

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

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data['is_held_by_user'] = $data['user_id'] !== null;

        return $data;
    }
}

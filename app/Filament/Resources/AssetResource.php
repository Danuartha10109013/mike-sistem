<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use Exception;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-s-archive-box';

    protected static ?string $navigationGroup = 'Inventory';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label('Number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label('Room')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition')
                    ->badge()
                    ->label('Condition'),
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->multiple()
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->label('Category')
                    ->attribute('category_id')
                    ->searchable(),
                SelectFilter::make('room_id')
                    ->multiple()
                    ->options(Room::pluck('name', 'id')->toArray())
                    ->label('Room')
                    ->attribute('room_id')
                    ->searchable(),
                SelectFilter::make('brand_id')
                    ->multiple()
                    ->options(Brand::pluck('name', 'id')->toArray())
                    ->label('Brand')
                    ->attribute('brand_id')
                    ->searchable(),
                SelectFilter::make('status')
                    ->multiple()
                    ->options([
                        'new' => 'New',
                        'used' => 'Used',
                        'damaged' => 'Damaged',
                    ])
                    ->attribute('condition')
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->icon('heroicon-o-plus')
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Components\Section::make()->schema([
                Components\Split::make([
                    Components\Grid::make(2)->schema([
                        Components\Group::make([
                            Components\TextEntry::make('number')
                                ->label('Number'),
                            Components\TextEntry::make('name')
                                ->label('Name'),
                            Components\TextEntry::make('quantity')
                                ->label('Quantity'),
                            Components\TextEntry::make('brand.name')
                                ->label('Brand'),
                            Components\TextEntry::make('category.name')
                                ->label('Category'),
                        ]),
                        Components\Group::make([
                            Components\TextEntry::make('room.name')
                                ->label('Room'),
                            Components\TextEntry::make('condition')
                                ->label('Condition'),
                            Components\TextEntry::make('date')
                                ->label('Date'),
                            Components\TextEntry::make('user.name')
                                ->label('User'),
                            Components\TextEntry::make('created_at')
                                ->label('Created At'),
                        ])
                    ])
                ]),
            ])
                ->collapsible()
                ->description('View the details of the asset.')
        ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewAsset::class,
            Pages\EditAsset::class,
            Pages\ViewAssetMaintenances::class,
            Pages\ViewAssetPurchases::class,
        ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
            'view' => Pages\ViewAsset::route('/{record}'),
            'maintenances' => Pages\ViewAssetMaintenances::route('/{record}/maintenances'),
            'purchases' => Pages\ViewAssetPurchases::route('/{record}/purchases'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label('Number')
                    ->required()
                    ->readOnly()
                    ->unique()
                    ->maxLength(18)
                    ->minLength(18)
                    ->default(fn() => Asset::number())
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
                    ->searchable()
                    ->options(Brand::pluck('name', 'id')->toArray())
                    ->placeholder('Select the category of the asset'),
                Forms\Components\Select::make('category_id')
                    ->label('Category')
                    ->required()
                    ->searchable()
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->placeholder('Select the category of the asset'),
                Forms\Components\Select::make('room_id')
                    ->label('Room')
                    ->required()
                    ->searchable()
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
                    ->default(now())
                    ->required()
                    ->placeholder('Select the date of the asset'),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->required()
                    ->searchable()
                    ->default(fn() => auth()->id())
                    ->options(User::pluck('name', 'id')->toArray())
                    ->placeholder('Select the user of the asset'),
            ]);
    }
}

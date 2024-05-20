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

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation_group.inventory');
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label(__('asset.column.number'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('name')
                    ->label(__('asset.column.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('asset.column.quantity'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('asset.column.price'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label(__('brand.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('category.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label(__('room.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition')
                    ->badge()
                    ->label(__('asset.column.condition')),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('asset.column.date'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('asset.column.user'))
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->multiple()
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->label(__('category.title'))
                    ->attribute('category_id')
                    ->searchable(),
                SelectFilter::make('room_id')
                    ->multiple()
                    ->options(Room::pluck('name', 'id')->toArray())
                    ->label(__('room.title'))
                    ->attribute('room_id')
                    ->searchable(),
                SelectFilter::make('brand_id')
                    ->multiple()
                    ->options(Brand::pluck('name', 'id')->toArray())
                    ->label(__('brand.title'))
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
                                ->label(__('asset.column.number')),
                            Components\TextEntry::make('name')
                                ->label(__('asset.column.name')),
                            Components\TextEntry::make('quantity')
                                ->label(__('asset.column.quantity')),
                            Components\TextEntry::make('price')
                                ->label(__('asset.column.price')),
                            Components\TextEntry::make('brand.name')
                                ->label(__('brand.title')),
                            Components\TextEntry::make('category.name')
                                ->label(__('category.title')),
                        ]),
                        Components\Group::make([
                            Components\TextEntry::make('room.name')
                                ->label(__('room.title')),
                            Components\TextEntry::make('condition')
                                ->label(__('asset.column.condition')),
                            Components\TextEntry::make('date')
                                ->label(__('asset.column.date')),
                            Components\TextEntry::make('user.name')
                                ->label(__('asset.column.user')),
                            Components\TextEntry::make('created_at')
                                ->label(__('asset.column.created_at')),
                        ])
                    ])
                ]),
            ])
                ->collapsible()
                ->description(__('asset.infolist.description')),
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
                    ->label(__('asset.column.number'))
                    ->required()
                    ->readOnly()
                    ->unique()
                    ->maxLength(18)
                    ->minLength(18)
                    ->default(fn() => Asset::number())
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
                Forms\Components\TextInput::make('price')
                    ->label(__('asset.column.price'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('asset.placeholder.price')),
                Forms\Components\Select::make('brand_id')
                    ->label(__('brand.title'))
                    ->required()
                    ->searchable()
                    ->options(Brand::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.brand')),
                Forms\Components\Select::make('category_id')
                    ->label(__('category.title'))
                    ->required()
                    ->searchable()
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.category')),
                Forms\Components\Select::make('room_id')
                    ->label(__('room.title'))
                    ->required()
                    ->searchable()
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
                    ->default(now())
                    ->required()
                    ->placeholder(__('asset.placeholder.date')),
                Forms\Components\Select::make('user_id')
                    ->label(__('asset.column.user'))
                    ->required()
                    ->searchable()
                    ->default(fn() => auth()->id())
                    ->options(User::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.user')),
            ]);
    }
}

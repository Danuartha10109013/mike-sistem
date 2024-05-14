<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Asset;
use App\Models\Purchase;
use App\Models\User;
use App\PurchaseStatus;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    protected static ?string $navigationGroup = 'Inventory';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('asset_id')
                    ->label('Asset')
                    ->required()
                    ->options(fn() => Asset::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->placeholder('Select the asset'),
                DatePicker::make('submission_date')
                    ->label('Submission Date')
                    ->required()
                    ->placeholder('Select the submission date')
                    ->default(now()),
                TextInput::make('price')
                    ->label('Price')
                    ->required()
                    ->type('number')
                    ->placeholder('Enter the price'),
                TextInput::make('quantity')
                    ->label('Quantity')
                    ->required()
                    ->type('number')
                    ->placeholder('Enter the quantity'),
                TextInput::make('total')
                    ->label('Total')
                    ->required()
                    ->type('number')
                    ->placeholder('Enter the total'),
                Textarea::make('notes')
                    ->label('Notes')
                    ->nullable()
                    ->placeholder('Enter the notes'),
                Select::make('user_id')
                    ->label('User')
                    ->required()
                    ->options(fn() => User::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->default(fn() => auth()->id())
                    ->placeholder('Select the user'),
            ]);
    }

    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('asset.name')
                    ->label('Asset')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('submission_date')
                    ->label('Submission Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label('Total')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('asset_id')
                    ->label('Asset')
                    ->options(fn() => Asset::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->multiple()
                    ->placeholder('Show all'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(PurchaseStatus::class)
                    ->searchable()
                    ->multiple()
                    ->placeholder('Show all'),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
        ];
    }
}

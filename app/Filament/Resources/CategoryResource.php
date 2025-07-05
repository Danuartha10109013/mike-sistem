<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use App\Models\Category;
use App\Models\Penyusutan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function getNavigationGroup(): ?string
    {
        return __('navigation_group.data');
    }
    public static function canViewAny(): bool
    {
        return auth()->user()->isUser();
    }
    public static function getModelLabel(): string
    {
        return __('category.title');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label(__('category.column.name'))
                    ->placeholder(__('category.placeholder.name'))
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('penyusutan_id')
                    ->label(__('Decrease'))
                    ->required()
                    ->searchable()
                    ->options(
                        Penyusutan::all()->mapWithKeys(function ($item) {
                            return [$item->id => $item->nama_kelompok . ' - ' . $item->kelompok  . ' ('.$item->masa_manfaat.' Tahun)'];
                        })
                    )
                    ->placeholder(__('Select Decrease')),
                Forms\Components\Textarea::make('description')
                    ->label(__('category.column.description'))
                    ->placeholder(__('category.placeholder.description'))
                    ->rows(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('category.column.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('penyusutan')
                    ->label(__('Decrease'))
                    ->formatStateUsing(function ($record) {
                       
                        $garislurus = $record['penyusutan']['garis_lurus'];
                        $saldomenurun = $record['penyusutan']['saldo_menurun'];
                        $masa = $record['penyusutan']['masa_manfaat'];
                        

                        return $record['penyusutan']['nama_kelompok']. ' ('. $masa .' Tahun) <br> - '.$record['penyusutan']['kelompok'].', Garis Lurus ('. $garislurus .'%) <br> - '.$record['penyusutan']['kelompok'].', Saldo Menurun ('. $saldomenurun .'%)' 
                        ;
                    }) 
                    ->html()
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()
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
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}

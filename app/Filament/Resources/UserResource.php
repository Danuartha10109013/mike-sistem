<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use App\UserRole;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function getModelLabel(): string
    {
        return __('user.title');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('navigation_group.access');
    }


    public static function canViewAny(): bool
    {
        return auth()->user()->isUser();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('user.column.name'))
                    ->placeholder(__('user.placeholder.name'))
                    ->required(),
                TextInput::make('username')
                    ->label(__('user.column.username'))
                    ->placeholder(__('user.placeholder.username'))
                    ->required()
                    ->unique(),
                TextInput::make('password')
                    ->label(__('user.placeholder.password'))
                    ->placeholder(__('user.placeholder.password'))
                    ->password()
                    ->autocomplete('new-password')
                    ->required()
                    ->confirmed(),
                TextInput::make('password_confirmation')
                    ->label(__('user.placeholder.confirm_password'))
                    ->placeholder(__('user.placeholder.confirm_password'))
                    ->password()
                    ->autocomplete('new-password')
                    ->required()
                    ->dehydrated(false)
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label(__('user.column.name'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->label(__('user.column.username'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role')
                    ->label(__('user.column.role'))
                    ->badge()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('role')
                    ->options(UserRole::class)
                    ->label('Role'),
            ])
            ->actions([
                Tables\Actions\EditAction::make()->hidden(fn($record) => $record->id === auth()->id()),
                Tables\Actions\DeleteAction::make()->hidden(fn($record) => $record->id === auth()->id()),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    ExportBulkAction::make(),
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

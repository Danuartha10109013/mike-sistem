<?php

namespace App\Filament\Resources\PurchaseResource\Pages;

use App\Filament\Resources\PurchaseResource;
use App\Models\Asset;
use App\Models\User;
use Filament\Actions;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;

class EditPurchase extends EditRecord
{
    protected static string $resource = PurchaseResource::class;

    public function form(Form $form): Form
    {
        return $form->schema([
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

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

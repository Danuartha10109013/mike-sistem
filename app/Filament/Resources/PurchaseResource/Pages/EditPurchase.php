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
                ->disabled()
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
                ->placeholder('Enter the price')
                ->afterStateUpdated(fn (callable $set, $state, callable $get) =>
                    $set('total', (float) $state * (float) $get('quantity'))
                ),
            TextInput::make('quantity')
                ->label('Quantity')
                // ->required()
                ->type('number')
                ->placeholder('Enter the quantity')
                ->default(1)
                ->hidden()
                ->reactive()
                ->afterStateUpdated(fn (callable $set, $state, callable $get) =>
                    $set('total', (float) $state * (float) $get('price'))
                ),
            TextInput::make('total')
                ->label('Total')
                ->required()
                ->type('number')
                ->placeholder('Enter the total')
                ->hidden()
                ->readOnly(),

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
            Select::make('urgent')
                ->label(__('Tingkat Kepentingan'))
                ->required()
                ->options([
                    'Low' => 'Low',
                    'Mid' => 'Mid',
                    'Hight' => 'Hight',
                ])
                ->placeholder(__('Pilih tingkat kepentingan')),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

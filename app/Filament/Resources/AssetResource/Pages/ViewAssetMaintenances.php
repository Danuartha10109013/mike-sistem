<?php

namespace App\Filament\Resources\AssetResource\Pages;

use App\Filament\Resources\AssetResource;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;

class ViewAssetMaintenances extends ManageRelatedRecords
{
    protected static string $resource = AssetResource::class;

    protected static string $relationship = 'maintenances';

    protected static ?string $navigationIcon = 'heroicon-o-document';

    /**
     * @return string
     */
    public static function getNavigationLabel(): string
    {
        return 'Maintenances';
    }

    public function getTitle(): string|Htmlable
    {
        $recordTitle = $this->getRecordTitle();
        $recordTitle = $recordTitle instanceof Htmlable ? $recordTitle->toHtml() : $recordTitle;
        return "Viewing {$recordTitle} Maintenances";
    }

    public function getBreadcrumb(): string
    {
        return 'Maintenances';
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->label('Date')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('cost')
                    ->label('Cost')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
            ]);
    }
}

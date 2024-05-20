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
        return __('asset.navigation.maintenance_histories');
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
            ->description('A list of all approved purchases for this asset.')
            ->columns([
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
                Tables\Columns\TextColumn::make('total')
                    ->summarize(Tables\Columns\Summarizers\Sum::make())
                    ->label('Total'),
            ])
            ->filters([
                //
            ])
            ->actions([
//                Tables\Actions\ViewAction::make()
            ]);
    }
}

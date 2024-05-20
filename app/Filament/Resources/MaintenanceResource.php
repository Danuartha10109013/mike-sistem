<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MaintenanceResource\Pages;
use App\MaintenanceStatus;
use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\User;
use App\UserRole;
use Exception;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Grid;
use Filament\Infolists\Components\Group;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\Split;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MaintenanceResource extends Resource
{
    protected static ?string $model = Maintenance::class;

    protected static ?string $navigationIcon = 'iconpark-tool';

    public static function getNavigationGroup(): ?string
    {
        return __('navigation_group.inventory');
    }


    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()->role === UserRole::Admin) {
            return Maintenance::where('status', MaintenanceStatus::Pending)->count();
        }

        return null;
    }

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
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('asset_id')
                    ->label('Asset')
                    ->options(fn() => Asset::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->multiple()
                    ->placeholder('Show all'),
                Tables\Filters\SelectFilter::make('status')
                    ->label('Status')
                    ->options(MaintenanceStatus::class)
                    ->searchable()
                    ->multiple()
                    ->placeholder('Show all'),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    // Edit only for creators except for admins
                    Tables\Actions\EditAction::make()->hidden(fn(Maintenance $maintenance) => $maintenance->user_id !== auth()->id() && !auth()->user()->isAdmin()),
                    Tables\Actions\Action::make('Approve')
                        ->requiresConfirmation()
                        ->action(function (Maintenance $maintenance) {
                            $maintenance->approved();
                            Notification::make()->success()->title('Maintenance approved')->icon('heroicon-o-check-circle')->send();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->hidden(fn(Maintenance $maintenance) => !$maintenance->isPending() || !auth()->user()->isAdmin()),
                    Tables\Actions\Action::make('Reject')
                        ->requiresConfirmation()
                        ->action(function (Maintenance $maintenance) {
                            $maintenance->status = MaintenanceStatus::Rejected;
                            $maintenance->rejected_by = auth()->id();
                            $maintenance->rejection_date = now();
                            $maintenance->save();
                            Notification::make()->success()->title('Maintenance rejected')->icon('heroicon-o-x-circle')->send();
                        })
                        ->icon('heroicon-o-x-circle')
                        ->hidden(fn(Maintenance $maintenance) => !$maintenance->isPending() || !auth()->user()->isAdmin()),
                    Tables\Actions\Action::make('Complete')
                        ->requiresConfirmation()
                        ->action(function (Maintenance $maintenance) {
                            $maintenance->status = MaintenanceStatus::Completed;
                            $maintenance->completed_by = auth()->id();
                            $maintenance->completion_date = now();
                            $maintenance->save();
                            Notification::make()->success()->title('Maintenance completed')->icon('heroicon-o-check-circle')->send();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->hidden(fn(Maintenance $maintenance) => !$maintenance->isApproved() || !auth()->user()->isAdmin()),

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

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Section::make()->schema([
                Split::make([
                    Grid::make()->schema([
                        Group::make([
                            TextEntry::make('asset.name')
                                ->label('Asset'),
                            TextEntry::make('submission_date')
                                ->label('Submission Date'),
                            TextEntry::make('price')
                                ->label('Price'),
                            TextEntry::make('quantity')
                                ->label('Quantity'),
                            TextEntry::make('total')
                                ->label('Total'),
                        ])
                    ]),
                    Grid::make()->schema([
                        Group::make([
                            TextEntry::make('user.name')
                                ->label('User'),
                            TextEntry::make('status')
                                ->badge(),
                            TextEntry::make('notes')
                                ->label('Notes'),
                            TextEntry::make('approvedBy.name')
                                ->label('Approved By')
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isApproved()),
                            TextEntry::make('approval_date')
                                ->label('Approval Date')
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isApproved()),
                            TextEntry::make('rejectedBy.name')
                                ->label('Rejected By')
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isRejected()),
                            TextEntry::make('rejection_date')
                                ->label('Rejection Date')
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isRejected()),
                            TextEntry::make('completedBy.name')
                                ->label('Completed By')
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isCompleted()),
                            TextEntry::make('completion_date')
                                ->label('Completion Date')
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isCompleted()),
                        ])

                    ])
                ])
            ])
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
            'index' => Pages\ListMaintenances::route('/'),
            'create' => Pages\CreateMaintenance::route('/create'),
            'edit' => Pages\EditMaintenance::route('/{record}/edit'),
            'view' => Pages\ViewMaintenance::route('/{record}'),
        ];
    }
}

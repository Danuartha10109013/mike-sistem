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

    public static function getModelLabel(): string
    {
        return __('maintenance.title');
    }

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
                    ->label(__('asset.title'))
                    ->required()
                    ->options(fn() => Asset::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->placeholder(__('maintenance.placeholder.asset')),
                DatePicker::make('submission_date')
                    ->label(__('maintenance.column.submission_date'))
                    ->required()
                    ->placeholder(__('maintenance.placeholder.submission_date'))
                    ->default(now()),
                TextInput::make('price')
                    ->label(__('maintenance.column.price'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('maintenance.placeholder.price')),
                TextInput::make('quantity')
                    ->label(__('maintenance.column.quantity'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('maintenance.placeholder.quantity')),
                TextInput::make('total')
                    ->label(__('maintenance.column.total'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('maintenance.placeholder.total')),
                Textarea::make('notes')
                    ->label(__('maintenance.column.notes'))
                    ->nullable()
                    ->placeholder(__('maintenance.placeholder.notes')),
                Select::make('user_id')
                    ->label(__('user.title'))
                    ->required()
                    ->options(fn() => User::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->default(fn() => auth()->id())
                    ->placeholder(__('maintenance.placeholder.user')),
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
                    ->label(__('asset.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('submission_date')
                    ->label(__('maintenance.column.submission_date'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('maintenance.column.price'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('maintenance.column.quantity'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('maintenance.column.total'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('user.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('maintenance.column.status'))
                    ->badge()
            ])->defaultSort('created_at', 'desc')
            ->filters([
                Tables\Filters\SelectFilter::make('asset_id')
                    ->label(__('asset.title'))
                    ->options(fn() => Asset::pluck('name', 'id')->toArray())
                    ->searchable()
                    ->multiple()
                    ->placeholder('Show all'),
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('maintenance.column.status'))
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
                                ->label(__('asset.title')),
                            TextEntry::make('submission_date')
                                ->label(__('maintenance.column.submission_date')),
                            TextEntry::make('price')
                                ->label(__('maintenance.column.price')),
                            TextEntry::make('quantity')
                                ->label(__('maintenance.column.quantity')),
                            TextEntry::make('total')
                                ->label(__('maintenance.column.total')),
                        ])
                    ]),
                    Grid::make()->schema([
                        Group::make([
                            TextEntry::make('user.name')
                                ->label(__('user.title')),
                            TextEntry::make('status')
                                ->label(__('maintenance.column.status'))
                                ->badge(),
                            TextEntry::make('notes')
                                ->label(__('maintenance.column.notes')),
                            TextEntry::make('approvedBy.name')
                                ->label(__('maintenance.column.approved_by'))
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isApproved()),
                            TextEntry::make('approval_date')
                                ->label(__('maintenance.column.approved_date'))
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isApproved()),
                            TextEntry::make('rejectedBy.name')
                                ->label(__('maintenance.column.rejected_by'))
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isRejected()),
                            TextEntry::make('rejection_date')
                                ->label(__('maintenance.column.rejected_date'))
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isRejected()),
                            TextEntry::make('completedBy.name')
                                ->label(__('maintenance.column.completed_by'))
                                ->hidden(fn(Maintenance $maintenance) => !$maintenance->isCompleted()),
                            TextEntry::make('completion_date')
                                ->label(__('maintenance.column.completion_date'))
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

<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PurchaseExporter;
use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Models\Asset;
use App\Models\Purchase;
use App\Models\User;
use App\PurchaseStatus;
use App\UserRole;
use Exception;
use Filament\Actions\Exports\Enums\ExportFormat;
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
use Filament\Tables\Actions\ExportAction;
use Filament\Tables\Table;

class PurchaseResource extends Resource
{
    protected static ?string $model = Purchase::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function getNavigationGroup(): ?string
    {
        return __('navigation_group.inventory');
    }


    public static function getModelLabel(): string
    {
        return __('purchase.title');
    }

    public static function getNavigationBadge(): ?string
    {
        if (auth()->user()->role === UserRole::Admin) {
            return Purchase::where('status', PurchaseStatus::Pending)->count();
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
                    ->label(__('purchase.column.submission_date'))
                    ->required()
                    ->placeholder(__('maintenance.placeholder.submission_date'))
                    ->default(now()),
                TextInput::make('price')
                    ->label(__('purchase.column.price'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('maintenance.placeholder.price')),
                TextInput::make('quantity')
                    ->label(__('purchase.column.quantity'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('maintenance.placeholder.quantity')),
                TextInput::make('total')
                    ->label(__('purchase.column.total'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('maintenance.placeholder.total')),
                Textarea::make('notes')
                    ->label(__('purchase.column.notes'))
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
                    ->label(__('purchase.column.submission_date'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('purchase.column.price'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity')
                    ->label(__('purchase.column.quantity'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total')
                    ->label(__('purchase.column.total'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('user.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('purchase.column.status'))
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
                    ->label(__('purchase.column.status'))
                    ->options(PurchaseStatus::class)
                    ->searchable()
                    ->multiple()
                    ->placeholder('Show all'),
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    // Edit only for creators except for admins
                    Tables\Actions\EditAction::make()->hidden(fn(Purchase $purchase) => $purchase->user_id !== auth()->id() && !auth()->user()->isAdmin()),
                    Tables\Actions\Action::make('Approve')
                        ->requiresConfirmation()
                        ->action(function (Purchase $purchase) {
                            $purchase->approved();
                            Notification::make()->success()->title('Purchase approved')->icon('heroicon-o-check-circle')->send();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->hidden(fn(Purchase $purchase) => !$purchase->isPending() || !auth()->user()->isAdmin()),
                    Tables\Actions\Action::make('Reject')
                        ->requiresConfirmation()
                        ->action(function (Purchase $purchase) {
                            $purchase->status = PurchaseStatus::Rejected;
                            $purchase->rejected_by = auth()->id();
                            $purchase->rejection_date = now();
                            $purchase->save();
                            Notification::make()->success()->title('Purchase rejected')->icon('heroicon-o-x-circle')->send();
                        })
                        ->icon('heroicon-o-x-circle')
                        ->hidden(fn(Purchase $purchase) => !$purchase->isPending() || !auth()->user()->isAdmin()),
                    Tables\Actions\Action::make('Complete')
                        ->requiresConfirmation()
                        ->action(function (Purchase $purchase) {
                            $purchase->status = PurchaseStatus::Completed;
                            $purchase->completed_by = auth()->id();
                            $purchase->completion_date = now();
                            $purchase->save();
                            Notification::make()->success()->title('Purchase completed')->icon('heroicon-o-check-circle')->send();
                        })
                        ->icon('heroicon-o-check-circle')
                        ->hidden(fn(Purchase $purchase) => !$purchase->isApproved() || !auth()->user()->isAdmin()),

                ]),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                ExportAction::make()
                    ->exporter(PurchaseExporter::class)
                    ->formats([
                        ExportFormat::Xlsx
                    ])
                    ->fileDisk('public')
                    ->fileName('purchase_report.xlsx')
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
                                ->hidden(fn(Purchase $purchase) => !$purchase->isApproved()),
                            TextEntry::make('approval_date')
                                ->label('Approval Date')
                                ->hidden(fn(Purchase $purchase) => !$purchase->isApproved()),
                            TextEntry::make('rejectedBy.name')
                                ->label('Rejected By')
                                ->hidden(fn(Purchase $purchase) => !$purchase->isRejected()),
                            TextEntry::make('rejection_date')
                                ->label('Rejection Date')
                                ->hidden(fn(Purchase $purchase) => !$purchase->isRejected()),
                            TextEntry::make('completedBy.name')
                                ->label('Completed By')
                                ->hidden(fn(Purchase $purchase) => !$purchase->isCompleted()),
                            TextEntry::make('completion_date')
                                ->label('Completion Date')
                                ->hidden(fn(Purchase $purchase) => !$purchase->isCompleted()),
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
            'index' => Pages\ListPurchases::route('/'),
            'create' => Pages\CreatePurchase::route('/create'),
            'edit' => Pages\EditPurchase::route('/{record}/edit'),
            'view' => Pages\ViewPurchase::route('/{record}'),
        ];
    }
}

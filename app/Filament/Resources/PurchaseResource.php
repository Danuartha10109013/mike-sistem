<?php

namespace App\Filament\Resources;

use App\Filament\Exports\PurchaseExporter;
use App\Filament\Resources\PurchaseResource\Pages;
use App\Filament\Resources\PurchaseResource\RelationManagers;
use App\Filament\Resources\AssetResource;

use App\Models\Asset;
use App\Models\Purchase;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use App\Models\Penyusutan;

use App\PurchaseStatus;
use App\UserRole;
use Exception;
use Filament\Actions\Exports\Enums\ExportFormat;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Checkbox;
// use Filament\Forms\Components\Group;
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

use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;


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

    public static function canCreate(): bool
    {
        return !auth()->user()->isAdmin();
    }

    public static function form(Form $form): Form
    {

        return $form
            ->schema([
                // Checkbox::make('create_new_asset')
                //     ->label('Input data aset baru jika belum tersedia')
                //     ->hidden()
                //     ->default('checked')
                //     ->reactive(),
                \Filament\Forms\Components\Group::make([
                    TextInput::make('asset_name')
                            ->label(__('asset.column.name'))
                            ->required()
                            ->placeholder(__('asset.placeholder.name')),
                    Select::make('brand_id')
                            ->label(__('brand.title'))
                            ->required()
                            ->options(Brand::pluck('name', 'id'))->placeholder(__('asset.placeholder.brand')),
                    Select::make('category_id')
                            ->label(__('category.title'))
                            ->required()
                            ->options(Category::pluck('name', 'id'))->placeholder(__('asset.placeholder.category')),
                    // Select::make('penyusutan_id')
                    //         ->label(__('Decrease'))
                    //         ->required()
                    //         ->searchable()
                    //         ->options(
                    //             Penyusutan::all()->mapWithKeys(function ($item) {
                    //                 return [$item->id => $item->nama_kelompok . ' - ' . $item->kelompok  . ' ('.$item->masa_manfaat.' Tahun)'];
                    //             })
                    //         )
                    //         ->placeholder(__('Select Decrease')),
                    // Checkbox to determine if asset is held by user or in room
                    Checkbox::make('is_held_by_user')
                            ->label(__('asset.column.held_by_user'))
                            ->reactive()
                            ->dehydrated(false)
                            ->default(function ($get, $record) {
                                // Jika sedang edit dan user_id terisi, return true
                                if ($record && $record->user_id) {
                                    return true;
                                }else {
                                    return false;
                                }
                    })
                    ->afterStateUpdated(function ($set) {
                        $set('user_id', null);
                        $set('room_id', null);
                    }),
                    // User select - only shown when is_held_by_user is true
                    Select::make('user_id')
                            ->label(__('asset.column.user'))
                            ->searchable()
                            ->options(User::pluck('name', 'id')->toArray())
                            ->placeholder(__('asset.placeholder.user'))
                            ->hidden(fn ($get) => !$get('is_held_by_user'))
                            ->required(fn ($get) => $get('is_held_by_user')),
                    
                    // Room select - only shown when is_held_by_user is false
                    Select::make('room_id')
                            ->label(__('room.title'))
                            ->options(Room::pluck('name', 'id'))
                            ->placeholder(__('asset.placeholder.room'))
                            ->hidden(fn ($get) => $get('is_held_by_user'))
                            ->required(fn ($get) => !$get('is_held_by_user')),
                    

                ])
                ->columnSpanFull(),


                // Select::make('asset_id')
                //     ->label(__('asset.title'))
                //     ->required(fn (callable $get) => !$get('create_new_asset'))
                //     ->options(Asset::pluck('name', 'id'))
                //     ->searchable()
                //     ->hidden(),

                DatePicker::make('submission_date')
                    ->label(__('purchase.column.submission_date'))
                    ->required()
                    ->default(now()),

                TextInput::make('price')
                    ->label(__('purchase.column.price'))
                    ->required()
                    ->numeric()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, $state, callable $get) =>
                        $set('total', (float) $state * (float) $get('quantity'))
                    ),

                TextInput::make('quantity')
                    ->label(__('purchase.column.quantity'))
                    ->required()
                    ->default(1)
                    ->numeric()
                    ->hidden()
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set, $state, callable $get) =>
                        $set('total', (float) $state * (float) $get('price'))
                    ),

                TextInput::make('total')
                    ->label(__('purchase.column.total'))
                    ->required()
                    ->numeric()
                    ->hidden()
                    ->readOnly(),

                Textarea::make('notes')
                    ->label(__('purchase.column.notes'))
                    ->nullable(),

                Select::make('user_id')
                    ->label(__('user.title'))
                    // ->required()
                    ->options(User::pluck('name', 'id')->toArray())
                    ->default(fn () => auth()->id())
                    ->searchable(),
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
                    ->formatStateUsing(function ($record) {
                        return 'Rp ' . number_format($record->price, 0, ',', '.');
                    })
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('quantity')
                //     ->label(__('purchase.column.quantity'))
                //     ->searchable()
                //     ->sortable(),
                // Tables\Columns\TextColumn::make('total')
                //     ->label(__('purchase.column.total'))
                //     ->searchable()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('user.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label(__('purchase.column.status'))
                    ->badge(),
                Tables\Columns\TextColumn::make('urgent')
                    ->label(__('Tingkat Kepentingan'))
                    ->badge(),
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
                Tables\Actions\Action::make('Approve')
                ->requiresConfirmation()
                ->action(function (Purchase $purchase) {
                    $purchase->approved();
                    Notification::make()->success()->title('Submission Asset approved')->icon('heroicon-o-check-circle')->send();
                })
                ->icon('heroicon-o-check-circle')
                ->hidden(fn(Purchase $purchase) => !$purchase->isPending() || !auth()->user()->isAdmin()),
                Tables\Actions\Action::make('Reject')
                ->requiresConfirmation()
                ->form([
                    Textarea::make('notes_rejected')
                        ->label('Rejection Reason')
                        ->required()
                        ->maxLength(255),
                ])
                ->action(function (Purchase $purchase, array $data) {
                    $purchase->status = PurchaseStatus::Rejected;
                    $purchase->rejected_by = auth()->id();
                    $purchase->rejection_date = now();
                    $purchase->notes_rejected = $data['notes_rejected'];
                    $purchase->save();
                    Notification::make()->success()->title('Submission Asset rejected')->icon('heroicon-o-x-circle')->send();
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

                    if ($purchase->asset && is_numeric($purchase->quantity)) {
                        $purchase->asset->increment('quantity', (int) $purchase->quantity);
                    }


                    Notification::make()->success()->title('Submission Asset completed')->icon('heroicon-o-check-circle')->send();


                })
                ->icon('heroicon-o-check-circle')
                ->hidden(fn(Purchase $purchase) => !$purchase->isApproved() || auth()->user()->isEmployee()),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    // Edit only for creators except for admins
                    Tables\Actions\EditAction::make()->hidden(fn(Purchase $purchase) => auth()->user()->isEmployee()),
                ]),

                   
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            // ->headerActions([
            //     ExportAction::make()
            //         ->exporter(PurchaseExporter::class)
            //         ->formats([
            //             ExportFormat::Xlsx
            //         ])
            //         ->fileDisk('public')
            //         ->fileName('purchase_report.xlsx')
            // ])
            ->headerActions([
                Action::make('download')
                    ->label('Download Excel')
                    ->action(fn () => Excel::download(new PurchaseExporter, 'purchase_report.xlsx')),
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
                                ->label('Price')
                                ->formatStateUsing(function ($record) {
                                    return 'Rp ' . number_format($record->price, 0, ',', '.');
                                }),
                            TextEntry::make('urgent')
                            ->label('Tingkat Kepentingan'),
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
                            TextEntry::make('notes_rejected')
                                ->label('Notes Rejected')
                                ->hidden(fn(Purchase $purchase) => !$purchase->isRejected()),
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

<?php

namespace App\Filament\Resources;

use App\Filament\Exports\AssetExporter;
use App\Filament\Resources\AssetResource\Pages;
use App\Filament\Resources\AssetResource\RelationManagers;
use App\Models\Asset;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Room;
use App\Models\User;
use App\Models\Penyusutan;
use App\Models\Maintenance;
use Exception;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Components;
use Filament\Infolists\Infolist;
use Filament\Notifications\Notification;
use Filament\Pages\SubNavigationPosition;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

use Filament\Tables\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

use Carbon\Carbon;

class AssetResource extends Resource
{
    protected static ?string $model = Asset::class;

    protected static ?string $navigationIcon = 'heroicon-s-archive-box';

    protected static SubNavigationPosition $subNavigationPosition = SubNavigationPosition::Top;

    public static function getNavigationGroup(): ?string
    {
        return __('navigation_group.inventory');
    }
    public static function canCreate(): bool
    {
        return !auth()->user()->isAdmin();
    }
    /**
     * @throws Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')
                    ->label(__('asset.column.number'))
                    ->getStateUsing(function (Asset $record) {
                      
                
                        $text = $record->number;
                
                        if ($record->amnesty == 1) {
                            $text .= ' <br><b style="color:red">(Amnesty)</b>';
                        }
                
                        return $text;
                    })->html()
                    ->searchable()
                    ->sortable(),
            
                Tables\Columns\TextColumn::make('name')
                    ->label(__('asset.column.name'))
                    ->searchable()
                    ->sortable(),
                // Tables\Columns\TextColumn::make('quantity')
                //     ->label(__('asset.column.quantity'))
                //     ->searchable()
                //     ->sortable(),
                Tables\Columns\TextColumn::make('price')
                    ->label(__('asset.column.price'))
                    ->formatStateUsing(function ($record) {
                        return 'Rp ' . number_format($record->price, 0, ',', '.');
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.penyusutan.nama_kelompok')
                    ->label(__('Decrease'))
                    ->formatStateUsing(function ($record) {
                        if (!$record->category->penyusutan) {
                            return '';
                        }
                        
                        $time = Carbon::now()->diff($record->date);
                        $year = 1;
                        if($time->y > 0) $year = $time->y;
                        $penyusutan = $record->category->penyusutan;
                       
                        $garislurus = $penyusutan['garis_lurus'];
                        $saldomenurun = $penyusutan['saldo_menurun'];
                        $masa = $penyusutan['masa_manfaat'];
                        $price = $record->price;

                        $garislurusPercent = $garislurus / 100;
                        $price1 = $price * $garislurusPercent * $year;
                        $finalPrice1 = $price - $price1 ;
                        
                        $saldomenurunPercent = $saldomenurun / 100;
                        $price2 = $price * $saldomenurunPercent * $year;
                        $finalPrice2 = $price - $price2 ;

                        return $penyusutan['nama_kelompok']. ' ('. $masa .' Tahun) <br> - '.$penyusutan['kelompok'].', Garis Lurus ('. $garislurus .'%) <br> Harga Jual: Rp ' . number_format($finalPrice1, 0, ',', '.') . ' <br> - '.$penyusutan['kelompok'].', Saldo Menurun ('. $saldomenurun .'%) <br> Harga Jual: Rp ' . number_format($finalPrice2, 0, ',', '.') ;
                    }) 
                    ->html()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('date')
                    ->label(__('asset.column.date'))
                    ->formatStateUsing(function ($record) {

                        $time = Carbon::now()->diff($record->date);
                        return  '(Usia: '.$time->y. 'Tahun) '.$record->date;
                    })
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('condition')
                    ->label(__('asset.column.condition'))
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('brand.name')
                    ->label(__('brand.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('category.name')
                    ->label(__('category.title'))
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('room.name')
                    ->label(__('room.title'))
                    ->default('-')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('user.name')
                    ->label(__('asset.column.user'))
                    ->default('-')
                    ->searchable()
                    ->sortable(),
            ])->defaultSort('updated_at', 'desc')
            ->filters([
               
            SelectFilter::make('category_id')
                ->multiple()
                ->options(Category::pluck('name', 'id')->toArray())
                ->label(__('category.title'))
                ->attribute('category_id')
                ->searchable(),
            SelectFilter::make('room_id')
                ->multiple()
                ->options(Room::pluck('name', 'id')->toArray())
                ->label(__('room.title'))
                ->attribute('room_id')
                ->searchable(),
            // SelectFilter::make('brand_id')
            //     ->multiple()
            //     ->options(Brand::pluck('name', 'id')->toArray())
            //     ->label(__('brand.title'))
            //     ->attribute('brand_id')
            //     ->searchable(),
            SelectFilter::make('condition')
                ->multiple()
                ->options([
                    'new' => 'New',
                    'used' => 'Used',
                    'damaged' => 'Damaged',
                ])
                ->attribute('condition'),
            SelectFilter::make('amnesty')
                ->multiple()
                ->options([
                    '0' => 'Not Amnesty',
                    '1' => 'Amnesty',
                ])
                ->attribute('amnesty'),
            \Filament\Tables\Filters\Filter::make('date_range')
                ->label('Tanggal')
                ->form([
                    \Filament\Forms\Components\DatePicker::make('date_from')
                        ->label('Dari Tanggal'),
                    \Filament\Forms\Components\DatePicker::make('date_until')
                        ->label('Sampai Tanggal'),
                ])
                // ->columns(2) // ⬅️ Ini membuat 2 kolom horizontal
                ->query(function ($query, array $data) {
                    return $query
                        ->when($data['date_from'], fn ($q, $date) => $q->whereDate('date', '>=', $date))
                        ->when($data['date_until'], fn ($q, $date) => $q->whereDate('date', '<=', $date));
                }),
               
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                Tables\Actions\Action::make('submit maintenance')
                    ->requiresConfirmation()
                    ->form([

                        TextInput::make('price')
                            ->type('number')
                            ->label(__('asset.column.price')),
                        Textarea::make('notes')
                            ->label('Detail Maintenance')
                            ->required()
                            ->maxLength(255),
                    ])
                    ->action(function (Maintenance $maintenance, array $data, Asset $record) {
                    
                        $assetId = $record->id;
                        
                        $maintenance->asset_id = $assetId;
                        $maintenance->user_id = auth()->id();
                        $maintenance->quantity = 1;
                        $maintenance->price = $data['price'] ?? 0;
                        $maintenance->total = $data['price'] ?? 0;
                        $maintenance->submission_date = now() ;
                        $maintenance->status = 'pending'  ;
                        $maintenance->notes = $data['notes'] ?? null;
                        $maintenance->save();
                    
                        Notification::make()->success()->title('Submission Maintenace Sended')->icon('heroicon-o-check-circle')->send();
                    })
                    ->icon('iconpark-send')
                    ->visible(function (Asset $record) {
                        // tombol hanya tampil jika tidak ada pending maintenance
                        if($record->condition->value === 'damaged' ){
                              
                            $maintenance = !\App\Models\Maintenance::where('asset_id', $record->id)
                                ->where('status', 'pending')
                                ->exists();
                            if($maintenance){
                                return true;
                            }else{
                                return false;

                            }
                        }else{
                            return false;
                        }
                    }),
                Tables\Actions\Action::make('Asset Amnesty')
                    ->requiresConfirmation()
                    ->action(function ( Asset $record) {
                    
                        $record->amnesty = '1';
                        $record->save();
                        
                        Notification::make()->success()->title('Asset Amnesty Successfully')->icon('heroicon-o-check-circle')->send();
                    })
                    ->icon('iconpark-box')
                    ->visible(fn(Asset $record) => $record->amnesty == '0'),
                    
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    // Tables\Actions\DeleteAction::make(),
                    Tables\Actions\ViewAction::make(),
                ])

            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
             ->headerActions([
                Action::make('download')
                    ->label('Download Excel')
                    ->action(fn () => Excel::download(new AssetExporter, 'asset_report.xlsx')),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make()->icon('heroicon-o-plus')
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Components\Section::make()->schema([
                Components\Split::make([
                    Components\Grid::make(2)->schema([
                        Components\Group::make([
                            Components\TextEntry::make('number')
                                ->label(__('asset.column.number')),
                            Components\TextEntry::make('name')
                                ->label(__('asset.column.name')),
                            // Components\TextEntry::make('quantity')
                            //     ->label(__('asset.column.quantity')),
                            Components\TextEntry::make('price')
                                ->label(__('asset.column.price')),
                            Components\TextEntry::make('brand.name')
                                ->label(__('brand.title')),
                            Components\TextEntry::make('category.name')
                                ->label(__('category.title')),
                        ]),
                        Components\Group::make([
                            
                            Components\TextEntry::make('condition')
                                ->label(__('asset.column.condition')),
                            Components\TextEntry::make('category.penyusutan.nama_kelompok')
                            ->label(__('Decrease'))
                            ->formatStateUsing(function ($record) {
                                if (!$record->category->penyusutan) {
                                    return '';
                                }
                                
                                $time = Carbon::now()->diff($record->date);
                                $year = 1;
                                if($time->y > 0) $year = $time->y;
                                $penyusutan = $record->category->penyusutan;
                               
                                $garislurus = $penyusutan['garis_lurus'];
                                $saldomenurun = $penyusutan['saldo_menurun'];
                                $masa = $penyusutan['masa_manfaat'];
                                $price = $record->price;
        
                                $garislurusPercent = $garislurus / 100;
                                $price1 = $price * $garislurusPercent * $year;
                                $finalPrice1 = $price - $price1 ;
                                
                                $saldomenurunPercent = $saldomenurun / 100;
                                $price2 = $price * $saldomenurunPercent * $year;
                                $finalPrice2 = $price - $price2 ;
        
                                return $penyusutan['nama_kelompok']. ' ('. $masa .' Tahun) <br> - '.$penyusutan['kelompok'].', Garis Lurus ('. $garislurus .'%) <br> Harga Jual: Rp ' . number_format($finalPrice1, 0, ',', '.') . ' <br> - '.$penyusutan['kelompok'].', Saldo Menurun ('. $saldomenurun .'%) <br> Harga Jual: Rp ' . number_format($finalPrice2, 0, ',', '.') ;
                            }) 
                            ->html(),

                            Components\TextEntry::make('date')
                                ->label(__('asset.column.date'))
                                ->formatStateUsing(function ($record) {

                                    $time = Carbon::now()->diff($record->date);
                                    return $record->date . ' (Usia: '.$time->y. 'Tahun) ';
                                }),
                            Components\TextEntry::make('user.name')
                                ->default('-')
                                ->label(__('asset.column.user')),
                            Components\TextEntry::make('room.name')
                                ->default('-')
                                ->label(__('room.title')),
                            // Components\TextEntry::make('created_at')
                            //     ->label(__('asset.column.created_at')),
                        ])
                    ])
                ]),
            ])
                ->collapsible()
                ->description(__('asset.infolist.description')),
        ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return $page->generateNavigationItems([
            Pages\ViewAsset::class,
            // Pages\EditAsset::class,
            Pages\ViewAssetMaintenances::class,
            Pages\ViewAssetPurchases::class,
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
            'index' => Pages\ListAssets::route('/'),
            'create' => Pages\CreateAsset::route('/create'),
            'edit' => Pages\EditAsset::route('/{record}/edit'),
            'view' => Pages\ViewAsset::route('/{record}'),
            'maintenances' => Pages\ViewAssetMaintenances::route('/{record}/maintenances'),
            'purchases' => Pages\ViewAssetPurchases::route('/{record}/purchases'),
        ];
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('number')
                    ->label(__('asset.column.number'))
                    ->required()
                    ->readOnly()
                    ->unique()
                    ->maxLength(18)
                    ->minLength(18)
                    ->default(fn() => Asset::number())
                    ->placeholder(__('asset.placeholder.number')),
                Forms\Components\TextInput::make('name')
                    ->label(__('asset.column.name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('asset.placeholder.name')),
                Forms\Components\TextInput::make('quantity')
                    ->label(__('asset.column.quantity'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('asset.placeholder.quantity')),
                Forms\Components\TextInput::make('price')
                    ->label(__('asset.column.price'))
                    ->required()
                    ->type('number')
                    ->placeholder(__('asset.placeholder.price')),
                // Forms\Components\Select::make('penyusutan_id')
                //     ->label(__('Decrease'))
                //     ->required()
                //     ->searchable()
                //     ->options(
                //         Penyusutan::all()->mapWithKeys(function ($item) {
                //             return [$item->id => $item->nama_kelompok . ' - ' . $item->kelompok  . ' ('.$item->masa_manfaat.' Tahun)'];
                //         })
                //     )
                //     ->placeholder(__('Select Decrease')),
                Forms\Components\Select::make('brand_id')
                    ->label(__('brand.title'))
                    ->required()
                    ->searchable()
                    ->options(Brand::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.brand')),
                Forms\Components\Select::make('category_id')
                    ->label(__('category.title'))
                    ->required()
                    ->searchable()
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.category')),
              
                Forms\Components\Select::make('condition')
                    ->label(__('asset.column.condition'))
                    ->required()
                    ->options([
                        'new' => 'New',
                        'used' => 'Used',
                        'damaged' => 'Damaged',
                    ])
                    ->placeholder(__('asset.placeholder.condition')),
                Forms\Components\DatePicker::make('date')
                    ->label(__('asset.column.date'))
                    ->default(now())
                    ->required()
                    ->placeholder(__('asset.placeholder.date')),
                // Checkbox to determine if asset is held by user or in room
                Forms\Components\Checkbox::make('is_held_by_user')
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
                        // Default untuk create form
                        return false;
                    })
                    ->afterStateUpdated(function ($set) {
                        $set('user_id', null);
                        $set('room_id', null);
                    }),
                // User select - only shown when is_held_by_user is true
                Forms\Components\Select::make('user_id')
                    ->label(__('asset.column.user'))
                    ->searchable()
                    ->options(User::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.user'))
                    ->hidden(fn ($get) => !$get('is_held_by_user'))
                    ->required(fn ($get) => $get('is_held_by_user')),
                    
                // Room select - only shown when is_held_by_user is false
                Forms\Components\Select::make('room_id')
                    ->label(__('room.title'))
                    ->searchable()
                    ->options(Room::pluck('name', 'id')->toArray())
                    ->placeholder(__('asset.placeholder.room'))
                    ->hidden(fn ($get) => $get('is_held_by_user'))
                    ->required(fn ($get) => !$get('is_held_by_user')),
            ]);
    }
}

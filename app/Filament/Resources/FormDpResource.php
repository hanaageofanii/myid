<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormDpResource\Pages;
use App\Models\form_dp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use App\Models\form_kpr;
use App\Models\FormKpr;
use Filament\Tables;
use App\Filament\Resources\AuditResource\RelationManagers;
use App\Models\Audit;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use App\Filament\Resources\KPRStats;
use illuminate\Support\Facades\Auth;




class FormDpResource extends Resource
{
    protected static ?string $model = form_dp::class;

    protected static ?string $title = "Data Uang Muka GCV";
    protected static ?string $navigationGroup = "Keuangan";
    protected static ?string $pluralLabel = "Data Uang Muka GCV";
    protected static ?string $navigationLabel = "Uang Muka GCV";
    protected static ?string $pluralModelLabel = 'Daftar Uang Muka GCV';
    protected static ?string $navigationIcon = 'heroicon-o-folder-arrow-down';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
            ->schema([
                Select::make('siteplan')
                    ->label('Site Plan')
                    ->options(fn () => form_kpr::pluck('siteplan', 'siteplan')) 
                    ->searchable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $data = form_kpr::where('siteplan', $state)->first(); 
                            if ($data) {
                                $set('nama_konsumen', $data->nama_konsumen);
                                $set('harga', $data->harga);
                                $set('max_kpr', $data->maksimal_kpr);
                            }
                        }
                    }),
        
                TextInput::make('nama_konsumen')
                    ->label('Nama Konsumen')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->dehydrated(),
        
                TextInput::make('harga')
                    ->label('Harga')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->prefix('Rp')
                    ->dehydrated(),
        
                TextInput::make('max_kpr')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Kasir 1']);
                })())
                    ->label('Maksimal KPR')
                    // ->prefix('Rp')
                    ->dehydrated(),
            ]),        

            Fieldset::make('Pembayaran')
            ->schema([
                TextInput::make('sbum')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->label('SBUM')
                    ->prefix('Rp')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $harga = $get('harga') ?? 0;
                        $max_kpr = $get('max_kpr') ?? 0;
                        $sbum = $state ?? 0;

                        $sisa_pembayaran = max(0, $harga - $max_kpr - $sbum);
                        $set('sisa_pembayaran', $sisa_pembayaran);

                        $dp = $get('dp') ?? 0;
                        $set('laba_rugi', $dp - $sisa_pembayaran);
                    }),

                TextInput::make('sisa_pembayaran')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->reactive()
                    ->prefix('Rp')
                    ->label('Sisa Pembayaran'),

                TextInput::make('dp')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->prefix('Rp')
                    ->label('Uang Muka (DP)')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $sisa_pembayaran = $get('sisa_pembayaran') ?? 0;
                        
                        $set('laba_rugi', ($state ?? 0) - $sisa_pembayaran);
                    }),

                TextInput::make('laba_rugi')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->reactive()
                    ->prefix('Rp')
                    ->label('Laba Rugi'),

                DatePicker::make('tanggal_terima_dp')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->label('Tanggal Terima Uang Muka'),

                Select::make('pembayaran')
                    ->options([
                        'cash' => 'Cash',
                        'potong_komisi' => 'Potong Komisi',
                        'promo' => 'Promo',
                    ])
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                    ->required()
                    ->label('Pembayaran'),
            ]),


            Fieldset::make('Dokumen')
                ->schema([
                    FileUpload::make('up_kwitansi')->disk('public')
                    ->nullable()->label('Kwitansi')->multiple()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                        ->downloadable()->previewable(false),
                    FileUpload::make('up_pricelist')->disk('public')
                    ->nullable()->label('Price List')->multiple()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                        ->downloadable()->previewable(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siteplan')->searchable()->label('Blok'),
                TextColumn::make('nama_konsumen')->searchable()->label('Nama Konsumen'),
                TextColumn::make('harga')
                ->searchable()
                ->label('Harga')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),                
                
                TextColumn::make('max_kpr')
                ->searchable()
                ->label('Max KPR')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

                TextColumn::make('sbum')
                    ->searchable()
                    ->label('SBUM')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

                TextColumn::make('sisa_pembayaran')
                    ->searchable()
                    ->label('Sisa Pembayaran')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
                
                TextColumn::make('dp')
                    ->searchable()
                    ->label('Uang Muka')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
                
                TextColumn::make('laba_rugi')
                    ->searchable()
                    ->label('Laba Rugi Uang Muka')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

                TextColumn::make('tanggal_terima_dp')
                    ->searchable()
                    ->label('Tanggal Terima DP')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                TextColumn::make('pembayaran')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                            'cash' => 'Cash',
                            'potong_komisi' => 'Potong Komisi',
                            'promo' => 'Promo',
                        default => ucfirst($state), 
                    })
                    ->sortable()
                    ->searchable()
                    ->label('Pembayaran'),

                TextColumn::make('up_kwitansi')
                ->label('Kwitansi')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_kwitansi) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_kwitansi) ? $record->up_kwitansi : json_decode($record->up_kwitansi, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $files = [];
                    }

                    $output = '';
                    foreach ($files as $file) {
                        $url = Storage::url($file);
                        $output .= '<a href="' . $url . '" target="_blank">Lihat</a> | <a href="' . $url . '" download>Download</a><br>';
                    }

                    return $output ?: 'Tidak Ada Dokumen';
                })
                ->html()
                ->sortable(),

                 TextColumn::make('up_pricelist')
                ->label('Price List')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_pricelist) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_pricelist) ? $record->up_pricelist : json_decode($record->up_pricelist, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $files = [];
                    }

                    $output = '';
                    foreach ($files as $file) {
                        $url = Storage::url($file);
                        $output .= '<a href="' . $url . '" target="_blank">Lihat</a> | <a href="' . $url . '" download>Download</a><br>';
                    }

                    return $output ?: 'Tidak Ada Dokumen';
                })
                ->html()
                ->sortable(),
            ])
            ->defaultSort('siteplan', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                TrashedFilter::make()
                ->label('Data yang dihapus') 
                ->native(false),

                Filter::make('pembayaran')
                    ->label('Pembayaran')
                    ->form([
                        Select::make('pembayaran')
                            ->options([
                                'cash' => 'Cash',
                                'potong_komisi' => 'Potong Komisi',
                                'promo' => 'Promo',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['pembayaran']), fn ($q) =>
                            $q->where('pembayaran', $data['pembayaran'])
                        )
                    ),
                    Filter::make('created_from')
                    ->label('Dari Tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari')
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_from'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '>=', $data['created_from'])
                        )
                    ),
                
                Filter::make('created_until')
                    ->label('Sampai Tanggal')
                    ->form([
                        DatePicker::make('created_until')
                            ->label('Sampai')
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_until'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '<=', $data['created_until'])
                        )
                    ),                
            ], layout: FiltersLayout::AboveContent)
            ->filtersFormMaxHeight('400px')
            ->filtersFormColumns(4)
            ->filtersFormWidth(MaxWidth::FourExtraLarge)
                                    
            ->actions([
                ActionGroup::make([
                    ViewAction::make()
                        ->color('success')
                        ->label('Lihat'),
                    EditAction::make()
                        ->color('info')
                        ->label('Ubah')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Uang Muka Diubah')
                                ->body('Data Uang Muka telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Uang Muka Dihapus')
                                ->body('Data Uang Muka telah berhasil dihapus.')),                            
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Uang')
                            ->body('Data Uang berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Uang')
                            ->body('Data Uang berhasil dihapus secara permanen.')
                    ),
                    ])->button()->label('Action'),
                ], position: ActionsPosition::BeforeCells)
            
                ->groupedBulkActions([
                    BulkAction::make('delete')
                        ->label('Hapus')
                        ->icon('heroicon-o-trash') 
                        ->color('danger')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Uang')
                                ->body('Data Uang berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Uang')
                                ->body('Data Uang berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
                
                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray') 
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),
                
                    RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path') 
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Uang Muka')
                                ->body('Data Uang Muka berhasil dikembalikan.')),
                ]);
                
    }
    public static function exportData(Collection $records)
    {
        $csvData = "ID, Blok, Nama Konsumen, harga, Maksimal KPR, SBUM, Sisa Pembayaran, DP, Laba Rugi, Tanggal Terima DP, Pembayaran\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->nama_konsumen}, {$record->harga}, {$record->max_kpr}, {$record->sbum}, {$record->sisa_pembayaran}, {$record->dp}, {$record->laba_rugi}, {$record->tanggal_terima_dp}, {$record->pembayaran}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'DP.csv');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormDps::route('/'),
            'create' => Pages\CreateFormDp::route('/create'),
            'edit' => Pages\EditFormDp::route('/{record}/edit'),
        ];
    }
}

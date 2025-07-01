<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvUangMukaResource\Pages;
use App\Filament\Resources\GcvUangMukaResource\RelationManagers;
use App\Models\gcv_uang_muka;
use App\Models\GcvUangMuka;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\gcv_stok;
use App\Models\gcvDataSiteplan;
use App\Models\gcv_legalitas;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use App\Models\form_kpr;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Validation\Rule;
use App\Models\gcv_datatandaterima;
use Carbon\Carbon;
use App\Models\gcv_kpr;
use App\Models\gcv_pencairan_akad;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use App\Filament\Resources\GcvUangMukaResource\Widgets\gcv_uang_MukaStats;


class GcvUangMukaResource extends Resource
{
    protected static ?string $model = gcv_uang_muka::class;

protected static ?string $title = "Data Uang Muka";
    protected static ?string $navigationGroup = "GCV";
    protected static ?int $navigationSort = 8;
    protected static ?string $pluralLabel = "Data Uang Muka";
    protected static ?string $navigationLabel = "Keuangan > Data Uang Muka";
    protected static ?string $pluralModelLabel = 'Daftar Uang Muka';
    protected static ?string $navigationIcon = 'heroicon-o-folder-arrow-down';
    public static function form(Form $form): Form
    {
        return $form->schema([
            Wizard::make([
                Step::make('Data Proyek')
                    ->columns(2)
                    ->description('Informasi data proyek')
                    ->schema([
                        Select::make('kavling')
                            ->label('Kavling')
                            ->options([
                                'standar' => 'Standar',
                                'khusus' => 'Khusus',
                                'hook' => 'Hook',
                                'komersil' => 'Komersil',
                                'tanah_lebih' => 'Tanah Lebih',
                                'kios' => 'Kios',
                            ])
                            ->required()
                            ->reactive()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),

                        Select::make('siteplan')
                            ->label('Blok')
                            ->searchable()
                            ->reactive()
                            ->options(function (callable $get) {
                                $selectedKavling = $get('kavling');
                                if (! $selectedKavling) return [];

                                return gcv_kpr::where('jenis_unit', $selectedKavling)
                                    ->where('status_akad', 'akad')
                                    ->pluck('siteplan', 'siteplan')
                                    ->toArray();
                            })
                                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })())
                            ->afterStateUpdated(function ($state, callable $set) {
                                if (! $state) return;

                                $kprData = gcv_kpr::where('siteplan', $state)->first();

                                if ($kprData) {
                                    $set('nama_konsumen', $kprData->nama_konsumen);
                                    $set('harga', $kprData->harga);
                                    $set('max_kpr', $kprData->maksimal_kpr);
                                } else {
                                    $set('nama_konsumen', null);
                                    $set('harga', null);
                                    $set('max_kpr', null);
                                }
                            }),
                    ]),

                Step::make('Data Konsumen')
                    ->columns(3)
                    ->description('Informasi konsumen')
                    ->schema([
                        TextInput::make('nama_konsumen')
                            ->label('Nama Konsumen')
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })())
                            ->dehydrated(),

                        TextInput::make('harga')
                            ->label('Harga')
                            ->prefix('Rp')
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })())
                            ->dehydrated(),

                        TextInput::make('max_kpr')
                            ->label('Maksimal KPR')
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })())
                            ->dehydrated(),
                    ]),

                    Step::make('Data Uang Muka')
                    ->columns(3)
                    ->description('Informasi Uang Muka')
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
                Step::make('Upload Dokumen')
                    ->columns(2)
                    ->description('Informasi Dokumen')
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
            ])->columnSpanFull(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kavling')
                ->label('Kavling')
                ->formatStateUsing(fn(string $state): string => match ($state){
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state,
                })->searchable(),

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

                Filter::make('kavling')
                    ->label('Jenis Unit')
                    ->form([
                        Select::make('kavling')
                            ->options([
                                'standar' => 'Standar',
                                'khusus' => 'Khusus',
                                'hook' => 'Hook',
                                'komersil' => 'Komersil',
                                'tanah_lebih' => 'Tanah Lebih',
                                'kios' => 'Kios',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['kavling']), fn ($q) =>
                            $q->where('kavling', $data['kavling'])
                        )
                    ),

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
                            ->displayFormat('Y-m-d'),
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
                            ->displayFormat('Y-m-d'),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when($data['created_until'] ?? null, fn ($q) =>
                            $q->whereDate('created_at', '<=', $data['created_until'])
                        )
                    ),

            ], layout: FiltersLayout::AboveContent)
            ->filtersFormMaxHeight('400px')
            ->filtersFormColumns(5)
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
                            ->title('Data Uang Muka')
                            ->body('Data Uang Muka berhasil dihapus secara permanen.')
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
                                ->title('Data Uang Muka')
                                ->body('Data Uang Muka berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Uang Muka')
                                ->body('Data Uang Muka berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
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
        $csvData = "ID, Kavling,Blok, Nama Konsumen, harga, Maksimal KPR, SBUM, Sisa Pembayaran, DP, Laba Rugi, Tanggal Terima DP, Pembayaran\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->kavling}, {$record->siteplan}, {$record->nama_konsumen}, {$record->harga}, {$record->max_kpr}, {$record->sbum}, {$record->sisa_pembayaran}, {$record->dp}, {$record->laba_rugi}, {$record->tanggal_terima_dp}, {$record->pembayaran}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'UangMuka.csv');
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
            'index' => Pages\ListGcvUangMukas::route('/'),
            'create' => Pages\CreateGcvUangMuka::route('/create'),
            'edit' => Pages\EditGcvUangMuka::route('/{record}/edit'),
        ];
    }
}
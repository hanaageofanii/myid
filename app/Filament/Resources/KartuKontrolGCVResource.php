<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartuKontrolGCVResource\Pages;
use App\Models\Kartu_kontrolGCV;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\gcv_kpr;
use Filament\Forms\Components\FileUpload;
use Closure;

use App\Filament\Resources\GcvUangMukaResource\RelationManagers;
use App\Models\gcv_uang_muka;
use App\Models\GcvUangMuka;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\gcv_stok;
use App\Models\gcvDataSiteplan;
use App\Models\gcv_legalitas;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\Filter;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use App\Models\form_kpr;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Validation\Rule;
use App\Models\gcv_datatandaterima;
use Carbon\Carbon;
use App\Models\gcv_pencairan_akad;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use App\Filament\Resources\GcvUangMukaResource\Widgets\gcv_uang_MukaStats;



class KartuKontrolGCVResource extends Resource
{
    protected static ?string $model = Kartu_kontrolGCV::class;

    protected static ?string $title = "Form Kartu Kontrol";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Kartu Kontrol";
    protected static ?string $navigationLabel = "Keuangan > Kartu Kontrol";
    protected static ?string $pluralModelLabel = 'Kartu Kontrol';
    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-ripple';
    protected static ?int $navigationSort = 18;

    public static function form(Form $form): Form
    {
        return $form
            ->columns(1)
            ->extraAttributes(['class' => 'centered-container'])
            ->schema([
                Wizard::make()
                    ->steps([

                        Wizard\Step::make('Informasi')
                            ->description('Data Proyek')
                            ->schema([
                                Section::make('Informasi')
                                    ->columns(2)
                                    ->schema([
                                    Select::make('proyek')
                                    ->label('Proyek')
                                    ->options([
                                        'gcv_cira' => 'GCV Cira',
                                        'gcv' => 'GCV',
                                        'tkr' => 'TKR',
                                        'tkr_cira' => 'TKR Cira',
                                        'pca1' => 'PCA1',
                                    ])
                                    ->required()
                                    ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal officer']);
                                    })()),

                                TextInput::make('lokasi_proyek')
                                    ->label('Lokasi Proyek')
                                    ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal officer']);
                                    })()),

                                    ]),
                            ]),

                        Wizard\Step::make('Detail Konsumen')
                            ->description('Informasi Alamat Konsumen')
                            ->schema([
                                Section::make('Detail Konsumen')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('nama_konsumen')
                                            ->label('Nama Konsumen')
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('no_telepon')
                                            ->label('No. Handphone')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        Textarea::make('alamat')
                                            ->label('Alamat')
                                            ->required()
                                            ->columnSpanFull()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                    ]),
                            ]),

                        Wizard\Step::make('Detail Unit')
                            ->description('Informasi Siteplan Konsumen')
                            ->schema([
                                Section::make('Detail Unit')
                                    ->columns(2)
                                    ->schema([
                                        Select::make('nama_perusahaan')
                                            ->label('Nama Perusahaan')
                                            ->options([
                                                'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                                                'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                                                'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                                            ])
                                            ->reactive()
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
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
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        Select::make('siteplan')
                                            ->label('Blok')
                                            ->searchable()
                                            ->required()
                                            ->reactive()
                                            ->options(function (callable $get) {
                                                $selectedKavling = $get('kavling');
                                                if (! $selectedKavling) {
                                                    return [];
                                                }
                                                return gcv_kpr::where('jenis_unit', $selectedKavling)
                                                    ->where('status_akad', 'akad')
                                                    ->pluck('siteplan', 'siteplan')
                                                    ->toArray();
                                            })
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('type')
                                            ->label('Type')
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('luas')
                                            ->label('Luas')
                                            ->numeric()
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        DatePicker::make('tanggal_booking')
                                            ->label('Tanggal Booking')
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('agent')
                                            ->label('Agent')
                                            ->required()
                                            ->columnSpanFull()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                    ]),
                            ]),

                        Wizard\Step::make('Detail Akad')
                            ->description('Informasi Pembiayaan & Akad')
                            ->schema([
                                Section::make('Informasi Akad & Pembiayaan')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('bank')
                                            ->label('Bank')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('notaris')
                                            ->label('Notaris')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        DatePicker::make('tanggal_akad')
                                            ->label('Tanggal Akad')
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('harga_jual')
                                            ->label('Harga Jual (Rp)')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('harga_m')
                                            ->label('Harga per m²')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('pajak')
                                            ->label('Pajak')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('biaya_proses')
                                            ->label('Biaya Proses')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('uang_muka')
                                            ->label('Kartu Kontrol')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('estimasi_kpr')
                                            ->label('Estimasi KPR')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('realisasi_kpr')
                                            ->label('Realisasi KPR')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('selisih_kpr')
                                            ->label('Selisih KPR')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('sbum_disct')
                                            ->label('SBUM')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('diskon')
                                            ->label('Diskon')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('biaya_lain')
                                            ->label('Biaya Lainnya')
                                            ->reactive()
                                            ->placeholder('Tuliskan detail biaya lain di sini...')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),

                                            TextInput::make('total_biaya')
                                            ->label('Total Biaya')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->reactive()
                                            ->columnSpanFull()
                                            ->afterStateUpdated(fn ($state, $set, $get) =>
                                                $set('total_biaya',
                                                    ($get('harga_jual') ?? 0) +
                                                    ($get('harga_m') ?? 0) +
                                                    ($get('pajak') ?? 0) +
                                                    ($get('biaya_proses') ?? 0) +
                                                    ($get('uang_muka') ?? 0) +
                                                    ($get('estimasi_kpr') ?? 0) +
                                                    ($get('realisasi_kpr') ?? 0) +
                                                    ($get('selisih_kpr') ?? 0) +
                                                    ($get('sbum_disct') ?? 0) +
                                                    ($get('biaya_lain') ?? 0)
                                                )
                                            )
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                    ]),
                            ]),
                            Wizard\Step::make('Detail Pembayaran')
                            ->description('Informasi Pembayaran Konsumen')
                            ->schema([
                                Section::make('Informasi Pembayaran')
                                    ->columns(2)
                                    ->schema([
                                        TextInput::make('no_konsumen')
                                            ->label('No Konsumen')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('keterangan')
                                            ->label('Keterangan')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        DatePicker::make('tanggal_pembayaran')
                                            ->label('Tanggal Pembayaran')
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('nilai_kontrak')
                                            ->label('Nilai Kontrak')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->required()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('pembayaran')
                                            ->label('Pembayaran')
                                            ->numeric()
                                            ->reactive()
                                            ->prefix('Rp')
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('sisa_saldo')
                                            ->label('Sisa Saldo')
                                            ->numeric()
                                            ->prefix('Rp')
                                            ->reactive() // Agar otomatis update saat field lain berubah
                                            ->afterStateUpdated(function ($state, $set, $get) {
                                                $set('sisa_saldo', ($get('nilai_kontrak') ?? 0) - ($get('pembayaran') ?? 0));
                                            })
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('paraf')
                                            ->label('Paraf')
                                            ->reactive()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                        TextInput::make('catatan')
                                            ->label('Catatan')
                                            ->reactive()
                                            ->disabled(fn () => ! (function () {
                                                /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),
                                         FileUpload::make('bukti_lainnya')->label('Bukti Lainnya')->disk('public')->columnSpanFull()->nullable()->previewable(false)->downloadable()->disabled(fn () => ! (function () {
                                            /** @var \App\Models\User|null $user */
                                                $user = Auth::user();
                                                return $user && $user->hasRole(['admin', 'Kasir 1', 'Kasir 2']);
                                            })()),

                                    ]),
                            ]),

                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                Tables\Columns\TextColumn::make('proyek')
    ->label('Proyek')
    ->sortable()
    ->searchable()
    ->formatStateUsing(fn ($state) => match($state) {
        'gcv_cira' => 'GCV Cira',
        'gcv' => 'GCV',
        'tkr' => 'TKR',
        'tkr_cira' => 'TKR Cira',
        'pca1' => 'PCA1',
        default => $state,
    }),


            Tables\Columns\TextColumn::make('lokasi_proyek')
                ->label('Lokasi Proyek')
                ->sortable()
                ->searchable(),

                Tables\Columns\TextColumn::make('nama_konsumen')
                ->label('Nama Konsumen')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('tanggal_pembayaran')
                ->label('Tanggal Pembayaran')
                ->date()
                ->sortable(),

                Tables\Columns\TextColumn::make('pembayaran')
                    ->searchable()
                    ->label('Pembayaran')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),


                Tables\Columns\TextColumn::make('nilai_kontrak')
                    ->searchable()
                    ->label('Nilai Kontrak')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('sisa_saldo')
                    ->searchable()
                    ->label('Sisa Saldo')
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

                TextColumn::make('bukti_lainnya')
                ->label('File Lainnyas')
                ->formatStateUsing(function ($record) {
                    if (!$record->bukti_lainnya) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->bukti_lainnya) ? $record->bukti_lainnya : json_decode($record->bukti_lainnya, true);

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

                    Filter::make('nama_proyek')
                    ->label('Nama Proyek')
                    ->form([
                        Select::make('nama_proyek')
                            ->options([
                                        'gcv_cira' => 'GCV Cira',
                                        'gcv' => 'GCV',
                                        'tkr' => 'TKR',
                                        'tkr_cira' => 'TKR Cira',
                                        'pca1' => 'PCA1',

                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['nama_proyek']), fn ($q) =>
                            $q->where('nama_proyek', $data['nama_proyek'])
                        )
                    ),

                    Filter::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->form([
                        Select::make('nama_perusahaan')
                            ->options([
                                'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                                'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                                'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['nama_perusahaan']), fn ($q) =>
                            $q->where('nama_perusahaan', $data['nama_perusahaan'])
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
            ->filtersFormColumns(3)
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
                                ->title('Data Kartu Kontrol Diubah')
                                ->body('Data Kartu Kontrol telah berhasil disimpan.')),
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Kartu Kontrol Dihapus')
                                ->body('Data Kartu Kontrol telah berhasil dihapus.')),
                    RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Kartu Kontrol')
                            ->body('Data Kartu Kontrol berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Kartu Kontrol')
                            ->body('Data Kartu Kontrol berhasil dihapus secara permanen.')
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
                                ->title('Data Kartu Kontrol')
                                ->body('Data Kartu Kontrol berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Kartu Kontrol')
                                ->body('Data Kartu Kontrol berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),

                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray')
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),

                    BulkAction::make('print')
                    ->label('Print Data')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function (Collection $records) {
                        session(['print_records' => $records->pluck('id')->toArray()]);

                        return redirect()->route('kartukontrol.print');
                    }),

                    RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Kartu Kontrol')
                                ->body('Data Kartu Kontrol berhasil dikembalikan.')),
                ]);

    }
    public static function exportData(Collection $records)
    {
        $csvData = "ID, Nama Perusahaan, Proyek, Lokasi Proyek, Nama Konsumen, No. Telepon, Alamat, Kavling, Siteplan, Type, Luas, Tanggal Booking, Agent, Bank, Notaris, Tanggal Akad, Harga Jual, Harga/m², Pajak, Biaya Proses, Uang Muka, Estimasi KPR, Realisasi KPR, Selisih KPR, SBUM/Discount, Biaya Lain, Total Biaya, No Konsumen, Tanggal Pembayaran, Keterangan, Nilai Kontrak, Pembayaran, Sisa Saldo, Paraf, Catatan, Bukti Lainnya, Status\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->nama_perusahaan}, {$record->proyek}, {$record->lokasi_proyek}, {$record->nama_konsumen}, {$record->no_telepon}, {$record->alamat}, {$record->kavling}, {$record->siteplan}, {$record->type}, {$record->luas}, {$record->tanggal_booking}, {$record->agent}, {$record->bank}, {$record->notaris}, {$record->tanggal_akad}, {$record->harga_jual}, {$record->harga_m}, {$record->pajak}, {$record->biaya_proses}, {$record->uang_muka}, {$record->estimasi_kpr}, {$record->realisasi_kpr}, {$record->selisih_kpr}, {$record->sbum_disct}, {$record->biaya_lain}, {$record->total_biaya}, {$record->no_konsumen}, {$record->tanggal_pembayaran}, {$record->keterangan}, {$record->nilai_kontrak}, {$record->pembayaran}, {$record->sisa_saldo}, {$record->paraf}, {$record->catatan}, {$record->bukti_lainnya}, {$record->status}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'KartuKontrolGCV.csv');
    }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKartuKontrolGCVS::route('/'),
            'create' => Pages\CreateKartuKontrolGCV::route('/create'),
            'edit' => Pages\EditKartuKontrolGCV::route('/{record}/edit'),
        ];
    }
}
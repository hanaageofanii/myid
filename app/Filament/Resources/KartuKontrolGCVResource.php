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
                                            ->label('Uang Muka')
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
            ->filters([
                // Tambahkan filter jika perlu
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            // Tambahkan RelationManagers jika ada
        ];
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
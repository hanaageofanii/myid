<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormPajakResource\Pages;
use App\Filament\Resources\FormPajakResource\RelationManagers;
use App\Models\form_kpr;
use App\Models\form_legal;
use App\Models\form_pajak;
use App\Models\FormPajak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\FormLegal;
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class FormPajakResource extends Resource
{
    protected static ?string $model = form_pajak::class;

    protected static ?string $title = "Form Validasi PPH";

    protected static ?string $navigationGroup = "Legal - Pajak";

    protected static ?string $pluralLabel = "Data Validasi PPH";

    protected static ?string $navigationLabel = "Validasi PPH";

    protected static ?string $pluralModelLabel = 'Daftar Validasi PPH';
    protected static ?string $navigationIcon = 'heroicon-o-document-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('siteplan')
                ->label('Blok')
                ->nullable()
                ->options(fn() => form_kpr::pluck('siteplan', 'siteplan')->toArray())
                ->reactive()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->afterStateUpdated(function ($state, callable $set) {
                    $kprData = form_kpr::where('siteplan', $state)->first();
                    if ($kprData) {
                        $set('kavling', $kprData->jenis_unit);
                        $set('nama_konsumen', $kprData->nama_konsumen);
                        $set('nik', $kprData->nik);
                        $set('npwp', $kprData->npwp);
                        $set('alamat', $kprData->alamat);
                        $set('harga', $kprData->harga);
                        $set('pembayaran', $kprData->pembayaran);
            
                        // Hitung NPOPTKP
                        $npoptkp = (int) $kprData->harga >= 80000000 ? 80000000 : 0;
                        $set('npoptkp', $npoptkp);
            
                        // Hitung BPHTB (5% dari harga - NPOPTKP)
                        $set('jumlah_bphtb', max(0.05 * ($kprData->harga - $npoptkp), 0));
            
                        // Tentukan Tarif PPH
                        $tarif_pph = ($kprData->jenis_unit === 'standar' && $kprData->pembayaran === 'kpr') ? 0.01 : 0.025;
                        $set('tarif_pph', ($tarif_pph * 100) . '%'); 
            
                        // Hitung Jumlah PPH
                        $jumlah_pph = max(($kprData->harga * $tarif_pph), 0);
                        $set('jumlah_pph', $jumlah_pph);
                    }
            

                        $legalData = form_legal::where('siteplan', $state)->first();
                        if ($legalData) {
                            $set('no_sertifikat', $legalData->no_sertifikat);
                            $set('nop', $legalData->nop);
                            $set('luas_tanah', $legalData->luas_sertifikat);
                        }
                    }),

            Forms\Components\TextInput::make('no_sertifikat')
            ->nullable()->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal Pajak']);
            })())
            ->label('No. Sertifikat'),

            Forms\Components\Select::make('kavling')
                    ->options([
                        'standar' => 'Standar',
                        'khusus' => 'Khusus',
                        'hook' => 'Hook',
                        'komersil' => 'Komersil',
                        'tanah_lebih' => 'Tanah Lebih',
                        'kios' => 'Kios',
                    ])
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->required()
                    ->reactive()
                    ->label('Jenis Unit'),
                    
                Forms\Components\TextInput::make('nama_konsumen')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Nama Konsumen'),

                Forms\Components\TextInput::make('nik')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('NIK'),
                Forms\Components\TextInput::make('npwp')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('NPWP'),

                Forms\Components\Textarea::make('alamat')
                ->nullable()->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Alamat'),

                Forms\Components\TextInput::make('nop')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('NOP'),

                Forms\Components\TextInput::make('luas_tanah')
                ->nullable()
                ->label('Luas Sertifikat')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })()),

                Forms\Components\TextInput::make('harga')
                ->numeric()
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Harga')
                ->reactive()
                ->prefix('Rp')
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $npoptkp = $state >= 80000000 ? 80000000 : 0;
                    $set('npoptkp', $npoptkp);
                    $set('jumlah_bphtb', max(0.05 * ($state - $npoptkp), 0));
                }),

                Forms\Components\TextInput::make('npoptkp')
                    ->numeric()
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('NPOPTKP')
                    ->reactive()
                    ->prefix('Rp')
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $harga = $get('harga');
                        $set('jumlah_bphtb', max(0.05 * ($harga - $state), 0));
                    }),

                Forms\Components\TextInput::make('jumlah_bphtb')
                ->numeric()
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Jumlah BPHTB')
                ->prefix('Rp'),

                Forms\Components\Select::make('tarif_pph')
                ->label('Tarif PPH')
                ->options([
                    '1' => '1 %',
                    '2.5' => '2.5 %',
                ])
                ->required()
                ->reactive()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->afterStateUpdated(function (callable $set, callable $get) {
                    $harga = (float) str_replace(['.', ','], ['', ''], $get('harga') ?? '0');
            
                    $tarif_pph_raw = $get('tarif_pph') ?? '0';
                    $tarif_pph = (float) $tarif_pph_raw / 100;
                    
                    if (is_numeric($harga) && is_numeric($tarif_pph)) {
                        $jumlah_pph = $harga * $tarif_pph;
                    } else {
                        $jumlah_pph = 0;
                    }
            
                    $set('jumlah_pph', $jumlah_pph);
                }),

                Forms\Components\TextInput::make('jumlah_pph')
                ->numeric()
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Jumlah PPH')                
                ->prefix('Rp'),
                
                Forms\Components\TextInput::make('kode_billiing_pph')
                ->numeric()
                ->nullable()
                ->label('Kode Billing PPH')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })()),

                Forms\Components\DatePicker::make('tanggal_bayar_pph')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Tanggal Pembayaran PPH'),

                Forms\Components\TextInput::make('ntpnpph')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->numeric()
                ->nullable()
                ->label('NTPN PPH'),

                Forms\Components\TextInput::make('validasi_pph')
                ->numeric()
                ->nullable()
                ->label('Validasi PPH')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })()),

                Forms\Components\DatePicker::make('tanggal_validasi')
                ->nullable()
                ->label('Tanggal Validasi')
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })()),

                Forms\Components\Fieldset::make('Dokumen')
                ->schema([
                    Forms\Components\FileUpload::make('up_kode_billing')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Kode Billing')
                        ->downloadable()
                        ->previewable(false),
            
                    Forms\Components\FileUpload::make('up_bukti_setor_pajak')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Bukti Setor Pajak')
                        ->downloadable()
                        ->previewable(false),
            
                    Forms\Components\FileUpload::make('up_suket_validasi')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Suket Validasi')
                        ->downloadable()
                        ->previewable(false),                
                    ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siteplan')->sortable()->searchable()->label('Blok'),
                Tables\Columns\TextColumn::make('no_sertifikat')->sortable()->searchable()->label('No. Sertifikat'),
                Tables\Columns\TextColumn::make('kavling')
                ->sortable()
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state, 
                })->searchable()
                ->label('Jenis Unit'),
                Tables\Columns\TextColumn::make('nama_konsumen')->sortable()->searchable()->label('Nama Konsumen'),
                Tables\Columns\TextColumn::make('nik')->sortable()->searchable()->label('NIK'),
                Tables\Columns\TextColumn::make('npwp')->sortable()->searchable()->label('NPWP'),
                Tables\Columns\TextColumn::make('alamat')->sortable()->searchable()->label('Alamat'),
                Tables\Columns\TextColumn::make('nop')->sortable()->searchable()->label('NOP'),
                Tables\Columns\TextColumn::make('luas_tanah')->sortable()->searchable()->label('Luas Sertifikat'),
                Tables\Columns\TextColumn::make('harga')
                ->label('Harga')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            
            Tables\Columns\TextColumn::make('npoptkp')
                ->label('NPOPTKP')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
            
            Tables\Columns\TextColumn::make('jumlah_bphtb')
                ->label('Jumlah BPHTB')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
        
                Tables\Columns\TextColumn::make('tarif_pph')->sortable()->searchable()->label('Tarif PPH')->formatStateUsing(fn (string $state): string => match ($state) {
                    '1%' => '1 %',
                    '2.5%' => '2.5 %',
                    default => $state, 
                }),
                
                Tables\Columns\TextColumn::make('jumlah_pph')
                ->label('Jumlah PPH')
                ->sortable()
                ->searchable()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                Tables\Columns\TextColumn::make('kode_billing_pph')->sortable()->searchable()->label('Kode Billing PPH'),

                Tables\Columns\TextColumn::make('tanggal_bayar_pph')
                ->sortable()
                ->searchable()
                ->label('Tanggal Bayar PPH')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                Tables\Columns\TextColumn::make('ntpnpph')->sortable()->searchable()->label('NTPN PPH'),
                Tables\Columns\TextColumn::make('validasi_pph')->sortable()->searchable()->label('Validasi PPH'),
                Tables\Columns\TextColumn::make('tanggal_validasi')->sortable()->searchable()->label('Tanggal validasi')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)
                ->translatedFormat('d F Y')),

                TextColumn::make('up_kode_billing')
                ->label('Kode Billing')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_kode_billing) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_kode_billing) ? $record->up_kode_billing : json_decode($record->up_kode_billing, true);

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

                TextColumn::make('up_bukti_setor_pajak')
                ->label('Bukti Setor Pajak')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_bukti_setor_pajak) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_bukti_setor_pajak) ? $record->up_bukti_setor_pajak : json_decode($record->up_bukti_setor_pajak, true);

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

                TextColumn::make('up_suket_validasi')
                ->label('Suket Validasi')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_suket_validasi) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_suket_validasi) ? $record->up_suket_validasi : json_decode($record->up_suket_validasi, true);

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
                Tables\Filters\TrashedFilter::make()
                ->label('Data yang dihapus') 
                ->native(false),

                Filter::make('kavling')
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
                            ->label('Jenis Unit')
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['kavling']), fn ($q) =>
                            $q->where('kavling', $data['kavling'])
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
                                ->title('Data Validasi Diubah')
                                ->body('Data Validasi telah berhasil disimpan.')),                    
                    DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Validasi Dihapus')
                                        ->body('Data Validasi telah berhasil dihapus.')),    
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Validasi')
                            ->body('Data Validasi berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Validasi')
                            ->body('Data Validasi berhasil dihapus secara permanen.')
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
                                ->title('Data Validasi')
                                ->body('Data Validasi berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Validasi')
                                ->body('Data Validasi berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
                
                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray') 
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),
                
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path') 
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Validasi')
                                ->body('Data Validasi berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Blok, No. Sertifikat, Jenis Unit, Nama Konsumen, NIK, NPWP, Alamat, NOP, Luas Tanah, Harga, NPOPTKP, Jumlah BPHTB, Tarif PPH, Jumlah PPH, Kode Billing PPH, Tanggal Bayar PPH, NTPN PPH, Validasi PPH, Tanggal Validasi\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->no_sertifikat}, {$record->kavling}, {$record->nama_konsumen}, {$record->nik}, {$record->npwp}, {$record->alamat}, {$record->nop}, {$record->luas_tanah}, {$record->harga}, {$record->npoptkp}, {$record->jumlah_bphtb}, {$record->tarif_pph}, {$record->jumlah_pph}, {$record->kode_billing_pph}, {$record->tanggal_bayar_pph}, {$record->ntpnpph}, {$record->validasi_pph}, {$record->tanggal_validasi}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'Validasi.csv');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
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
            'index' => Pages\ListFormPajaks::route('/'),
            'create' => Pages\CreateFormPajak::route('/create'),
            'edit' => Pages\EditFormPajak::route('/{record}/edit'),
        ];
    }
}

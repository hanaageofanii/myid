<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvFakturResource\Pages;
use App\Filament\Resources\GcvFakturResource\RelationManagers;
use App\Models\gcv_faktur;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;
use App\Models\gcv_validasi_pph;
use App\Models\GcvValidasiPph;
use App\Models\gcv_uang_muka;
use App\Models\GcvUangMuka;
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


class GcvFakturResource extends Resource
{
    protected static ?string $model = gcv_faktur::class;

    protected static ?string $title = "Form Data Faktur";
    protected static ?string $navigationGroup = "Pajak";
    protected static ?int $navigationSort = 1;
    protected static ?string $pluralLabel = "Data Faktur";
    protected static ?string $navigationLabel = "Faktur";
    protected static ?string $pluralModelLabel = 'Daftar Faktur';

    protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Wizard::make([
                // STEP 1: Data Unit
                Step::make('Data Unit')
                    ->schema([
                        Section::make('Informasi Data Unit')
                            ->columns(2)
                            ->schema([
                                Select::make('kavling')
                                    ->label('Jenis Unit / Kavling')
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
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })()),

                                Select::make('siteplan')
                                    ->label('Blok / Siteplan')
                                    ->nullable()
                                    ->searchable()
                                    ->reactive()
                                    ->options(function (callable $get) {
                                            $selectedKavling = $get('kavling');
                                            if (! $selectedKavling) return [];

                                            // Ambil tenant aktif
                                            $tenant = Filament::getTenant();

                                            // Jika tenant belum diset, jangan tampilkan data apa pun
                                            if (! $tenant) return [];

                                            // Ambil hanya siteplan yang sesuai tenant aktif dan kavling terpilih
                                            return gcv_kpr::where('jenis_unit', $selectedKavling)
                                                ->where('team_id', $tenant->id)
                                                ->pluck('siteplan', 'siteplan')
                                                ->toArray();
                                        })                                    ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
->afterStateUpdated(function ($state, callable $set, $get) {
        if (! $state) return;

        $tenant = Filament::getTenant();

        $kprData = gcv_kpr::where('siteplan', $state)
            ->where('team_id', optional($tenant)->id)
            ->first();

        if ($kprData) {
            $set('nama_konsumen', $kprData->nama_konsumen);
            $set('nik', $kprData->nik);
            $set('npwp', $kprData->npwp);
            $set('alamat', $kprData->alamat);
            $set('harga_jual', $kprData->harga);
            $set('kavling', $kprData->jenis_unit);

            $harga = $kprData->harga ?? 0;
            $dpp_ppn = $harga * (11 / 12);
            $set('dpp_ppn', $dpp_ppn);

            $tarif_ppn_raw = $get('tarif_ppn') ?? '0%';
            $tarif_ppn = (float) str_replace('%', '', $tarif_ppn_raw) / 100;
            $jumlah_ppn = $harga * $tarif_ppn;
            $set('jumlah_ppn', $jumlah_ppn);
        }                                    }),
                            ]),
                    ]),

                // STEP 2: Data Konsumen
                Step::make('Data Konsumen')
                ->columns(2)
                    ->schema([
                        TextInput::make('nama_konsumen')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('Nama Konsumen'),

                        TextInput::make('nik')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('NIK'),

                        TextInput::make('npwp')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('NPWP'),

                        Textarea::make('alamat')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('Alamat'),
                    ]),

                // STEP 3: Data Penjualan & Pajak
                Step::make('Data Penjualan & Pajak')
                ->columns(2)
                    ->schema([
                        TextInput::make('harga_jual')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('Harga Jual')
                            ->prefix('Rp'),

                        TextInput::make('dpp_ppn')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('DPP PPN')
                            ->prefix('Rp'),

                        Select::make('tarif_ppn')
                            ->label('Tarif PPN')
                            ->options([
                                '11%' => '11%',
                                '12%' => '12%',
                            ])
                            ->nullable()
                            ->required()
                            ->reactive()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->afterStateUpdated(function (callable $set, callable $get) {
                                $harga = (float) str_replace(['.', ','], ['', ''], $get('harga_jual') ?? '0');
                                $tarif_ppn_raw = $get('tarif_ppn') ?? '0%';
                                $tarif_ppn = (float) str_replace('%', '', $tarif_ppn_raw) / 100;
                                $jumlah_ppn = $harga * $tarif_ppn;
                                $set('jumlah_ppn', $jumlah_ppn);
                            }),

                        TextInput::make('jumlah_ppn')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('Jumlah PPN')
                            ->prefix('Rp'),
                    ]),

                // STEP 4: Faktur & Pembayaran
                Step::make('Faktur & Pembayaran')
                ->columns(2)
                    ->schema([
                        TextInput::make('no_seri_faktur')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('No. Seri Faktur'),

                        DatePicker::make('tanggal_faktur')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('Tanggal Faktur'),

                        Select::make('status_ppn')
                            ->label('Status PPN')
                            ->options([
                                'dtp' => 'DTP',
                                'dtp_sebagian' => 'DTP Sebagian',
                                'dibebaskan' => 'Dibebaskan',
                                'bayar' => 'Bayar',
                            ])
                            ->searchable()
                            ->native(false)
                            ->reactive()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })()),

                        DatePicker::make('tanggal_bayar_ppn')
                            ->nullable()
                            ->required(fn (callable $get) => $get('status_ppn') === 'bayar')
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                            ->label('Tanggal Bayar PPN'),

                        TextInput::make('ntpn_ppn')
                            ->nullable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())->columnSpanFull()
                            ->label('NTPN PPN'),
                    ]),

                // STEP 5: Dokumen Pendukung
                Step::make('Dokumen Pendukung')
                ->columns(2)
                    ->schema([
                        FileUpload::make('up_bukti_setor_ppn')
                            ->disk('public')
                            ->multiple()
                            ->nullable()
                            ->label('Upload Bukti Setor PPN')
                            ->previewable(false)
                            ->downloadable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })()),

                        FileUpload::make('up_efaktur')
                            ->disk('public')
                            ->multiple()
                            ->nullable()
                            ->label('Upload E-Faktur')
                            ->previewable(false)
                            ->downloadable()
                            ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })()),
                    ])
            ])->columnSpanFull(),
        ]);
}

    public static function table(Table $table): Table
    {
        return $table
        ->columns([
            Tables\Columns\TextColumn::make('siteplan')->sortable()->searchable()->label('Blok'),
            Tables\Columns\TextColumn::make('kavling')->sortable()->searchable()->label('Jenis Unit')->formatStateUsing(fn (string $state): string => match ($state) {
                'standar' => 'Standar',
                'khusus' => 'Khusus',
                'hook' => 'Hook',
                'komersil' => 'Komersil',
                'tanah_lebih' => 'Tanah Lebih',
                'kios' => 'Kios',
                default => $state,
            }),
            Tables\Columns\TextColumn::make('nama_konsumen')->sortable()->searchable()->label('Nama Konsumen'),
            Tables\Columns\TextColumn::make('nik')->sortable()->searchable()->label('NIK'),
            Tables\Columns\TextColumn::make('npwp')->sortable()->searchable()->label('NPWP'),
            Tables\Columns\TextColumn::make('alamat')->sortable()->searchable()->label('Alamat'),
            Tables\Columns\TextColumn::make('no_seri_faktur')->sortable()->searchable()->label('No. Seri Faktur'),
            Tables\Columns\TextColumn::make('tanggal_faktur')->sortable()->searchable()->label('Tanggal Faktur')                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            Tables\Columns\TextColumn::make('harga_jual')->sortable()->searchable()->label('Harga')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) str_replace(['Rp', '.', ','], '', $state), 0, ',', '.')),
            Tables\Columns\TextColumn::make('dpp_ppn')->sortable()->searchable()->label('DPP PPN')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) str_replace(['Rp', '.', ','], '', $state), 0, ',', '.')),
            Tables\Columns\TextColumn::make('tarif_ppn')
            ->sortable()
            ->searchable()
            ->formatStateUsing(fn (string $state): string => match ($state) {
                '11%' => '11 %',
                '12%' => '12 %',
                default => $state,
            })
            ->label('Tarif PPN'),
            Tables\Columns\TextColumn::make('jumlah_ppn')->sortable()->searchable()->label('Jumlah PPN')->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) str_replace(['Rp', '.', ','], '', $state), 0, ',', '.')),

            Tables\Columns\TextColumn::make('status_ppn')
            ->sortable()
            ->searchable()
            ->label('Status PPN')
            ->formatStateUsing(fn ($state) => match ($state) {
                'dtp' => 'DTP',
                'dtp_sebagian' => 'DTP Sebagian',
                'dibebaskan' => 'Dibebaskan',
                'bayar' => 'Bayar',
                default => $state,
            }),
            Tables\Columns\TextColumn::make('tanggal_bayar_ppn')->sortable()->searchable()->label('Tanggal Bayar PPN')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            Tables\Columns\TextColumn::make('ntpn_ppn')->sortable()->searchable()->label('NTPN PPN'),

            TextColumn::make('up_bukti_setor_ppn')
            ->label('File Setor PPN')
            ->formatStateUsing(function ($record) {
                if (!$record->up_bukti_setor_ppn) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_bukti_setor_ppn) ? $record->up_bukti_setor_ppn : json_decode($record->up_bukti_setor_ppn, true);

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

            TextColumn::make('up_efaktur')
            ->label('File E-Faktur')
            ->formatStateUsing(function ($record) {
                if (!$record->up_efaktur) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_efaktur) ? $record->up_efaktur : json_decode($record->up_efaktur, true);

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
            Tables\Filters\TrashedFilter::make()
            ->label('Data yang dihapus')
            ->native(false),

            Filter::make('status_ppn')
                ->form([
                    Select::make('status_ppn')
                        ->options([
                            'dtp' => 'DTP',
                            'dtp_sebagian' => 'DTP Sebagian',
                            'dibebaskan' => 'Dibebaskan',
                            'bayar' => 'Bayar',
                        ])
                        ->label('Status PPN')
                        ->nullable()
                        ->native(false),
                ])
                ->query(fn ($query, $data) =>
                    $query->when(isset($data['status_ppn']), fn ($q) =>
                        $q->where('status_ppn', $data['status_ppn'])
                    )
                ),

                Filter::make('jenis_unit')
                ->form([
                    Select::make('jenis_unit')
                        ->options([
                            'standar' => 'Standar',
                            'khusus' => 'Khusus',
                            'hook' => 'Hook',
                            'komersil' => 'Komersil',
                            'tanah_lebih' => 'Tanah Lebih',
                            'kios' => 'Kios',
                        ])
                        ->label('Jenis Unit')
                        ->nullable()
                        ->native(false),
                ])
                ->query(fn ($query, $data) =>
                    $query->when(isset($data['jenis_unit']), fn ($q) =>
                        $q->where('jenis_unit', $data['jenis_unit'])
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
                            ->title('Data Faktur Diubah')
                            ->body('Data Faktur telah berhasil disimpan.')),
                            DeleteAction::make()
                            ->color('danger')
                            ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                            ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                            ->successNotification(
                                Notification::make()
                                    ->success()
                                    ->title('Data Faktur Dihapus')
                                    ->body('Data Faktur telah berhasil dihapus.')),
                Tables\Actions\RestoreAction::make()
                ->color('info')
                ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Data Faktur')
                        ->body('Data Faktur berhasil dikembalikan.')
                ),
                Tables\Actions\ForceDeleteAction::make()
                ->color('primary')
                ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Data Faktur')
                        ->body('Data Faktur berhasil dihapus secara permanen.')
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
                            ->title('Data Faktur')
                            ->body('Data Faktur berhasil dihapus.'))
                            ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),

                BulkAction::make('forceDelete')
                    ->label('Hapus Permanent')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Faktur')
                            ->body('Data Faktur berhasil dihapus secara permanen.'))
                            ->requiresConfirmation()
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

                        return redirect()->route('faktur.print');
                    }),



                Tables\Actions\RestoreBulkAction::make()
                    ->label('Kembalikan Data')
                    ->icon('heroicon-o-arrow-path')
                    ->color('success')
                    ->button()
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Faktur')
                            ->body('Data Faktur berhasil dikembalikan.')),
            ]);

}

public static function exportData(Collection $records)
{
    $csvData = "ID,  Blok, Kavling, Nama Konsumen, NIK, NPWP, Alamat, No. Seri Faktur, Tanggal Faktur, Harga Jual, DPP PPN, Tarif PPN, Jumlah PPN, Status PPN, Tanggal Bayar PPN, NTPN PPN\n";

    foreach ($records as $record) {
        $csvData .= "{$record->id}, {$record->siteplan}, {$record->kavling}, {$record->nama_konsumen}, {$record->nik}, {$record->npwp}, {$record->alamat}, {$record->no_seri_faktur}, {$record->tanggal_faktur}, {$record->harga_jual}, {$record->dpp_ppn}, {$record->tarif_ppn}, {$record->jumlah_ppn}, {$record->status_ppn}, {$record->tanggal_bayar_ppn}, {$record->ntpn_ppn}\n";
    }

    return response()->streamDownload(fn () => print($csvData), 'Faktur.csv');
}

public static function getRelations(): array
{
    return [
        //
    ];
}

    protected static function mutateFormDataBeforeCreate(array $data): array
{
    $user = filament()->auth()->user();

    if (! $user) {
        throw new \Exception('User harus login untuk membuat data ini.');
    }

    $data['user_id'] = $user->id;
    $data['team_id'] = $user->current_team_id ?? filament()->getTenant()?->id;

    return $data;
}


protected static function mutateFormDataBeforeSave(array $data): array
{
    if (! isset($data['user_id'])) {
        $data['user_id'] = filament()->auth()->id();
    }

    return $data;
}


public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
        ->where('team_id', filament()->getTenant()->id); // filter data sesuai tenant
}

public static function canViewAny(): bool
{
    $user = auth()->user();
        /** @var \App\Models\User|null $user */

    return $user->hasRole(['admin','Legal Pajak','Direksi','Super Admin', 'Legal officer']);
}



    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvFakturs::route('/'),
            'create' => Pages\CreateGcvFaktur::route('/create'),
            'edit' => Pages\EditGcvFaktur::route('/{record}/edit'),
        ];
    }
}

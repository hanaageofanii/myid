<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvKprResource\Pages;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use App\Filament\Resources\GcvKprResource\RelationManagers;
use App\Models\gcv_kpr;
use App\Models\gcv_stok;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\CheckboxColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
use Illuminate\Validation\Rule;
use Filament\Forms\Components\Checkbox;
use Carbon\Carbon;
use App\Filament\Resources\GcvKprResource\Widgets\gcv_kprStats;


class GcvKprResource extends Resource
{
    protected static ?string $model = gcv_kpr::class;

    protected static ?string $title = "Data Akad KPR";
    protected static ?string $pluralLabel = "Data Akad KPR";
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'KPR > Data Akad KPR';
    protected static ?string $pluralModelLabel = 'Data Akad KPR';
     protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';


    protected static ?int $navigationSort = 5;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
    Wizard::make([
        Step::make('Informasi Unit')
            ->columns(2)
            ->description('Informasi dasar unit')
            ->schema([
                Forms\Components\Select::make('jenis_unit')
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
                    ->label('Jenis Unit')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })())
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $bookedBlok = gcv_stok::where('kpr_status', 'akad')
                                ->where('kavling', $state)
                                ->pluck('siteplan')
                                ->toArray();
                            $formattedOptions = array_combine($bookedBlok, $bookedBlok);
                            $set('siteplan', null);
                            $set('available_siteplans', $formattedOptions);
                        }
                    }),

                Forms\Components\Select::make('siteplan')
                    ->label('Blok')
                    ->nullable()
                    ->reactive()
                    ->required()
                    ->options(fn (callable $get) => $get('jenis_unit')
                        ? gcv_stok::where('kpr_status', 'akad')
                            ->where('kavling', $get('jenis_unit'))
                            ->pluck('siteplan', 'siteplan')
                            ->toArray()
                        : [])
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })())
                    ->afterStateUpdated(function ($state, callable $set) {
                        $gcv = gcv_stok::where('siteplan', $state)->first();
                        if ($gcv) {
                            $set('tanggal_akad', $gcv->tanggal_akad);
                            $set('tanggal_booking', $gcv->tanggal_booking);
                            $set('nama_konsumen', $gcv->nama_konsumen);
                            $set('agent', $gcv->agent);
                            $set('luas', $gcv->luas_tanah);
                            $set('type', $gcv->type);
                            $set('status_akad', $gcv->kpr_status);
                            $set('pembayaran', $gcv->status_pembayaran);
                        }
                    }),
                Forms\Components\Select::make('type')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })())
                    ->options([
                        '29/60' => '29/60',
                        '30/60' => '30/60',
                        '45/104' => '45/104',
                        '32/52' => '32/52',
                        '36/60' => '36/60',
                        '36/72' => '36/72',
                    ])
                    ->nullable()
                    ->label('Type')
                    ->required(),

                Forms\Components\TextInput::make('luas')
                    ->numeric()
                    ->nullable()
                    ->label('Luas')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\DatePicker::make('tanggal_booking')
                    ->label('Tanggal Booking')
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\TextInput::make('agent')
                    ->label('Agent')
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
                ]),

        Step::make('Data Konsumen')
        ->columns(2)
        ->description('Informasi data konsumen')
            ->schema([
                Forms\Components\TextInput::make('nama_konsumen')
                    ->label('Nama Konsumen')
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\TextInput::make('nik')
                    ->label('NIK')
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\TextInput::make('npwp')
                    ->label('NPWP')
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\TextInput::make('no_hp')
                    ->label('No. Handphone')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\TextInput::make('no_email')
                    ->label('Email')
                    ->email()
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\DatePicker::make('tanggal_akad')
                    ->label('Tanggal Akad')
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\Select::make('status_akad')
                    ->label('Status Akad')
                    ->options([
                        'akad' => 'Akad',
                        'batal' => 'Batal',
                    ])
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
            ]),

        Step::make('Pembayaran')
            ->columns(2)
            ->description('Informasi data pembayaran')
            ->schema([
                Forms\Components\TextInput::make('harga')
                    ->label('Harga')
                    ->prefix('Rp')
                    ->nullable()
                    ->columnSpanfull()
                    ->numeric()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\TextInput::make('maksimal_kpr')
                    ->label('Maksimal KPR')
                    ->prefix('Rp')
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\Select::make('pembayaran')
                    ->label('Pembayaran')
                    ->options([
                        'kpr' => 'KPR',
                        'cash' => 'Cash',
                        'cash_bertahap' => 'Cash Bertahap',
                        'promo' => 'Promo',
                    ])
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\Select::make('bank')
                    ->label('Bank')
                    ->options([
                        'btn_cikarang' => 'BTN Cikarang',
                        'btn_bekasi' => 'BTN Bekasi',
                        'btn_karawang' => 'BTN Karawang',
                        'bjb_syariah' => 'BJB Syariah',
                        'bjb_jababeka' => 'BJB Jababeka',
                        'btn_syariah' => 'BTN Syariah',
                        'brii_bekasi' => 'BRI Bekasi',
                    ])
                    ->nullable()
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

                Forms\Components\TextInput::make('no_rekening')
                    ->label('No. Rekening')
                    ->nullable()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
            ]),

        Step::make('Dokumen')
            ->columns(3)
            ->description('Informasi kelengkapan dokumen')
            ->schema([
                Forms\Components\Fieldset::make('Cek Kelengkapan Dokumen Data Diri')
                    ->schema([
                        Checkbox::make('ktp')->label('KTP')->accepted()->inline(),
                        Checkbox::make('kk')->label('Kartu Keluarga')->accepted()->inline(),
                        Checkbox::make('npwp_upload')->label('NPWP')->inline(),
                        Checkbox::make('buku_nikah')->label('Buku Nikah')->inline(),
                        Checkbox::make('akte_cerai')->label('Akta Cerai')->inline(),
                        Checkbox::make('akte_kematian')->label('Akta Kematian')->inline(),
                        Checkbox::make('kartu_bpjs')->label('Kartu BPJS')->inline(),
                        Checkbox::make('drk')->label('DRK')->accepted()->inline(),
                    ]),

                Forms\Components\FileUpload::make('data_diri')
                    ->label('Upload Data Diri')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->downloadable()
                    ->columnSpanfull()
                    ->previewable(false)
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
            ]),
    ])
    ->columnSpanFull()
]);
    }




    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_unit')->label('Jenis Unit')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state,
                })->searchable(),

                Tables\Columns\TextColumn::make('siteplan')->sortable()->searchable()->label('Blok'),
                Tables\Columns\TextColumn::make('type')->sortable()->searchable()->label('Type'),
                Tables\Columns\TextColumn::make('luas')->sortable()->searchable()->label('Luas')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('agent')->sortable()->searchable()->label('Agent'),
                Tables\Columns\TextColumn::make('tanggal_booking')
                ->searchable()
                ->label('Tanggal Booking')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

            Tables\Columns\TextColumn::make('tanggal_akad')
                ->searchable()
                ->label('Tanggal Akad')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

            Tables\Columns\TextColumn::make('harga')
                ->sortable()
                ->searchable()
                ->label('Harga')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('maksimal_kpr')
                ->sortable()
                ->searchable()
                ->label('Maksimal KPR')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('nama_konsumen')->sortable()->searchable()->label('Nama Konsumen'),
                Tables\Columns\TextColumn::make('nik')->sortable()->searchable()->label('NIK'),
                Tables\Columns\TextColumn::make('npwp')->sortable()->searchable()->label('NPWP'),
                Tables\Columns\TextColumn::make('alamat')->sortable()->searchable()->label('Alamat'),
                Tables\Columns\TextColumn::make('no_hp')->sortable()->searchable()->label('No Handphone'),
                Tables\Columns\TextColumn::make('no_email')->sortable()->searchable()->label('Email'),
                Tables\Columns\TextColumn::make('pembayaran')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                        'kpr' => 'KPR',
                        'cash' => 'Cash',
                        'cash_bertahap' => 'Cash Bertahap',
                        'promo' => 'Promo',
                    default => ucfirst($state),
                })
                ->sortable()
                ->searchable()
                ->label('Pembayaran'),
                Tables\Columns\TextColumn::make('bank')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'btn_cikarang' => 'BTN Cikarang',
                    'btn_bekasi' => 'BTN Bekasi',
                    'btn_karawang' => 'BTN Karawang',
                    'bjb_syariah' => 'BJB Syariah',
                    'bjb_jababeka' => 'BJB Jababeka',
                    'btn_syariah' => 'BTN Syariah',
                    'brii_bekasi' => 'BRI Bekasi',
                default => ucfirst($state),
                })
                ->sortable()
                ->searchable()
                ->label('Bank'),
                Tables\Columns\TextColumn::make('no_rekening')->sortable()->searchable()->label('No. Rekening'),
                Tables\Columns\TextColumn::make('status_akad')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'akad' => 'Akad',
                        'batal' => 'Batal',
                        default => ucfirst($state),
                    })
                    ->sortable()
                    ->searchable()
                    ->label('Status Akad'),

                    CheckboxColumn::make('kk')
                        ->label('Kartu Keluarga'),

                    CheckboxColumn::make('ktp')
                    ->label('KTP'),

                    CheckboxColumn::make('npwp_upload')
                    ->label('NPWP'),

                    CheckboxColumn::make('buku_nikah')
                    ->label('Buku Nikah'),

                    CheckboxColumn::make('akte_cerai')
                    ->label('Akte Cerai'),

                    CheckboxColumn::make('akte_kematian')
                    ->label('Akte Kematian'),

                    CheckboxColumn::make('kartu_bpjs')
                    ->label('Kartu BPJS'),

                    CheckboxColumn::make('drk')
                    ->label('DRK'),

                    TextColumn::make('data_diri')
                    ->label('Data Diri')
                    ->formatStateUsing(function ($record) {
                        if (!$record->data_diri) {
                            return 'Tidak Ada Dokumen';
                        }

                        $files = is_array($record->data_diri) ? $record->data_diri : json_decode($record->data_diri, true);

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

                Filter::make('status_akad')
                    ->label('Status Akad')
                    ->form([
                        Select::make('status_akad')
                            ->options([
                                'akad' => 'Akad',
                                'batal' => 'Batal',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_akad']), fn ($q) =>
                            $q->where('status_akad', $data['status_akad'])
                        )
                    ),

                    Filter::make('jenis_unit')
                    ->label('Jenis Unit')
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
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['jenis_unit']), fn ($q) =>
                            $q->where('jenis_unit', $data['jenis_unit'])
                        )
                    ),
                    Tables\Filters\SelectFilter::make('pembayaran')
                ->label('Status Pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'kpr' => 'KPR',
                    'cash_bertahap' => 'Cash Bertahap',
                    'promo' => 'Promo',
                ])
                ->native(false),

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
                                ->title('Data KPR Diubah')
                                ->body('Data KPR telah berhasil disimpan.')),
                    DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Blok {$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data KPR Dihapus')
                                ->body('Data KPR telah berhasil dihapus.')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data KPR')
                            ->body('Data KPR berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data KPR')
                            ->body('Data KPR berhasil dihapus secara permanen.')
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
                                ->title('Data KPR')
                                ->body('Data KPR berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data KPR')
                                ->body('Data KPR berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
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

                        return redirect()->route('datakpr.print');
                    }),


                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data KPR')
                                ->body('Data KPR berhasil dikembalikan.')),
                ]);

    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Jenis Unit, Blok, Type, Luas, Agent, Tanggal Booking, Tanggal Akad, Harga, Maksimal KPR, Nama Konsumen, NIK, NPWP, Alamat, NO Handphone, Email, Pembayaran, Bank, No. Rekening, Status Akad\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->jenis_unit}, {$record->siteplan}, {$record->type}, {$record->luas}, {$record->agent}, {$record->tanggal_booking}, {$record->tanggal_akad}, {$record->harga}, {$record->maksimal_kpr}, {$record->nama_konsumen}, {$record->nik}, {$record->npwp}, {$record->alamat}, {$record->no_hp}, {$record->no_email}, {$record->pembayaran}, {$record->bank}, {$record->no_rekening}, {$record->status_akad}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'KPR.csv');
    }


    public static function getRelations(): array
    {
        return [

        ];
    }


public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);

    // Filter by tenant
    $tenant = filament()->getTenant();
    if ($tenant) {
        $query->where('team_id', $tenant->id);
    }

    // Filter by user role
    /** @var \App\Models\User|null $user */
    $user = Auth::user();
    if ($user && $user->hasRole(['Legal officer', 'Legal Pajak'])) {
        $query->where('status_akad', 'akad');
    }

    return $query;
}
    public static function getWidgets(): array
    {
        return [
            gcv_kprStats::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvKprs::route('/'),
            'create' => Pages\CreateGcvKpr::route('/create'),
            'edit' => Pages\EditGcvKpr::route('/{record}/edit'),
        ];
    }
}
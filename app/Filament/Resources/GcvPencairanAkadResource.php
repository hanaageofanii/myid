<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvPencairanAkadResource\Pages;
use App\Filament\Resources\GcvPencairanAkadResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\gcv_pencairan_akad;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use App\Models\gcv_stok;
use App\Models\gcv_kpr;
use App\Models\gcvDataSiteplan;
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
use Illuminate\Validation\Rule;
use App\Models\gcv_datatandaterima;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\GcvLegalitasResource\Widgets\gcv_legalitasStats;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use Carbon\Carbon;




class GcvPencairanAkadResource extends Resource
{
    protected static ?string $model = gcv_pencairan_akad::class;

    protected static ?string $title = "Data Pencairan Akad";
    protected static ?string $pluralLabel = "Data Pencairan Akad";
    protected static ?string $navigationLabel = 'Keuangan > Pencairan Akad';
    protected static ?string $pluralModelLabel = 'Data Pencairan Akad';
    protected static ?int $navigationSort = 6;
    protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';

    protected static ?string $navigationIcon = 'heroicon-o-cloud-arrow-up';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
        Step::make('Data Konsumen')
            ->description('Informasi data konsumen')
            ->schema([
                Section::make('Informasi Konsumen')
                            ->columns(2)
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
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })()),

                        //

                        Select::make('siteplan')
    ->label('Blok')
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
    ->searchable()
    ->disabled(fn () => ! (function () {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole(['admin','Kasir 1']);
    })())
    ->reactive()
    ->afterStateUpdated(function ($state, callable $set) {
        if ($state) {
            $data = gcv_kpr::where('siteplan', $state)->first();
            if ($data) {
                $set('nama_konsumen', $data->nama_konsumen);
                $set('bank', $data->bank);
                $set('max_kpr', $data->maksimal_kpr);
            }

            $bayar = gcv_stok::where('siteplan', $state)->first();
            if ($state) {
                $set('status_pembayaran', $bayar->status_pembayaran ?? null);
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

                        Select::make('bank')
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
                            ->required()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })()),

                        TextInput::make('max_kpr')
                            ->label('Maksimal KPR')
                            ->prefix('Rp')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })())
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, $get) =>
                                $set('dana_jaminan', max(0, (int) $state - (int) $get('nilai_pencairan')))
                            )
                            ->dehydrated(),

                            Select::make('status_pembayaran')
                            ->options([
                                'cash' => 'CASH',
                                'kpr' => 'KPR',
                                'cash_bertahap' => 'CASH BERTAHAP',
                                'promo' => 'PROMO',
                            ])
                            ->reactive()
                            ->dehydrated()
                            ->label('Status Pembayaran')
                            ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','KPR Officer','Kasir 1']);
                                })()),
                    ]),
            ]),

        Step::make('Pembayaran')
            ->description('Informasi Pecairan')
            ->schema([
                Section::make('Detail Pembayaran')
                    ->columns(2)
                    ->schema([
                        TextInput::make('no_debitur')
                        ->label('No. Debitur')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive(),

                        DatePicker::make('tanggal_pencairan')
                            ->label('Tanggal Pencairan Akad')
                            ->required()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })()),

                        TextInput::make('nilai_pencairan')
                            ->label('Nilai Pencairan')
                            ->prefix('Rp')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })())
                            ->dehydrated()
                            ->reactive()
                            ->afterStateUpdated(fn ($state, callable $set, $get) =>
                                $set('dana_jaminan', max(0, (int) $get('max_kpr') - (int) $state))
                            ),

                        TextInput::make('dana_jaminan')
                            ->label('Dana Jaminan')
                            ->prefix('Rp')
                            ->reactive()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })())
                            ->dehydrated(),
                    ]),
            ]),

        Step::make('Dokumen')
            ->description('Upload dokumen')
            ->schema([
                Section::make('Upload Dokumen')
                    ->columns(2)
                    ->schema([
                        FileUpload::make('up_spd5')
                            ->label('Upload SPD5')
                            ->disk('public')
                            ->nullable()
                            ->multiple()
                            ->downloadable()
                            ->afterStateHydrated(function ($component, $state) {
                                $day = now()->day;

                                if (blank($state)) {
                                    Notification::make()
                                        ->title($day > 4 ? '❗ SPD 5 Belum Di-upload' : '⚠️ Wajib Upload SPD 5')
                                        ->body($day > 4
                                            ? 'Tanggal upload SPD 5 sudah lewat. Harap segera lengkapi dokumen.'
                                            : 'Hari ini tanggal 4. Harap upload SPD 5.')
                                        ->{ $day > 4 ? 'danger' : 'warning' }()
                                        ->persistent()
                                        ->send();
                                                }
                                    })
                            ->previewable(false)
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })()),

                        FileUpload::make('up_rekening_koran')
                            ->label('Upload Rekening Koran')
                            ->disk('public')
                            ->nullable()
                            ->multiple()
                            ->downloadable()
                            ->previewable(false)
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })())
                    ]),
            ]),
    ])
    ->columnSpan('full')->columns(2),
]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kavling')->label('Kavling')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state,
                })->searchable(),
                TextColumn::make('siteplan')->searchable()->label('Blok'),
                TextColumn::make('bank')
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
            TextColumn::make('nama_konsumen')->searchable()->label('Nama Konsumen'),
            TextColumn::make('max_kpr')
            ->searchable()
            ->label('Max KPR')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

            Tables\Columns\TextColumn::make('status_pembayaran')
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

            TextColumn::make('no_debitur')->searchable()->label('No. Debitur'),
            TextColumn::make('tanggal_pencairan')
            ->searchable()
            ->label('Tanggal Pencairan')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('nilai_pencairan')
            ->searchable()
            ->label('Nilai Pencairan')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dana_jaminan')
            ->searchable()
            ->label('Dana Jaminan')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('up_spd5')
            ->label('File SPD5')
            ->formatStateUsing(function ($record) {
                if (!$record->up_rekening_koran) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_rekening_koran) ? $record->up_rekening_koran : json_decode($record->up_rekening_koran, true);

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
            TextColumn::make('up_rekening_koran')
            ->label('File Rekening Koran')
            ->formatStateUsing(function ($record) {
                if (!$record->up_rekening_koran) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_rekening_koran) ? $record->up_rekening_koran : json_decode($record->up_rekening_koran, true);

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
                    ->label('Kavling')
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

                Filter::make('bank')
                    ->label('Bank')
                    ->form([
                        Select::make('bank')
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
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['bank']), fn ($q) =>
                            $q->where('bank', $data['bank'])
                        )
                    ),
                    Tables\Filters\SelectFilter::make('status_pembayaran')
                ->label('Status Pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'kpr' => 'KPR',
                    'cash_bertahap' => 'Cash Bertahap',
                    'promo' => 'Promo',
                ])
                ->native(false),

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
                                ->title('Data Pencairan Akad Diubah')
                                ->body('Data Pencarian Akad telah berhasil disimpan.')),
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencarian Akad Dihapus')
                                ->body('Data Pencarian Akad telah berhasil dihapus.')),
                    RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pencarian Akad')
                            ->body('Data Pencarian Akad berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pencarian Akad')
                            ->body('Data Pencarian Akad berhasil dihapus secara permanen.')
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
                                ->title('Data Pencarian Akadg')
                                ->body('Data Pencarian Akad berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencarian Akad')
                                ->body('Data Pencarian Akad berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
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

                            return redirect()->route('datapencairanakad.print');
                    }),


                    RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencarian Akad')
                                ->body('Data Pencarian Akad berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Kavling, Blok, Bank, Nama Konsumen, Jenis Pembayaran, No. Debitur, Maksimal KPR, Tanggal Pencairan, Nilai Pencairan, Dana Jaminan\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->kavling}, {$record->bank}, {$record->nama_konsumen}, {$record->status_pembayaran}, {$record->no_debitur}, {$record->max_kpr}, {$record->tanggal_pencairan}, {$record->nilai_pencairan}, {$record->dana_jaminan}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'PencairanAkad.csv');
    }

public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
        ->where('team_id', filament()->getTenant()->id); // filter data sesuai tenant
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
            'index' => Pages\ListGcvPencairanAkads::route('/'),
            'create' => Pages\CreateGcvPencairanAkad::route('/create'),
            'edit' => Pages\EditGcvPencairanAkad::route('/{record}/edit'),
        ];
    }
}
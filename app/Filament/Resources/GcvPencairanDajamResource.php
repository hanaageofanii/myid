<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvPencairanDajamResource\Pages;
use App\Filament\Resources\GcvPencairanDajamResource\RelationManagers;
use App\Models\gcv_pencairan_dajam;
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

class GcvPencairanDajamResource extends Resource
{
    protected static ?string $model = gcv_pencairan_dajam::class;

    protected static ?string $title = "Data Pencairan Dajam";
    protected static ?string $pluralLabel = "Data Pencairan Dajam";
    protected static ?string $navigationLabel = 'Keuangan > Pencairan Dajam';
    protected static ?string $pluralModelLabel = 'Data Pencairan Dajam';
    protected static ?int $navigationSort = 7;
    protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';

    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                 Wizard::make([
        Step::make('Data Konsumen')
            ->description('Informasi siteplan, bank, dan konsumen')
            ->schema([
                Section::make('Informasi Siteplan')
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
                                $kprData = gcv_kpr::where('siteplan', $state)->first();
                                $pencairanAkad = gcv_pencairan_akad::where('siteplan', $state)->first();
                                // $dajamData = dajam::where('siteplan', $state)->first();
                                // $pengDajam = pengajuan_dajam_pca::where('siteplan', $state)->first();

                                $set('bank', $kprData?->bank);
                                $set('nama_konsumen', $kprData?->nama_konsumen);
                                $set('max_kpr', $kprData?->maksimal_kpr);
                                $set('no_debitur', $pencairanAkad?->no_debitur);
                                // $set('pembukuan', $dajamData?->pembukuan);
                                // $set('no_debitur', $dajamData?->no_debitur);
                                // $set('nama_dajam', $pengDajam?->nama_dajam);
                            }),
                    ]),
                Section::make('Identitas Konsumen')
                    ->columns(2)
                    ->schema([
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
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                            ->required()
                            ->label('Bank'),
                        TextInput::make('nama_konsumen')
                            ->label('Nama Konsumen')
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive(),
                        TextInput::make('no_debitur')
                            ->label('No. Debitur')
                            ->columnSpanFull()
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive(),
                    ]),
            ]),

        Step::make('Data Pencairan')
            ->description('Informasi pencairan & nilai dajam')
            ->schema([
            Section::make('Nilai dan Selisih Dajam')
                    ->columns(2)
                    ->schema([
                Select::make('nama_dajam')
                            ->options([
                                'sertifikat' => 'Sertifikat',
                                'imb' => 'IMB',
                                'jkk' => 'JKK',
                                'bestek' => 'Bestek',
                                'pph' => 'PPH',
                                'bphtb' => 'BPHTB',
                            ])
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->label('Nama Dajam'),

                        TextInput::make('nilai_dajam')
                            ->label('Nilai Dajam')
                            ->live()
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive()
                            ->prefix('Rp')
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                $set('selisih_dajam', max(0, ($state ?? 0) - ($get('nilai_pencairan') ?? 0)))
                            ),
                        TextInput::make('nilai_pencairan')
                            ->label('Nilai Pencairan')
                            ->live()
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive()
                            ->prefix('Rp')
                            ->afterStateUpdated(fn ($state, callable $set, callable $get) =>
                                $set('selisih_dajam', max(0, ($get('nilai_dajam') ?? 0) - ($state ?? 0)))
                            ),
                        TextInput::make('selisih_dajam')
                            ->label('Selisih Dajam')
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())                            ->prefix('Rp'),
                        DatePicker::make('tanggal_pencairan')
                            ->label('Tanggal Pencairan')
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => \Carbon\Carbon::parse($state)->translatedFormat('d F Y'))
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                    ]),
            ]),

        Step::make('Upload Dokumen')
            ->description('Unggah file pendukung')
            ->schema([
                Section::make('Dokumen Pendukung')
                    ->columns(2)
                    ->schema([
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
                        })()),
                        FileUpload::make('up_lainnya')
                            ->label('Upload Dokumen Lainnya')
                            ->disk('public')
                            ->nullable()
                            ->multiple()
                            ->downloadable()
                            ->previewable(false)
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })()),
                    ]),
            ]),
    ])
    ->columnSpanFull()
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

                TextColumn::make('siteplan')
                ->sortable()
                ->searchable()
                ->label('Blok'),

                TextColumn::make('bank')
                ->formatStateUsing(fn (string $state): string =>match($state){
                    'btn_cikarang' => 'BTN Cikarang',
                    'btn_bekasi' => 'BTN Bekasi',
                    'btn_karawang' => 'BTN Karawang',
                    'bjb_syariah' => 'BJB Syariah',
                    'bjb_jababeka' => 'BJB Jababeka',
                    'btn_syariah' => 'BTN Syariah',
                    'brii_bekasi' => 'BRI Bekasi',
                    default => ucfirst($state)
                })
                ->sortable()
                ->searchable()
                ->label('Bank'),

                TextColumn::make('nama_konsumen')
                ->searchable()
                ->label('Nama Konsumen')
                ->sortable(),

                TextColumn::make('no_debitur')
                ->searchable()
                ->sortable()
                ->label('No. Debitur'),

                TextColumn::make('nama_dajam')
                ->formatStateUsing(fn(string $state): string => match ($state){
                    'sertifikat' => 'Sertifikat',
                    'imb' => 'IMB',
                    'jkk' => 'JKK',
                    'bestek' => 'Bestek',
                    'pph' => 'PPH',
                    'bphtb' => 'BPHTB',
                    default => ucfirst($state),
                })
                ->sortable()
                ->searchable()
                ->label('Nama Dajam'),

                TextColumn::make('nilai_dajam')
                ->sortable()
                ->searchable()
                ->label('Nilai Dajam')
                ->formatStateUsing(fn($state)=>'Rp ' . number_format((float)$state, 0, ',', '.')),

                TextColumn::make('tanggal_pencairan')
                ->searchable()
                ->sortable()
                ->label('Tanggal Pencairan')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

                TextColumn::make('nilai_pencairan')
                ->sortable()
                ->searchable()
                ->label('Nilai Pencairan')
                ->formatStateUsing(fn($state)=>'Rp ' . number_format((float)$state, 0, ',', '.')),

                TextColumn::make('selisih_dajam')
                ->sortable()
                ->searchable()
                ->label('Selisih Dajam')
                ->formatStateUsing(fn($state)=>'Rp ' . number_format((float)$state, 0, ',', '.')),

                TextColumn::make('up_rekening_koran')
            ->label('Rekening Koran')
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

            TextColumn::make('up_lainnya')
            ->label('Dokumen Lainnya')
            ->formatStateUsing(function ($record) {
                if (!$record->up_lainnya) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_lainnya) ? $record->up_lainnya: json_decode($record->up_lainnya, true);

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

                    Filter::make('nama_dajam')
                    ->form([
                        Select::make('nama_dajam')
                            ->options([
                                'sertifikat' => 'Sertifikat',
                            'imb' => 'IMB',
                            'jkk' => 'JKK',
                            'bestek' => 'Bestek',
                            'pph' => 'PPH',
                            'bphtb' => 'BPHTB',
                            ])
                            ->nullable()
                            ->label('Nama Dajam')
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_dajam']), fn ($q) =>
                            $q->where('status_dajam', $data['status_dajam'])
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
                                ->title('Data Pencairan Dajam Diubah')
                                ->body('Data Pencairan Dajam telah berhasil disimpan.')),
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencairan Dajam Dihapus')
                                ->body('Data Pencairan Dajam telah berhasil dihapus.')),
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
                            ->title('Data Pencairan Dajam')
                            ->body('Data Pencairan Dajam berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pencairan Dajam')
                            ->body('Data Pencairan Dajam berhasil dihapus secara permanen.')
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
                                ->title('Data Pencairan Dajam')
                                ->body('Data Pencairan Dajam berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencairan Dajam')
                                ->body('Data Pencairan Dajam berhasil dihapus secara permanen.'))
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

                        return redirect()->route('datapencairandajam.print');
                    }),

                    RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencairan Dajam')
                                ->body('Data Pencairan Dajam berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Kavling, Blok, Bank, No. Debitur, Nama Konsumen, Nama Dajam, Nilai Dajam, Tanggal Pencairan, Nilai Pencairan, Selisih Dajam\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->kavling}, {$record->bank}, {$record->no_debitur}, {$record->nama_konsumen}, {$record->nama_dajam}, {$record->nilai_dajam}, {$record->tanggal_pencairan}, {$record->nilai_pencairan}, {$record->selisih_dajam}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'PencairanDajam.csv');
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
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvPencairanDajams::route('/'),
            'create' => Pages\CreateGcvPencairanDajam::route('/create'),
            'edit' => Pages\EditGcvPencairanDajam::route('/{record}/edit'),
        ];
    }
}
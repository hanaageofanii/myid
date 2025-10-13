<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvPengajuanDajamResource\Pages;
use App\Filament\Resources\GcvPengajuanDajamResource\RelationManagers;
use App\Models\gcv_pengajuan_dajam;
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
use App\Models\gcv_validasi_pph;
use App\Models\gcv_pencairan_dajam;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\GcvLegalitasResource\Widgets\gcv_legalitasStats;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use Carbon\Carbon;


class GcvPengajuanDajamResource extends Resource
{
    protected static ?string $model = gcv_pengajuan_dajam::class;

    protected static ?string $title = "Form Pengajuan Dajam";
    protected static ?string $pluralLabel = "Data Pengajuan Dajam";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $navigationLabel = "Pengajuan Dajam";
    protected static ?string $pluralModelLabel = 'Daftar Pengajuan Dajam';
     protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
        protected static ?int $navigationSort = 6;

public static function form(Form $form): Form
{
    return $form->schema([
        Wizard::make([
            Step::make('Data Konsumen')
            ->columns(2)
            ->description('Informasi data konsumen')
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

                                        return gcv_kpr::where('jenis_unit', $selectedKavling)
                                            ->pluck('siteplan', 'siteplan')
                                            ->toArray();
                                    })
                                    ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','Legal Pajak']);
                                    })())
                        ->afterStateUpdated(function ($state, callable $set) {
                            $kprData = gcv_kpr::where('siteplan', $state)->first();
                            $akadData = gcv_pencairan_akad::where('siteplan', $state)->first();
                            // $pajakData = gcv_validasi_pph::where('siteplan', $state)->first();
                            $dajamData = gcv_pencairan_dajam::where('siteplan', $state)->first();

                            $maxKpr = $kprData->maksimal_kpr ?? 0;
                            $nilaiPencairan = $akadData->nilai_pencairan ?? 0;

                            $set('bank', $kprData->bank ?? null);
                            $set('nama_konsumen', $kprData->nama_konsumen ?? null);
                            $set('max_kpr', $maxKpr);
                            $set('nilai_pencairan', $nilaiPencairan);
                            // $set('dajam_pph', $pajakData->jumlah_pph ?? 0);
                            // $set('dajam_bphtb', $pajakData->jumlah_bphtb ?? 0);
                            // $set('total_dajam', max(0, $maxKpr - $nilaiPencairan));

                            if ($dajamData) {
                                // $set('dajam_sertifikat', $dajamData->dajam_sertifikat);
                                // $set('dajam_imb', $dajamData->dajam_imb);
                                // $set('dajam_listrik', $dajamData->dajam_listrik);
                                // $set('dajam_jkk', $dajamData->dajam_jkk);
                                // $set('dajam_bestek', $dajamData->dajam_bestek);
                                // $set('jumlah_realisasi_dajam', $dajamData->jumlah_realisasi_dajam);
                                // $set('pembukuan', $dajamData->pembukuan);
                                $set('no_debitur', $dajamData->no_debitur);
                            }
                        }),

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
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                    TextInput::make('nama_konsumen')
                        ->label('Nama Konsumen')
                        ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })())
                        ->reactive(),

                    TextInput::make('no_debitur')
                        ->label('No. Debitur')
                        ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })())->columnSpanFull()
                        ->reactive(),
                ]),

            Step::make('Detail Dajam')
            ->columns(2)
            ->description('Informasi pengajuan dajam')
                ->schema([
                    Select::make('nama_dajam')
                        ->label('Nama Dajam')
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
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                    TextInput::make('no_surat')
                        ->label('No. Surat')
                        ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                    DatePicker::make('tanggal_pengajuan')
                        ->label('Tanggal Pengajuan')
                        ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                    TextInput::make('nilai_pencairan')
                        ->label('Nilai Pencairan')
                        ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),

                     TextArea::make('catatan')
                        ->label('Catatan')
                        ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })())->columnSpanFull()
                        ->reactive(),
                ]),

            Step::make('Dokumen')
            ->columns(2)
            ->description('Informasi upload dokumen')
                ->schema([
                    FileUpload::make('up_surat_pengajuan')
                        ->label('Upload Surat Pengajuan')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->previewable(false)
                        ->downloadable()
                        ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                    FileUpload::make('up_nominatif_pengajuan')
                        ->label('Upload Nominatif Pengajuan')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->previewable(false)
                        ->downloadable()
                        ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                            ])
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
            TextColumn::make('no_debitur')->searchable()->label('No. Debitur'),
            TextColumn::make('nama_dajam')
                ->formatStateUsing(fn (string $state): string => match ($state) {
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

            TextColumn::make('no_surat')
            ->searchable()
            ->label('No. Surat'),

            TextColumn::make('tanggal_pengajuan')
            ->searchable()
            ->label('Tanggal Pengajuan')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),


            TextColumn::make('nilai_pencairan')
            ->searchable()
            ->label('Nilai Pencairan')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

            TextColumn::make('catatan')
            ->searchable()
            ->label('Catatan'),


            TextColumn::make('up_surat_pengajuan')
            ->label('Surat Pengajuan')
            ->formatStateUsing(function ($record) {
                if (!$record->up_surat_pengajuan) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_surat_pengajuan) ? $record->up_surat_pengajuan : json_decode($record->up_surat_pengajuan, true);

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

        TextColumn::make('up_nominatif_pengajuan')
        ->label('Nominatif Pengajuan')
        ->formatStateUsing(function ($record) {
            if (!$record->up_nominatif_pengajuan) {
                return 'Tidak Ada Dokumen';
            }

            $files = is_array($record->up_nominatif_pengajuan) ? $record->up_nominatif_pengajuan : json_decode($record->up_nominatif_pengajuan, true);

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
                                ->title('Pengajuan Dajam Diubah')
                                ->body('Pengajuan Dajam telah berhasil disimpan.')),
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Pengajuan Dajam Dihapus')
                                ->body('Pengajuan Dajam telah berhasil dihapus.')),
                    RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Pengajuan Dajam')
                            ->body('Pengajuan Dajam berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Pengajuan Dajam')
                            ->body('Pengajuan Dajam berhasil dihapus secara permanen.')
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
                                ->title('Pengajuan Dajamg')
                                ->body('Pengajuan Dajam berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Pengajuan Dajam')
                                ->body('Pengajuan Dajam berhasil dihapus secara permanen.'))
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

                        return redirect()->route('pengajuandajam.print');
                    }),

                    RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Pengajuan Dajam')
                                ->body('Pengajuan Dajam berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Kavling, Blok, Bank, No. Debitur, Nama Konsumen, Nama Dajam, No. Surat, Tanggal Pengajuan, Nilai Pencairan, Catatan\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->kavling}, {$record->bank}, {$record->no_debitur}, {$record->nama_konsumen}, {$record->nama_dajam}, {$record->no_surat}, {$record->tanggal_pengajuan}, {$record->nilai_pencairan}, {$record->catatan}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'PengajuanDajam.csv');
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

    return $user->hasRole(['admin','Direksi','Legal officer','Super Admin', 'Legal Pajak']);
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
            'index' => Pages\ListGcvPengajuanDajams::route('/'),
            'create' => Pages\CreateGcvPengajuanDajam::route('/create'),
            'edit' => Pages\EditGcvPengajuanDajam::route('/{record}/edit'),
        ];
    }
}
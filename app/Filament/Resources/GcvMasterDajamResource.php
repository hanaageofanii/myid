<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvMasterDajamResource\Pages;
use App\Filament\Resources\GcvMasterDajamResource\RelationManagers;
use App\Models\GcvMasterDajam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use App\Models\gcv_uang_muka;
use App\Models\GcvUangMuka;
use App\Models\gcv_stok;
use App\Models\gcv_data_siteplan;
use App\Models\gcv_legalitas;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\BooleanColumn;
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
use App\Models\gcv_kpr;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Validation\Rule;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Filament\Tables\Columns\TextColumn;
use App\Models\gcv_pencairan_akad;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use App\Filament\Resources\GcvUangMukaResource\Widgets\gcv_uang_MukaStats;


class GcvMasterDajamResource extends Resource
{
    protected static ?string $model = GcvMasterDajam::class;

    protected static ?string $title = "Data Master Dajam";
    protected static ?string $pluralLabel = "Data Master Dajam";
    protected static ?string $navigationIcon = 'heroicon-o-folder-open';
    protected static ?string $navigationLabel = 'Legal > Data Master Dajam';
    protected static ?string $pluralModelLabel = 'Data Master Dajam';
    protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';

    protected static ?int $navigationSort = 19;

    public static function form(Form $form): Form
    {
return $form
            ->columns(1)
            ->extraAttributes(['class' => 'centered-container'])
->schema([
    Wizard::make([
        Wizard\Step::make('Data Konsumen')
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
                    ->label('Blok')
                    ->options(fn () => gcv_kpr::pluck('siteplan', 'siteplan'))
                    ->searchable()
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
                                return $user && $user->hasRole(['admin','Legal officer']);
                                })())
                                ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        $kprData = gcv_kpr::where('siteplan', $state)->first();
                        if ($kprData) {
                            $set('kavling', $kprData->jenis_unit);
                            $set('nama_konsumen', $kprData->nama_konsumen);
                            $set('nik', $kprData->nik);
                            $set('npwp', $kprData->npwp);
                            $set('alamat', $kprData->alamat);
                        }

                        $legalData = gcv_legalitas::where('siteplan', $state)->first();
                        if ($legalData) {
                            // $set('nop', $legalData->nop);
                        }
                    }),

                TextInput::make('nop')
                    ->nullable()
                    ->label('NOP')
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                                })())
                    ->reactive(),

                TextInput::make('nama_konsumen')
                    ->nullable()
                    ->label('Nama Konsumen')
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                                })())
                    ->reactive(),

                TextInput::make('nik')
                    ->nullable()
                    ->label('NIK')
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                                })())
                    ->reactive(),

                TextInput::make('npwp')
                    ->nullable()
                    ->label('NPWP')
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                                })())
                    ->reactive(),

                TextArea::make('alamat')
                    ->nullable()
                    ->label('Alamat')
                    ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                                })())
                    ->reactive(),
            ]),

        Wizard\Step::make('Data AJB')
            ->schema([
                TextInput::make('suket_validasi')
                ->nullable()
                ->label('No. Suket Validasi')
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),                
                TextInput::make('no_sspd_bphtb')
                ->nullable()
                ->label('No. SSPD BPHTB')
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),                DatePicker::make('tanggal_sspd_bphtb')
                ->nullable()
                ->label('Tanggal SSPD BPHTB')
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                TextInput::make('no_validasi_sspd')
                ->nullable()
                ->label('No. Validasi SSPD BPHTB')
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
        DatePicker::make('tanggal_validasi_sspd')
                ->nullable()
                ->label('Tanggal Validasi SSPD')
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
            TextInput::make('notaris')
                ->nullable()->label('Notaris')
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),                
                                TextInput::make('no_ajb')
                ->nullable()->label('No. AJB')
                ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                DatePicker::make('tanggal_ajb')
                ->nullable()
                ->label('Tanggal AJB')
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                TextInput::make('no_bast')
                ->nullable()
                ->label('No. Bast')
                ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                DatePicker::make('tanggal_bast')
                ->nullable()
                ->label('Tanggal Bast')
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),            ]),

        Wizard\Step::make('Dokumen')
            ->schema([
                FileUpload::make('up_validasi')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->label('Upload BPHTB')
                    ->downloadable()
                    ->previewable(false)
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),
                FileUpload::make('up_bast')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->label('Upload Bast')
                    ->downloadable()
                    ->previewable(false)
->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),            ]),
    ])
]);
}

    public static function table(Table $table): Table
    {
         return $table
        ->columns([
            Tables\Columns\TextColumn::make('kavling')
                ->label('Jenis Unit / Kavling')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('siteplan')
                ->label('Blok')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nama_konsumen')
                ->label('Nama Konsumen')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('nik')
                ->label('NIK')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('npwp')
                ->label('NPWP')
                ->sortable()
                ->searchable(),

            Tables\Columns\TextColumn::make('alamat')
                ->label('Alamat')
                ->limit(30)
                ,

            Tables\Columns\TextColumn::make('nop')
                ->label('NOP')
                ->sortable()
                ->searchable()
                ,

            Tables\Columns\TextColumn::make('suket_validasi')
                ->label('No. Suket Validasi')
                ,

            Tables\Columns\TextColumn::make('no_sspd_bphtb')
                ->label('No. SSPD BPHTB')
                ,

            Tables\Columns\TextColumn::make('tanggal_sspd_bphtb')
                ->label('Tanggal SSPD BPHTB')
                ->date()
                ->sortable()
                ,

            Tables\Columns\TextColumn::make('no_validasi_sspd')
                ->label('No. Validasi SSPD')
                ,

            Tables\Columns\TextColumn::make('tanggal_validasi_sspd')
                ->label('Tanggal Validasi SSPD')
                ->date()
                ->sortable()
                ,

            Tables\Columns\TextColumn::make('notaris')
                ->label('Notaris')
                ,

            Tables\Columns\TextColumn::make('no_ajb')
                ->label('No. AJB')
                ,

            Tables\Columns\TextColumn::make('tanggal_ajb')
                ->label('Tanggal AJB')
                ->date()
                ->sortable()
                ,

            // Tables\Columns\TextColumn::make('no_bast')
            //     ->label('No. Bast')
            //     ,

            // Tables\Columns\TextColumn::make('tanggal_bast')
            //     ->label('Tanggal Bast')
            //     ->date()
            //     ->sortable()
            //     ,

            TextColumn::make('up_validasi')
                ->label('File Validasi BPHTB')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_validasi) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_validasi) ? $record->up_validasi : json_decode($record->up_validasi, true);

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

                TextColumn::make('up_bast')
                ->label('File BAST')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_bast) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->up_bast) ? $record->up_bast : json_decode($record->up_bast, true);

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

        ]) ->defaultSort('siteplan', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make()
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
                Filter::make('status_bn')
                ->form([
                Select::make('status_bn')
                    ->options([
                        'sudah' => 'Sudah Selesai',
                        'belum' => 'Belum Selesai',
                    ])
                    ->nullable()->label('Status BN')
                    ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_bn']), fn ($q) =>
                            $q->where('status_bn', $data['status_bn'])
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
                                ->title('Data Master Dajam Diperbarui')
                                ->body('Data Master Dajam  telah berhasil disimpan.')),
                    DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Master Dajam  Dihapus')
                                        ->body('Data Master Dajam telah berhasil dihapus.')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Master Dajam')
                            ->body('Data Master Dajam berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Master Dajam')
                            ->body('Data Master Dajam  berhasil dihapus secara permanen.')
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
                                ->title('Data Master Dajam')
                                ->body('Data Master Dajam berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Master Dajam')
                                ->body('Data Master Dajam berhasil dihapus secara permanen.'))
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
                        // simpan data ke session agar bisa diakses di route print
                        session(['print_records' => $records->pluck('id')->toArray()]);

                        return redirect()->route('gcvmasterdajam.print');
                    }),
                    
                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Master Dajam')
                                ->body('Data Master Dajam berhasil dikembalikan.')),
                ]);

    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Site Plan, Kavling, Nama Konsumen, Luas, Harga Jual, Tanggal Lunas, NOP, Nama Notaris, PPH, PPN, BPHTB, Biaya Notaris, Adm. BPHTB, Catatan, Status BN\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->kavling}, {$record->nama_konsumen}, {$record->luas}, {$record->harga_jual}, {$record->tanggal_lunas}, {$record->nop}, {$record->nama_notaris}, {$record->pph}, {$record->ppn}, {$record->bphtb}, {$record->biaya_notaris}, {$record->adm_bphtb}, {$record->keterangan}, {$record->status_bn},\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'DataPengajuanBN.csv');
    }


    public static function getRelations(): array
    {
        return [

        ];
    }


public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ])
        ->where('team_id', filament()->getTenant()->id); // filter data sesuai tenant
}


        public static function getWidgets(): array
        {
            return [
                // gcvPengajuanBnStats::class,
            ];
        }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvMasterDajams::route('/'),
            'create' => Pages\CreateGcvMasterDajam::route('/create'),
            'edit' => Pages\EditGcvMasterDajam::route('/{record}/edit'),
        ];
    }
}
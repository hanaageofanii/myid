<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvStokResource\Pages;
use App\Filament\Resources\GcvStokResource\RelationManagers;
use App\Models\gcv_stok;
use App\Models\gcvDataSiteplan;
use App\Models\gcv_legalitas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
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
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Validation\Rule;
use App\Models\gcv_datatandaterima;
use Carbon\Carbon;
use Filament\Forms\Components\Repeater;
use App\Filament\Resources\GcvStokResource\Widgets\gcv_stokStats;

class GcvStokResource extends Resource
{
    protected static ?string $model = gcv_stok::class;

    protected static ?string $title = "Data Bookingan";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Bookingan";
    protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
    protected static ?string $navigationLabel = 'Stok > Data Booking';
    protected static ?string $pluralModelLabel = 'Data Bookingan';
    protected static ?int $navigationSort = 4;
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Wizard::make([
                Step::make('Data Proyek')
                ->description('Informasi data proyek')
                ->schema([
                Select::make('proyek')
                ->options([
                    'gcv_cira' => 'GCV Cira',
                    'gcv' => 'GCV',
                    'tkr_cira' => 'TKR Cira',
                    'tkr' => 'TKR',
                    'pca1' => 'PCA1',
                ])
                ->label('Proyek')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),

            Select::make('nama_perusahaan')
                ->options([
                    'grand_cikarang_village' => 'Grand Cikarang Village',
                    'taman_kertamukti_residence' => 'Taman Kertamukti Residence',
                    'pesona_cengkong_asri_1' => 'Pesona Cengkong Asri 1',
                ])
                ->label('Nama Perusahaan')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),

            Select::make('kavling')
                ->options([
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios'
                ])
                ->label('Kavling')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),

            Select::make('siteplan')
                ->label('Blok')
                ->options(
                    gcvDataSiteplan::where('terbangun', 1)->pluck('siteplan', 'siteplan')->toArray()
                )
                ->searchable()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, $set) {
                    $audit = gcvDataSiteplan::where('siteplan', $state)->first();
                    $legalitas =gcv_legalitas::where( 'siteplan', $state)->first();

                    if ($audit) {
                        $set('type', $audit->type);
                        $set('luas_tanah', $audit->luas);
                    }

                    if($legalitas){
                        $set('status_sertifikat', $legalitas->status_sertifikat);
                    }


                })
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),

            TextInput::make('type')
                ->label('Type')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),

            TextInput::make('luas_tanah')
                ->numeric()
                ->label('Luas Tanah')
                ->required()
                ->disabled(fn () => ! (function () {
                                        /** @var \App\Models\User|null $user */
                                        $user = Auth::user();
                                        return $user && $user->hasRole(['admin','KPR Officer']);
                                    })()),
                                ])->columns(2),

        Step::make('Status Bookingan')
        ->description('Informasi data booking')
        ->schema([
            Select::make('status')
                ->options(['booking' => 'Booking'])
                ->label('Status')
                ->afterStateUpdated(function ($state, $set, $record) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        if (is_null($state) && $user && $user->hasRole(['Direksi', 'Super admin','admin'])) {
                            $set('tanggal_booking', null);
                            $set('nama_konsumen', null);
                            $set('agent', null);

                            $record->update([
                                'tanggal_booking' => null,
                                'nama_konsumen' => null,
                                'agent' => null
                            ]);
                        }
                    })
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

            DatePicker::make('tanggal_booking')
                ->label('Tanggal Booking')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

            TextInput::make('nama_konsumen')
                ->label('Nama Konsumen')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

            TextInput::make('agent')
                ->label('Agent')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),        ]),

        Step::make('Legalitas')
        ->description('Informasi data legalitas')
        ->schema([
            Select::make('status_sertifikat')
                ->options([
                    'induk' => 'Induk',
                    'pecah' => 'Pecah',
                ])
                ->label('Status Sertifikat')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
            Select::make('status_pembayaran')
                ->options([
                    'cash' => 'CASH',
                    'kpr' => 'KPR',
                    'cash_bertahap' => 'CASH BERTAHAP',
                    'promo' => 'PROMO',
                ])
                ->label('Status Pembayaran')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
                ])->columns(2),

        Step::make('Status KPR')
        ->description('Informasi status kpr')
        ->schema([
            Select::make('kpr_status')
                ->options([
                    'sp3k' => 'SP3K',
                    'akad' => 'Akad',
                    'batal' => 'Batal',
                ])
                ->afterStateUpdated(function ($state, $set, $get, $record) {
                        if ($record && $record->siteplan) {
                            $audit = gcv_datatandaterima::where('siteplan', $record->siteplan)->first();

                            if ($audit) {
                                $audit->update([
                                    'status' => $state === 'akad' ? 'akad' : null,
                                ]);
                            }
                        }
                    })
                    ->afterStateUpdated(function ($state, $set, $record) {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        if (is_null($state) && $user && $user->hasRole(['Direksi', 'Super admin','admin','KPR Stok'])) {
                            $set('tanggal_akad', null);


                            $record->update([
                                'tanggal_akad' => null,
                            ]);
                        }
                    })
                ->label('KPR Status')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

            DatePicker::make('tanggal_akad')
                ->label('Tanggal Akad')
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),

            Textarea::make('ket')
                ->label('Keterangan')
                ->nullable()->columnSpanFull()
                ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','KPR Officer']);
                    })()),
                ]),
    ])
    ->columnSpanFull()
    ->columns(2)
]
            );
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
               Tables\Columns\TextColumn::make('proyek')->label('Proyek')
            ->formatStateUsing(fn (string $state): string => match ($state)
            {
                    'gcv_cira' => 'GCV Cira',
                    'gcv' => 'GCV',
                    'tkr_cira' => 'TKR Cira',
                    'tkr' => 'TKR',
                    'pca1' => 'PCA1',
                    default => $state,
            })->searchable()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole('admin');
            })()),
            Tables\Columns\TextColumn::make('nama_perusahaan')->label('Nama Perumahan')
            ->formatStateUsing(fn (string $state): string => match ($state) {
                'grand_cikarang_village' => 'Grand Cikarang Village',
                'taman_kertamukti_residence' => 'Taman Kertamukti Residence',
                'pesona_cengkong_asri_1' => 'Pesona Cengkong Asri 1',
                default => $state,
            })->searchable(),

            Tables\Columns\TextColumn::make('kavling')->label('Kavling')
            ->formatStateUsing(fn (string $state): string => match ($state) {
                'standar' => 'Standar',
                'khusus' => 'Khusus',
                'hook' => 'Hook',
                'komersil' => 'Komersil',
                'tanah_lebih' => 'Tanah Lebih',
                'kios' => 'Kios',
                default => $state,
            })->searchable(),

            Tables\Columns\TextColumn::make('siteplan')->label('Blok')->searchable(),
            Tables\Columns\TextColumn::make('type')->label('Type')->searchable(),
            Tables\Columns\TextColumn::make('luas_tanah')->label('Luas Tanah')->searchable(),
            Tables\Columns\TextColumn::make('status')->label('Status')
            ->formatStateUsing(fn (string $state): string => match ($state) {
                'booking' => 'Booking',
                default => $state,
            })->searchable(),

            Tables\Columns\TextColumn::make('tanggal_booking')->date()->label('Tanggal Booking')->searchable()
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            Tables\Columns\TextColumn::make('nama_konsumen')->label('Nama Konsumen')->searchable(),
            Tables\Columns\TextColumn::make('agent')->label('Agent')->searchable(),
            Tables\Columns\TextColumn::make('status_sertifikat')->label('Status Sertifikat')
            ->formatStateUsing(fn (string $state): string => match ($state) {
                'induk' => 'Induk',
                'pecah' => 'Pecahan',
                default => $state,
            })->searchable(),
            Tables\Columns\TextColumn::make('status_pembayaran')->label('Status Pembayaran')
            ->formatStateUsing(fn (string $state): string => match ($state) {
                'cash' => 'CASH',
                'kpr' => 'KPR',
                'cash_bertahap' => 'CASH BERTAHAP',
                'promo' => 'PROMO',
                default => $state,
            })->searchable(),
            Tables\Columns\TextColumn::make('kpr_status')
                ->label('KPR Status')
                ->default(fn ($record) => $record->audits?->status === 'akad' ? 'Akad' : $record->kpr_status)
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'sp3k' => 'SP3K',
                    'akad' => 'Akad',
                    'batal' => 'Batal',
                    default => $state,
                })->searchable(),
            Tables\Columns\TextColumn::make('tanggal_akad')->date()->label('Tanggal Akad')->searchable()
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
        ])
            ->defaultSort('siteplan', 'asc')
        ->headerActions([
            Action::make('count')
            ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
            ->disabled(),
        ])
        ->filters([
           Tables\Filters\Filter::make('booking')
                    ->label('Terbooking')
                    ->query(fn ($query) => $query->where('status','booking'))
                    ->toggle(),

                     Tables\Filters\Filter::make('belum_terbooking')
                    ->label('Belum Terbooking')
                    ->query(fn ($query) => $query->whereNull('status'))
                    ->toggle(),

            Tables\Filters\TrashedFilter::make()
                ->label('Data yang dihapus')
                ->native(false),

            Tables\Filters\SelectFilter::make('kavling')
                    ->label('Jenis Unit')
                    ->options([
                        'standar' => 'Standar',
                        'khusus' => 'Khusus',
                        'hook' => 'Hook',
                        'komersil' => 'Komersil',
                        'tanah_lebih' => 'Tanah Lebih',
                        'kios' => 'Kios',
                    ])
                    ->native(false),

            Tables\Filters\SelectFilter::make('kpr_status')
                ->label('Status KPR')
                ->options([
                    'sp3k' => 'SP3K',
                    'akad' => 'Akad',
                    'batal' => 'Batal',
                ])
                ->native(false),

            Tables\Filters\SelectFilter::make('status_sertifikat')
                ->label('Status Sertifikat')
                ->options([
                    'pecah' => 'Sudah Pecah',
                    'belum' => 'Belum Pecah',
                ])
                ->native(false),

            Tables\Filters\SelectFilter::make('status_pembayaran')
                ->label('Status Pembayaran')
                ->options([
                    'cash' => 'Cash',
                    'kpr' => 'KPR',
                    'cash_bertahap' => 'Cash Bertahap',
                    'promo' => 'Promo',
                ])
                ->native(false),


            Tables\Filters\SelectFilter::make('proyek')
                ->label('Proyek')
                ->options([
                    'tkr_cira' => 'TKR Cira',
                    'tkr' => 'TKR',
                ])
                ->native(false),
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
                            ->title('Data Bookingan Diperbarui')
                            ->body('Data Bookingan telah berhasil disimpan.')),
                            DeleteAction::make()
                            ->color('danger')
                            ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                            ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                            ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                            ->successNotification(
                                Notification::make()
                                    ->success()
                                    ->title('Data Bookingan Dihapus')
                                    ->body('Data Bookingan telah berhasil dihapus.')),
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
                        ->title('Data Bookingan')
                        ->body('Data Bookingan berhasil dikembalikan.')
                ),
                Tables\Actions\ForceDeleteAction::make()
                ->color('primary')
                ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                ->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Data Bookingan')
                        ->body('Data Bookingan berhasil dihapus secara permanen.')
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
                            ->title('Data Bookingan')
                            ->body('Data Bookingan berhasil dihapus.'))
                            ->requiresConfirmation()
                    ->action(fn (Collection $records) => $records->each->delete()),

                BulkAction::make('forceDelete')
                    ->label('Hapus Permanent')
                    ->icon('heroicon-o-x-circle')
                    ->color('warning')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Bookingan')
                            ->body('Data Bookingan berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
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
                            ->title('Data Bookingan')
                            ->body('Data Bookingan berhasil dikembalikan.')),
            ]);

}

public static function getRelations(): array
{
    return [];
}

public static function exportData(Collection $records)
{
    $csvData = "ID, Proyek, Nama Perumahan, Kavling, Siteplan/Blok, Type, Luas Tanah, Status, Tanggal Booking, Nama Konsumen, Agent, Status KPR, Keterangan, User, Tanggal Update\n";

    foreach ($records as $record) {
        $csvData .= "{$record->id}, {$record->proyek}, {$record->nama_perusahaan}, {$record->kavling}, {$record->siteplan}, {$record->type}, {$record->luas_tanah}, {$record->status}, {$record->tanggal_booking}, {$record->nama_konsumen}, {$record->agent}, {$record->kpr_status}, {$record->ket}, {$record->user}, {$record->tanggal_update}\n";
    }

    return response()->streamDownload(fn () => print($csvData), 'TKR.csv');
}

public static function getEloquentQuery(): Builder
{
$query = parent::getEloquentQuery()
    ->withoutGlobalScopes([
        SoftDeletingScope::class,
    ]);
/** @var \App\Models\User|null $user */
$user = Auth::user();

if ($user) {
    if ($user->hasRole('Marketing')) {
        $query->where(function ($q) {
            // HANYA yang belum booking
            $q->where(function ($qStatus) {
                $qStatus->whereNull('status')
                        ->orWhere('status', '!=', 'booking');
            })
            // DAN kpr_status bukan akad atau batal sp3k
            ->where(function ($qKpr) {
                $qKpr->whereNull('kpr_status')
                     ->orWhereNotIn('kpr_status', ['akad', 'batal',' sp3k']);
            });
        });
    } elseif ($user->hasRole(['Legal officer', 'Legal Pajak'])) {
        // Legal tetap khusus yg sudah akad
        $query->where('kpr_status', 'akad');
    }
}


return $query;
}


public static function getWidgets(): array
{
    return [
        gcv_stokStats::class,
    ];
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvStoks::route('/'),
            'create' => Pages\CreateGcvStok::route('/create'),
            'edit' => Pages\EditGcvStok::route('/{record}/edit'),
        ];
    }
}

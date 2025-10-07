<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvPengajuanBnResource\Pages;
use App\Filament\Resources\GcvPengajuanBnResource\RelationManagers;
use App\Models\gcv_pengajuan_bn;
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
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextInputColumn;
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
use App\Filament\Resources\GcvPengajuanBnResource\Widgets\gcvPengajuanBnStats;
use App\Models\gcv_validasi_pph;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use Carbon\Carbon;

class GcvPengajuanBnResource extends Resource
{
    protected static ?string $model = gcv_pengajuan_bn::class;

    protected static ?string $title = "Data Pengajuan BN";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Pengajuan BN";
    protected static ?string $navigationLabel = 'Legal > Pengajuan BN';
    protected static ?string $pluralModelLabel = 'Data Pengajuan BN';
    protected static ?int $navigationSort = 17;
     protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';

    protected static ?string $navigationIcon = 'heroicon-o-globe-americas';

    public static function form(Form $form): Form
    {
        return $form
        // ->emptyStateActions([
        //     Action::make('create')
        //         ->label('Create post')
        //         ->url(route('posts.create'))
        //         ->icon('heroicon-m-plus')
        //         ->button(),
        // ])
            ->schema([
                Wizard::make([
                    Step::make('Data Konsumen')
                    ->columns(2)
                    ->description('Informasi Data Konsumen')
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
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),

                        Select::make('siteplan')
                            ->label('Blok')
                            ->reactive()
                            ->dehydrated()
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
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })())
                        ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                $kprData = gcv_kpr::where('siteplan', $state)->first();
                                $validasipph = gcv_validasi_pph::where('siteplan', $state)->first();
                                $datatandaterima = gcv_datatandaterima::where('siteplan', $state)->first();
                                $set('status_bn', $datatandaterima?->status_bn);
                                $set('nama_konsumen', $kprData?->nama_konsumen);
                                $set('luas', $kprData?->luas);
                                $set('nama_notaris', $validasipph?->nama_notaris);
                                $set('nop', $validasipph?->nop);
                                $set('harga_jual', $kprData?->harga);
                            }),

                        TextInput::make('nama_konsumen')
                        ->label('Nama Konsumen')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),

                        TextInput::make('luas')
                        ->label('Luas')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })),
                    ]),
                        Step::make('Informasi Harga')
                        ->columns(2)
                        ->description('Informasi Harga Unit KPR')
                        ->schema([
                            TextInput::make('harga_jual')
                            ->label('Harga Jual')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            DatePicker::make('tanggal_lunas')
                            ->label('Tanggal Lunas')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextInput::make('nop')
                            ->label('NOP')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextInput::make('nama_notaris')
                            ->label('Nama Notaris')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            }))
                        ]),

                        Step::make('Informasi Lanjutan')
                        ->columns(3)
                        ->description('Informasi lanjutan mengenai Pajak & Legal')
                        ->schema([
                            TextInput::make('pph')
                            ->label('PPH')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextInput::make('ppn')
                            ->label('PPN')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextInput::make('bphtb')
                            ->label('BPHTB')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextInput::make('biaya_notaris')
                            ->label('Biaya Notaris')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextInput::make('adm_bphtb')
                            ->label('Adm. BPHTB')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),

                            TextArea::make('catatan')
                            ->label('Catatan')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })),
                        ]),

                        Step::make('Informaasi Dokumen')
                        ->description('Upload Dokumen Penting')
                        ->schema([
                        Section::make('Dokumen Pendukung')
                        ->columns(3)
                            ->schema([
                                FileUpload::make('up_dokumen')->label('Upload Dokumen')->disk('public')->multiple()->nullable()->previewable(false)->downloadable()->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal fficer']);
                            })())->columnSpanFull(),
                        ]),

                        Select::make('status_bn')
                            ->label('Status BN')
                            ->options([
                                'sudah' => 'Sudah Selesai',
                                'belum' => 'Belum Selesai',
                            ])
                            ->columnSpanFull()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })())
                            ->afterStateUpdated(function ($state, callable $get, $set) {
                                $siteplan = $get('siteplan');

                                if ($siteplan) {
                                    \App\Models\gcv_datatandaterima::where('siteplan', $siteplan)
                                        ->update(['status_bn' => $state]);
                                }
                            }),
                    ])
                ])->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                SelectColumn::make('kavling')
                ->label('Kavling')
                ->Searchable()->sortable()
                ->rules(['required'])
                ->selectablePlaceholder(true)
                ->options([
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                ]),

                TextColumn::make('siteplan')
                ->label('Blok')
                ->searchable()->sortable(),

                TextColumn::make('nama_konsumen')
                ->label('Nama Konsumen')
                ->searchable()->sortable(),

                TextColumn::make('luas')
                ->label('Luas')
                ->searchable()->sortable()
                ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),

                TextColumn::make('harga_jual')
                ->label('Harga Jual')
                ->searchable()->sortable()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),

                TextColumn::make('tanggal_lunas')
                ->label('Tanggal Lunas')
                ->searchable()->sortable()
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

                TextColumn::make('nop')
                ->label('NOP')
                ->searchable()->sortable(),

                TextColumn::make('nama_notaris')
                ->label('Nama Notaris')
                ->searchable()->sortable(),

                TextColumn::make('pph')
                ->label('PPH')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->searchable()->sortable(),

                TextColumn::make('ppn')
                ->label('PPN')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->searchable()->sortable(),

                TextColumn::make('bphtb')
                ->label('BPHTB')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->searchable()->sortable(),

                TextColumn::make('biaya_notaris')
                ->label('Biaya Notaris')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                ->searchable()->sortable(),

                TextColumn::make('catatan')
                ->label('Catatan')
                ->searchable()
                ->sortable(),

                TextColumn::make('status_bn')
                ->label('Status BN')
                ->searchable()
                ->color(fn (string $state)  => match ($state){
                    'sudah' => 'success',
                    'belum' => 'danger',
                })
                ->formatStateUsing(fn ($state) => match ($state){
                    'sudah' => 'Sudah Selesai',
                    'belum' => 'Belum Selesai'
                })
                ->badge()
                ->sortable(),

                TextColumn::make('up_dokumen')
                    ->label('File Dokumen')
                    ->formatStateUsing(function ($record) {
                        if (!$record->up_dokumen) {
                            return 'Tidak Ada Dokumen';
                        }

                        $files = is_array($record->up_dokumen) ? $record->up_dokumen : json_decode($record->up_dokumen, true);

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
                                ->title('Data Pengajuan BN Diperbarui')
                                ->body('Data Pengajuan BN  telah berhasil disimpan.')),
                    DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok {$record->siteplan}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Data Pengajuan BN  Dihapus')
                                        ->body('Data Pengajuan BN telah berhasil dihapus.')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pengajuan BN')
                            ->body('Data Pengajuan BN berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pengajuan BN')
                            ->body('Data Pengajuan BN  berhasil dihapus secara permanen.')
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
                                ->title('Data Pengajuan BN')
                                ->body('Data Pengajuan BN berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pengajuan BN')
                                ->body('Data Pengajuan BN berhasil dihapus secara permanen.'))
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

                        return redirect()->route('pengajuanbn.print');
                    }),

                    Tables\Actions\RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pengajuan BN')
                                ->body('Data Pengajuan BN berhasil dikembalikan.')),
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
                gcvPengajuanBnStats::class,
            ];
        }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvPengajuanBns::route('/'),
            'create' => Pages\CreateGcvPengajuanBn::route('/create'),
            'edit' => Pages\EditGcvPengajuanBn::route('/{record}/edit'),
        ];
    }
}
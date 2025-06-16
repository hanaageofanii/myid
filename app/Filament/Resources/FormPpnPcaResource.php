<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormPpnPcaResource\Pages;
use App\Filament\Resources\FormPpnPcaResource\RelationManagers;
use App\Models\form_ppn_pca;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\form_kpr_pca;
use App\Models\form_legal_pca;
use App\Models\form_pajak_pca;
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
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
use Filament\Forms\Components\FileUpload;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class FormPpnPcaResource extends Resource
{
    protected static ?string $model = form_ppn_pca::class;

    protected static ?string $title = "Form Data Faktur PPN PCA";
    protected static ?string $navigationGroup = "Legal - Pajak";
    protected static ?string $pluralLabel = "Data Faktur PPN PCA";
    protected static ?string $navigationLabel = "Faktur PPN PCA";
    protected static ?string $pluralModelLabel = 'Daftar Faktur PPN PCA';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('siteplan')
                ->label('Blok')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->options(fn() => form_kpr_pca::pluck('siteplan', 'siteplan')->toArray())
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $kprData = form_kpr_pca::where('siteplan', $state)->first();

                    if ($kprData) {
                        $set('kavling', $kprData->jenis_unit);
                        $set('nama_konsumen', $kprData->nama_konsumen);
                        $set('nik', $kprData->nik);
                        $set('npwp', $kprData->npwp);
                        $set('alamat', $kprData->alamat);
                        $set('harga_jual', $kprData->harga);

                        $harga = $kprData->harga ?? 0;

                        // Perhitungan DPP PPN
                        $dpp_ppn = $harga * (11 / 12);
                        $set('dpp_ppn', $dpp_ppn);

                        $tarif_ppn_raw = $get('tarif_ppn') ?? '0%';
                        $tarif_ppn = (float) str_replace('%', '', $tarif_ppn_raw) / 100;

                        // Hitung JUMLAH PPN
                        $jumlah_ppn = $harga * $tarif_ppn;
                        $set('jumlah_ppn', $jumlah_ppn);
                    }
                }),

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
            ->nullable()
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
            ->nullable()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal Pajak']);
            })())
            ->label('Alamat'),

            Forms\Components\TextInput::make('no_seri_faktur')
            ->nullable()
            ->label('No. Seri Faktur')
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal Pajak']);
            })()),

            Forms\Components\DatePicker::make('tanggal_faktur')
            ->nullable()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal Pajak']);
            })())
            ->label('Tanggal Faktur'),

            Forms\Components\TextInput::make('harga_jual')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Harga Jual')
                ->prefix('Rp'),

            Forms\Components\TextInput::make('dpp_ppn')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('DPP PPN')
                ->prefix('Rp'),

            Forms\Components\Select::make('tarif_ppn')
                ->options([
                    '11%' => '11 %',
                    '12%' => '12 %',
                ])
                ->required()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->reactive()
                ->nullable()
                ->label('Tarif PPN')
                ->afterStateUpdated(function (callable $set, callable $get) {
                    $harga = (float) str_replace(['.', ','], ['', ''], $get('harga_jual') ?? '0');

                    $tarif_ppn_raw = $get('tarif_ppn') ?? '0%';
                    $tarif_ppn = (float) str_replace('%', '', $tarif_ppn_raw) / 100;
                    if (is_numeric($harga) && is_numeric($tarif_ppn)) {
                        $jumlah_ppn = $harga * $tarif_ppn;
                    } else {
                        $jumlah_ppn = 0;
                    }

                    $set('jumlah_ppn', $jumlah_ppn);
                }),

            Forms\Components\TextInput::make('jumlah_ppn')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Jumlah PPN')
                ->prefix('Rp'),


                Forms\Components\Select::make('status_ppn')
                ->options([
                    'dtp' => 'DTP',
                    'dtp_sebagian' => 'DTP Sebagian',
                    'dibebaskan' => 'Dibebaskan',
                    'bayar' => 'Bayar',
                ])
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Status PPN')
                ->searchable()
                ->native(false)
                ->reactive(),
            
            Forms\Components\DatePicker::make('tanggal_bayar_ppn')
                ->nullable()
                ->disabled(fn () => ! (function () {
                    /** @var \App\Models\User|null $user */
                    $user = Auth::user();
                    return $user && $user->hasRole(['admin','Legal Pajak']);
                })())
                ->label('Tanggal Faktur')
                ->required(fn (callable $get) => $get('status_ppn') === 'bayar'),
                            
            Forms\Components\TextInput::make('ntpn_ppn')
            ->nullable()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal Pajak']);
            })())
            ->label('BTPN PPN'),

            Forms\Components\Fieldset::make('Dokumen')
                ->schema([
                    Forms\Components\FileUpload::make('up_bukti_setor_ppn')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Upload Bukti Setor PPN')
                        ->downloadable()
                        ->previewable(false),
            
                    Forms\Components\FileUpload::make('up_efaktur')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Upload E-Faktur')
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
                ->label('Setor PPN')
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
                ->label('E-Faktur')
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
        $csvData = "ID,  Blok, Nama Konsumen, NIK, NPWP, Alamat, No. Seri Faktur, Tanggal Faktur, Harga Jual, DPP PPN, Tarif PPN, Jumlah PPN, Status PPN, Tanggal Bayar PPN, NTPN PPN\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->nama_konsumen}, {$record->nik}, {$record->npwp}, {$record->alamat}, {$record->no_seri_faktur}, {$record->harga_jual}, {$record->dpp_ppn}, {$record->tarif_ppn}, {$record->jumlah_ppn}, {$record->status_ppn}, {$record->tanggal_bayar_ppn}, {$record->ntpn_ppn}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'PPN.csv');
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
            'index' => Pages\ListFormPpnPcas::route('/'),
            'create' => Pages\CreateFormPpnPca::route('/create'),
            'edit' => Pages\EditFormPpnPca::route('/{record}/edit'),
        ];
    }
}

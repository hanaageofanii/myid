<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormPencocokanResource\Pages;
use App\Filament\Resources\FormPencocokanResource\RelationManagers;
use App\Models\form_pencocokan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\rekening_koran;
use App\Models\Rekonsil;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\form_dp;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Enums\ActionsPosition;
use Illuminate\Database\Eloquent\Collection;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Enums\FiltersLayout;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Grid;
use Filament\Support\Enums\MaxWidth;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasRoles;


class FormPencocokanResource extends Resource
{
    protected static ?string $model = form_pencocokan::class;

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Validasi Data Rekening";
    protected static ?string $navigationLabel = "Validasi Data Rekening";
    protected static ?string $pluralModelLabel = 'Daftar Validasi Data Rekening';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Informasi Transaksi')
            ->schema([
                Select::make('no_transaksi')
            ->label('No. Transaksi')
            ->options(fn () => rekonsil::pluck('no_transaksi', 'no_transaksi'))
            ->searchable()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal officer']);
            })())
            ->reactive()
            ->afterStateUpdated(function ($state, callable $set) {
                $rekonsil = \App\Models\rekonsil::where('no_transaksi', $state)->first();

                if ($rekonsil) {
                    $set('nama_pencair', $rekonsil->nama_yang_mencairkan);
                    $set('tanggal_dicairkan', $rekonsil->tanggal_diterima);
                    $set('nama_penerima', $rekonsil->nama_penerima);
                    $set('tanggal_penerima', $rekonsil->tanggal_transaksi);
                }
            })
            ->unique(ignoreRecord: true),



        Select::make('no_ref_bank')
            ->label('No. Ref Bank')
            ->options(fn () => rekening_koran::pluck('no_referensi_bank', 'no_referensi_bank'))
            ->searchable()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->reactive()
            ->unique(ignoreRecord: true),


        DatePicker::make('tanggal_transaksi')
            ->required()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Tanggal Transaksi'),

        TextInput::make('jumlah')
            ->required()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Jumlah')
            ->prefix('Rp'),

        TextInput::make('tujuan_dana')
            ->required()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Tujuan Dana'),

        Select::make('tipe')
            ->options([
                'debit' => 'Debit',
                'kredit' => 'Kredit',
            ])
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Tipe')
            ->required(),
    ]),

Fieldset::make('Pencairan dan Penerima')
    ->schema([
        TextInput::make('nama_pencair')
            ->required()
            ->reactive()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Nama Pencair'),

        DatePicker::make('tanggal_dicairkan')
            ->required()
            ->reactive()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Tanggal di Cairkan'),

        TextInput::make('nama_penerima')
            ->required()
            ->reactive()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Nama Penerima'),

        DatePicker::make('tanggal_penerima')
            ->required()
            ->reactive()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Tanggal Penerima'),
    ]),

Fieldset::make('Status dan Analisis Selisih')
    ->schema([
        Select::make('status_disalurkan')
            ->options([
                'sudah' => 'Sudah',
                'belum' => 'Belum',
            ])
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Status di Salurkan')
            ->required(),

        TextInput::make('nominal_selisih')
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Nominal Selisih')
            ->prefix('Rp'),

        TextArea::make('analisis_selisih')
            // ->required()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Analisis Selisih'),

        Select::make('tindakan')
            ->options([
                'koreksi' => 'Koreksi',
                'pending' => 'Pending',
                'abaikan' => 'Abaikan',
            ])
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->label('Tindakan')
            ->required(),
    ]),

Fieldset::make('Dokumen Pendukung')
    ->schema([
        FileUpload::make('bukti_pendukung')
            ->disk('public')
            ->multiple()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->nullable()
            ->label('Bukti Pendukung di Terima')
            ->downloadable()
            ->previewable(false),

        FileUpload::make('bukti_bukti')
            ->disk('public')
            ->multiple()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Kasir 2']);
            })())
            ->nullable()
            ->label('Bukti-bukti Lainnya')
            ->downloadable()
            ->previewable(false),
    ]),

    Fieldset::make('Validasi dan Catatan')
    ->schema([
        DatePicker::make('tanggal_validasi')
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Direksi']);
            })())
            ->label('Tanggal Validasi'),

        TextInput::make('disetujui_oleh')
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Direksi']);
            })())
            ->label('Disetujui Oleh'),

            Select::make('status')
            ->options([
                'approve' => 'Approve',
                'revisi' => 'Revisi',
            ])
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Direksi']);
            })())
            ->label('Status Validasi'),

        TextArea::make('catatan')
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Direksi']);
            })())
            ->label('Catatan'),
    ]),
]);
}


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_transaksi')
                ->searchable()
                ->label('No. Transaksi'),

                TextColumn::make('no_ref_bank')
                ->searchable()
                ->label('No. Referensi Bank'),

                TextColumn::make('tanggal_transaksi')
                ->searchable()
                ->label('Tanggal Transaksi')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

                TextColumn::make('nama_pencair')
                ->searchable()
                ->label('Nama Pencair'),

                TextColumn::make('tanggal_dicairkan')
                ->searchable()
                ->label('Tanggal di Cairkan')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

                TextColumn::make('nama_penerima')
                ->searchable()
                ->label('Nama Penerima'),

                TextColumn::make('tanggal_diterima')
                ->searchable()
                ->label('Tanggal di Terima')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

                TextColumn::make('jumlah')
                ->searchable()
                ->label('Jumlah'),

                TextColumn::make('tujuan_dana')
                ->searchable()
                ->label('Tujuan Dana'),

                TextColumn::make('status_disalurkan')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'sudah' => 'Sudah',
                    'belum' => 'Belum',                            
                default => ucfirst($state),
            })
            ->sortable()
            ->searchable()
            ->label('Status di Salurkan'),

            TextColumn::make('bukti_pendukung')
                ->label('Bukti Pendukung')
                ->formatStateUsing(function ($record) {
                    if (!$record->bukti_pendukung) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->bukti_pendukung) ? $record->bukti_pendukung : json_decode($record->bukti_pendukung, true);

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

                TextColumn::make('tipe')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'debit' => 'Debit',
                    'kredit' => 'Kredit',                            
                default => ucfirst($state),
            })
            ->sortable()
            ->searchable()
            ->label('Tipe'),

            TextColumn::make('status')
            ->formatStateUsing(fn (string $state): string => match ($state) {
                'approve' => 'Approve',
                'revisi' => 'Revisi',                           
            default => ucfirst($state),
                })
                ->sortable()
                ->searchable()
                ->label('Status Validasi'),

                TextColumn::make('nominal_selisih')
                ->label('Nominal Selisih')
                ->searchable(),

                TextColumn::make('analisis_selisih')
                ->label('Analisis Selisih')
                ->searchable(),

                TextColumn::make('tindakan')
            ->formatStateUsing(fn (string $state): string => match ($state) {
                'koreksi' => 'Koreksi',
                'pending' => 'Pending',
                'abaikan' => 'Abaikan',
            default => ucfirst($state),
                })
                ->sortable()
                ->searchable()
                ->label('Tindakan'),

                TextColumn::make('tanggal_validasi')
                ->searchable()
                ->label('Tanggal Validasi')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

                TextColumn::make('disetujui_oleh')
                ->searchable()
                ->label('Disetujui Oleh'),

                TextColumn::make('catatan')
                ->label('Catatan')
                ->searchable(),

                TextColumn::make('bukti_bukti')
                ->label('Bukti Bukti Lainnya')
                ->formatStateUsing(function ($record) {
                    if (!$record->bukti_bukti) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->bukti_bukti) ? $record->bukti_bukti : json_decode($record->bukti_bukti, true);

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
            ->defaultSort('no_transaksi', 'asc')
            ->headerActions([
                Action::make('count')
                    ->label(fn ($livewire): string => 'Total: ' . $livewire->getFilteredTableQuery()->count())
                    ->disabled(),
            ])
            ->filters([
                TrashedFilter::make()
                    ->label('Data yang dihapus') 
                    ->native(false),

                Filter::make('status_disalurkan')
                    ->label('Status di Salurkan')
                    ->form([
                        Select::make('status_disalurkan')
                            ->options([
                                'sudah' => 'Sudah',
                                'belum' => 'Belum',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_disalurkan']), fn ($q) =>
                            $q->where('status_disalurkan', $data['status_disalurkan'])
                        )
                    ),
            
                Filter::make('tipe')
                    ->label('Tipe')
                    ->form([
                        Select::make('tipe')
                            ->options([
                                'debit' => 'Debit',
                                'kredit' => 'Kredit',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['tipe']), fn ($q) =>
                            $q->where('tipe', $data['tipe'])
                        )
                    ),

                    Filter::make('tindakan')
                    ->form([
                        Select::make('tindakan')
                            ->options([
                                'koreksi' => 'Koreksi',
                                'pending' => 'Pending',
                                'abaikan' => 'Abaikan',
                            ])
                            ->nullable()
                            ->label('Tindakan')
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['tindakan']), fn ($q) =>
                            $q->where('tindakan', $data['tindakan'])
                        )
                    ),

                    Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->options([
                            'approve' => 'Approve',
                            'revisi' => 'Revisi',
                            ])
                            ->nullable()
                            ->label('Status Validasi')
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status']), fn ($q) =>
                            $q->where('status', $data['status'])
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
                                ->title('Data Pengecekan Diubah')
                                ->body('Data Pengecekan telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Nomor {$record->no_ref_bank}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Nomor{$record->no_ref_bank}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus nomor {$record->no_ref_bank}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pengecekan Dihapus')
                                ->body('Data Pengecekan telah berhasil dihapus.')),                         
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    RestoreAction::make()
                    ->color('info')
                    ->label('Kembalikan Data')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pengecekan')
                            ->body('Data Pengecekan berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label('Hapus Permanen')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pengecekan')
                            ->body('Data Pengecekan berhasil dihapus secara permanen.')
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
                                ->title('Data Pengecekan')
                                ->body('Data Pengecekan berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pengecekan')
                                ->body('Data Pengecekan berhasil dihapus secara permanen.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->forceDelete()),
                
                    BulkAction::make('export')
                        ->label('Download Data')
                        ->icon('heroicon-o-arrow-down-tray') 
                        ->color('info')
                        ->action(fn (Collection $records) => static::exportData($records)),
                
                    RestoreBulkAction::make()
                        ->label('Kembalikan Data')
                        ->icon('heroicon-o-arrow-path')
                        ->color('success')
                        ->button()
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pengecekan')
                                ->body('Data Pengecekan berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
{
    $csvData = "ID, No. Transaksi, No. Referensi Bank, Tanggal Transaksi, Nama Pencair, Tanggal di Cairkan, Nama Penerima, Tanggal di Terima, Jumlah, Tujuan Dana, Status di Salurkan, Tipe, Status Validasi, Nominal Selisih, Analisis Selisih, Tindakan, Tanggal Validasi, Disetujui Oleh, Catatan\n";

    foreach ($records as $record) {
        $csvData .= implode(", ", [
            $record->id,
            $record->no_transaksi,
            $record->no_ref_bank,
            $record->tanggal_transaksi,
            $record->nama_pencair,
            $record->tanggal_dicairkan,
            $record->nama_penerima,
            $record->tanggal_diterima,
            $record->jumlah,
            $record->tujuan_dana,
            $record->status_disalurkan,
            $record->tipe,
            $record->status,
            $record->nominal_selisih,
            $record->analisis_selisih,
            $record->tindakan,
            $record->tanggal_validasi,
            $record->disetujui_oleh,
            $record->catatan
        ]) . "\n";
    }

    return response()->streamDownload(fn () => print($csvData), 'FormPencocokan.csv');
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
            'index' => Pages\ListFormPencocokans::route('/'),
            'create' => Pages\CreateFormPencocokan::route('/create'),
            'edit' => Pages\EditFormPencocokan::route('/{record}/edit'),
        ];
    }
}

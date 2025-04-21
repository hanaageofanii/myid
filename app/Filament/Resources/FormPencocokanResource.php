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


class FormPencocokanResource extends Resource
{
    protected static ?string $model = form_pencocokan::class;

    protected static ?int $navigationSort = 4;
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Form Pencocokan Data Rekening";
    protected static ?string $navigationLabel = "Form Pencocokan Data Rekening";
    protected static ?string $pluralModelLabel = 'Daftar Form Pencocokan Data Rekening';
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
            ->reactive(),

        Select::make('no_ref_bank')
            ->label('No. Ref Bank')
            ->options(fn () => rekening_koran::pluck('no_referensi_bank', 'no_referensi_bank'))
            ->searchable()
            ->reactive(),

        DatePicker::make('tanggal_transaksi')
            ->required()
            ->label('Tanggal Transaksi'),

        TextInput::make('jumlah')
            ->required()
            ->label('Jumlah'),

        TextInput::make('tujuan_dana')
            ->required()
            ->label('Tujuan Dana'),

        Select::make('tipe')
            ->options([
                'debit' => 'Debit',
                'kredit' => 'Kredit',
            ])
            ->label('Tipe')
            ->required(),
    ]),

Fieldset::make('Pencairan dan Penerima')
    ->schema([
        TextInput::make('nama_pencair')
            ->required()
            ->label('Nama Pencair'),

        DatePicker::make('tanggal_dicairkan')
            ->required()
            ->label('Tanggal di Cairkan'),

        TextInput::make('nama_penerima')
            ->required()
            ->label('Nama Penerima'),

        DatePicker::make('tanggal_penerima')
            ->required()
            ->label('Tanggal Penerima'),
    ]),

Fieldset::make('Status dan Analisis Selisih')
    ->schema([
        Select::make('status_disalurkan')
            ->options([
                'sudah' => 'Sudah',
                'belum' => 'Belum',
            ])
            ->label('Status di Salurkan')
            ->required(),

        TextInput::make('nominal_selisih')
            ->required()
            ->label('Nominal Selisih'),

        TextArea::make('analisis_selisih')
            ->required()
            ->label('Analisis Selisih'),

        Select::make('tindakan')
            ->options([
                'koreksi' => 'Koreksi',
                'pending' => 'Pending',
                'abaikan' => 'Abaikan',
            ])
            ->label('Tindakan')
            ->required(),
    ]),

Fieldset::make('Dokumen Pendukung')
    ->schema([
        FileUpload::make('bukti_pendukung')
            ->disk('public')
            ->multiple()
            ->required()
            ->nullable()
            ->label('Bukti Pendukung di Terima')
            ->downloadable()
            ->previewable(false),

        FileUpload::make('bukti_bukti')
            ->disk('public')
            ->multiple()
            ->required()
            ->nullable()
            ->label('Bukti-bukti Lainnya')
            ->downloadable()
            ->previewable(false),
    ]),

Fieldset::make('Validasi dan Catatan')
    ->schema([
        DatePicker::make('tanggal_validasi')
            ->required()
            ->label('Tanggal Validasi'),

        TextInput::make('disetujui_oleh')
            ->required()
            ->label('Disetujui Oleh'),

            Select::make('status')
            ->options([
                'approve' => 'Approve',
                'revisi' => 'Revisi',
            ])
            ->label('Status')
            ->required(),

        TextArea::make('catatan')
            ->required()
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
                ->label('Status'),

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

                    Filter::make('status')
                    ->form([
                        Select::make('status')
                            ->options([
                                'belum' => 'Belum',
                            'sudah' => 'Sudah',
                            ])
                            ->nullable()
                            ->label('Status Pengecekan')
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
        $csvData = "ID, No. Transaksi, No. Referensi Bank, Tanggal Transaksi, Jumlah, Tipe, Status, Selisih, Catatan\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->no_transaksi}, {$record->no_ref_bank}, {$record->tanggal_transaksi}, {$record->jumlah}, {$record->tipe}, {$record->status}, {$record->nominal_selisih}, {$record->catatan}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'RekeningKoran.csv');
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

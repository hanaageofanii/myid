<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvKaskecilResource\Pages;
use App\Filament\Resources\GcvKaskecilResource\RelationManagers;
use App\Models\gcv_kaskecil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\BukuRekonsil;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\Rekening;
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
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Wizard;
use Filament\Forms\Components\Wizard\Step;

class GcvKaskecilResource extends Resource
{
    protected static ?string $model = gcv_kaskecil::class;

protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Kas Kecil";
    protected static ?string $navigationLabel = "Kasir > Kas Kecil";
    protected static ?string $pluralModelLabel = 'Daftar Kas Kecil';
    protected static ?string $navigationIcon = 'heroicon-o-plus';
    protected static ?int $navigationSort=16;
 public static function form(Form $form): Form
{
    return $form->schema([
        Wizard::make([
            Step::make('Informasi Perusahaan')
            ->schema([
                Select::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->options([
                        'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                        'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                        'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                    ])
                    ->reactive()
                    ->afterStateUpdated(fn (callable $set) => $set('bank', null))
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),               ]),

            Step::make('Transaksi')->columns(2)->schema([                
                
                DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),   

                TextArea::make('deskripsi')
                    ->label('Deskripsi')
                    ->required()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()), 

                Select::make('tipe')
                    ->label('Tipe')
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'Kredit',
                    ])
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $perusahaan = $get('nama_perusahaan');
                        $tipe = $get('tipe');
                        $jumlahUang = (int) $get('jumlah_uang');

                        if (! $perusahaan || ! $tipe || $jumlahUang === null) return;

                        $saldoSebelumnya = gcv_kaskecil::where('nama_perusahaan', $perusahaan)
                            ->selectRaw("SUM(CASE WHEN tipe = 'debit' THEN jumlah_uang ELSE -jumlah_uang END) as total")
                            ->value('total') ?? 0;

                        $set('saldo', $tipe === 'debit'
                            ? $saldoSebelumnya + $jumlahUang
                            : $saldoSebelumnya - $jumlahUang);
                    })
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()), 

                TextInput::make('jumlah_uang')
                    ->label('Jumlah Uang')
                    ->required()
                    ->reactive()
                    ->afterStateUpdated(function (callable $get, callable $set) {
                        $perusahaan = $get('nama_perusahaan');
                        $tipe = $get('tipe');
                        $jumlahUang = (int) $get('jumlah_uang');

                        if (! $perusahaan || ! $tipe || $jumlahUang === null) return;

                        $saldoSebelumnya = gcv_kaskecil::where('nama_perusahaan', $perusahaan)
                            ->selectRaw("SUM(CASE WHEN tipe = 'debit' THEN jumlah_uang ELSE -jumlah_uang END) as total")
                            ->value('total') ?? 0;

                        $set('saldo', $tipe === 'debit'
                            ? $saldoSebelumnya + $jumlahUang
                            : $saldoSebelumnya - $jumlahUang);
                    })
                        ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),   
                TextInput::make('saldo')
                    ->label('Saldo')
                    ->columnSpanFull()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),               ]),

            Step::make('Catatan & Bukti')->columns(2)->schema([
                TextArea::make('catatan')
                    ->label('Catatan')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),   

                Forms\Components\FileUpload::make('bukti')
                    ->label('Bukti - Bukti')
                    ->disk('public')
                    ->nullable()
                    ->multiple()
                    ->downloadable()
                    ->previewable(false)
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 2','Kasir 1']);
                    })()),               ]),
        ])
        ->columnSpanFull() 
    ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_perusahaan')->label('Nama Perusahaan')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                    'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                    'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                    default => $state,
                })->searchable(),

                Tables\Columns\TextColumn::make('tanggal')->label('Tanggal Check')
                ->searchable()
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

                                Tables\Columns\TextColumn::make('deskripsi')->label('Deskripsi')
                ->searchable(),
                // ->wrap()->limit(300) ->tooltip(fn ($record) => $record->deskripsi),

                Tables\Columns\TextColumn::make('jumlah_uang')->label('Jumlah Uang')
                ->searchable()            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),


                Tables\Columns\TextColumn::make('tipe')->label('Tipe')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                   'sudah' => 'Sudah',
                   'belum' => 'Belum',
                    default => $state,
                })->searchable(),


                Tables\Columns\TextColumn::make('saldo')->label('Saldo')
                ->searchable()
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),

                                Tables\Columns\TextColumn::make('catatan')->label('Catatan')
                ->searchable(),

                TextColumn::make('bukti')
                ->label('Bukti')
                ->formatStateUsing(function ($record) {
                    if (!$record->bukti) {
                        return 'Tidak Ada Dokumen';
                    }

                    $files = is_array($record->bukti) ? $record->bukti : json_decode($record->bukti, true);

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
            ->filters([
                 Tables\Filters\TrashedFilter::make()
                    ->label('Data yang dihapus')
                    ->native(false),

                Tables\Filters\SelectFilter::make('nama_perusahaan')
                    ->label('Nama Perusahaan')
                    ->options([
                        'langgeng_pertiwi_development' => 'PT. Langgeng Pertiwi Development',
                        'agung_purnama_bakti' => 'PT. Agung Purnama Bakti',
                        'purnama_karya_bersama' => 'PT. Purnama Karya Bersama',
                    ])
                    ->native(false),


                Tables\Filters\SelectFilter::make('tipe')
                    ->label('Tipe')
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'Kredit',
                    ])
                    ->native(false),

                Filter::make('created_from')
                    ->label('Dari Tanggal')
                    ->form([
                        DatePicker::make('created_from')
                            ->label('Dari'),
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
                            ->label('Sampai'),
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
                                ->title('Kas Kecil Diperbarui')
                                ->body('Kas Kecil telah berhasil disimpan.')),
                                DeleteAction::make()
                                ->color('danger')
                                ->label(fn ($record) => "Hapus {$record->deskripsi}")
                                ->modalHeading(fn ($record) => "Konfirmasi Hapus {$record->deskripsi}")
                                ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus {$record->deskripsi}?")
                                ->successNotification(
                                    Notification::make()
                                        ->success()
                                        ->title('Kas Kecil Dihapus')
                                        ->body('Kas Kecil telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->deskripsi}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan {$record->deskripsi}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan {$record->deskripsi}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Kas Kecil')
                            ->body('Kas Kecil berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->desripsi}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Permanent{$record->deskripsi}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus secara permanent {$record->deskripsi}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Kas Kecil')
                            ->body('Kas Kecil berhasil dihapus secara permanen.')
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
                                ->title('Kas Kecil')
                                ->body('Kas Kecil berhasil dihapus.'))
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),

                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle')
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Kas Kecil')
                                ->body('Kas Kecil berhasil dihapus secara permanen.'))
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
                                ->title('Kas Kecil')
                                ->body('Kas Kecil berhasil dikembalikan.')),
                ]);

    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function exportData(Collection $records)
    {
        $csvData = "Id, Nama Perusahaan, Tanggal, Deskripsi, Tipe, Jumlah Uang, Saldo, Catatan\n";

        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->nama_perusahaan}, {$record->tanggal}, {$record->deskripsi}, {$record->tipe}, {$record->jumlah_uang}, {$record->saldo}, {$record->catatan}\n";
        }

        return response()->streamDownload(fn () => print($csvData), 'KasKecil.csv');
    }

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery()
        ->withoutGlobalScopes([
            SoftDeletingScope::class,
        ]);

        return $query;
}
public static function getPages(): array
    {
        return [
            'index' => Pages\ListGcvKaskecils::route('/'),
            'create' => Pages\CreateGcvKaskecil::route('/create'),
            'edit' => Pages\EditGcvKaskecil::route('/{record}/edit'),
        ];
    }
}
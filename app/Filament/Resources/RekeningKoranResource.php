<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekeningKoranResource\Pages;
use App\Filament\Resources\RekeningKoranResource\RelationManagers;
use App\Models\rekening_koran;
use App\Models\RekeningKoran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
use App\Filament\Resources\Exception;
use Filament\Tables\Actions\ForceDeleteAction;
use Illuminate\Support\Facades\Storage;


class RekeningKoranResource extends Resource
{
    protected static ?string $model = rekening_koran::class;

    protected static ?string $title = "Input Rekening Koran";

    protected static ?int $navigationSort = 2;
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Rekening Koran";
    protected static ?string $navigationLabel = "Rekening Koran";
    protected static ?string $pluralModelLabel = 'Daftar Rekening Koran';
    protected static ?string $navigationIcon = 'heroicon-o-newspaper';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                ->schema([
                    Select::make('no_transaksi')
                    ->label('No. Transaksi')
                    ->options(fn () => rekonsil::pluck('no_transaksi', 'no_transaksi'))
                    ->searchable()
                    ->reactive()
                    ->unique(ignoreRecord: true),

                    // ->afterStateUpdated(function ($state, callable $set) {
                    //     if ($state) {
                    //         $data = rekonsil::where('no_referensi_bank', $state)->first();
                    //         if ($data) {
                    //             $set('nama_konsumen', $data->nama_konsumen);
                    //             $set('bank', $data->bank);
                    //             $set('max_kpr', $data->maksimal_kpr);
                    //         }
                    //     }
                    // }),

                    DatePicker::make('tanggal_mutasi')
                    ->label('Tanggal Mutasi')
                    ->required(),

                    TextInput::make('keterangan_dari_bank')
                    ->label('Keterangan dari Bank')
                    ->required(),

                    TextInput::make('nominal')
                    ->label('Nominal')
                    ->required(),

                    Select::make('tipe')
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'kredit',
                    ]) ->label('Tipe')
                    ->required(),

                    TextInput::make('saldo')
                    ->label('Saldo')
                    ->required(),

                    TextInput::make('no_referensi_bank')
                    ->label('No. Refrensi Bank')
                    ->required(),

                    TextInput::make('bank')
                    ->label('Bank')
                    ->required(),

                    TextInput::make('catatan')
                    ->label('Catatan'),

                    FileUpload::make('up_rekening_koran')
                    ->disk('public')
                    ->multiple()
                    ->required()
                    ->nullable()
                    ->label('Upload Rekening Koran')
                    ->downloadable()
                    ->previewable(false),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_referensi_bank')
                ->searchable()
                ->label('No. Transaksi'),

                TextColumn::make('tanggal_mutasi')
                ->searchable()
                ->label('Tanggal Mutasi')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),


                TextColumn::make('keterangan_dari_bank')
                ->searchable()
                ->label('Keterangan dari Bank'),

                TextColumn::make('nominal')
                ->searchable()
                ->label('Nominal'),

                TextColumn::make('tipe')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'debit' => 'Debit',
                    'kredit' => 'Kredit',  
                default => ucfirst($state),
            })
            ->sortable()
            ->searchable()
            ->label('Tipe'),

            TextColumn::make('saldo')
            ->searchable()
            ->label('Saldo'),

            TextColumn::make('no_referensi_bank')
            ->searchable()
            ->label('No. Refrensi Bank'),

            TextColumn::make('bank')
            ->searchable()
            ->label('Bank'),

            TextColumn::make('catatan')
            ->searchable()
            ->label('Catatan'),

            TextColumn::make('up_rekening_koran')
            ->label('Upload Rekening Koran')
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
            ->defaultSort('no_referensi_bank', 'asc')
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
                                ->title('Data Rekening Koran Diubah')
                                ->body('Data Rekening Koran telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Nomor {$record->no_referensi_bank}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Nomor{$record->no_referensi_bank}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus nomor {$record->no_referensi_bank}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Rekening Koran Dihapus')
                                ->body('Data Rekening Koran telah berhasil dihapus.')),                         
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
                            ->title('Data Rekening Koran')
                            ->body('Data Rekening Koran berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label('Hapus Permanen')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Rekening Koran')
                            ->body('Data Rekening Koran berhasil dihapus secara permanen.')
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
                                ->title('Data Rekening Koran')
                                ->body('Data Rekening Koran berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                        BulkAction::make('forceDelete')
                        ->label('Hapus Permanen')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Rekening Koran')
                                ->body('Data Rekening Koran berhasil dihapus secara permanen.')
                        )
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
                                ->title('Data Rekening Koran')
                                ->body('Data Rekening Koran berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, No. Transaksi, Tanggal Mutasi, Nominal, Tipe, Saldo, No. Referensi Bank, Nama Bank, Catatan\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->no_transaksi}, {$record->tanggal_mutasi}, {$record->nominal}, {$record->tipe}, {$record->saldo}, {$record->no_referensi_bank}, {$record->bank}, {$record->catatan}\n";
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
    
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRekeningKorans::route('/'),
            'create' => Pages\CreateRekeningKoran::route('/create'),
            'edit' => Pages\EditRekeningKoran::route('/{record}/edit'),
        ];
    }
}

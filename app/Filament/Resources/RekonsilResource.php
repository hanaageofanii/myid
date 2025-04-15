<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RekonsilResource\Pages;
use App\Filament\Resources\RekonsilResource\RelationManagers;
use App\Models\Rekonsil;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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


class RekonsilResource extends Resource
{
    protected static ?string $model = Rekonsil::class;

    protected static ?string $title = "Input Transaksi Internal";

    protected static ?int $navigationSort = 1;
    protected static ?string $navigationGroup = "Kasir";
    protected static ?string $pluralLabel = "Transaksi Internal";
    protected static ?string $navigationLabel = "Transaksi Internal";
    protected static ?string $pluralModelLabel = 'Daftar Transaksi Internal';
    protected static ?string $navigationIcon = 'heroicon-o-squares-plus';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make()
                ->schema([
                    TextInput::make('no_transaksi')
                    ->label('No. Transaksi')
                    ->required()
                    ->unique(),

                    DatePicker::make('tanggal_transaksi')
                    ->label('Tanggal Transaksi')
                    ->required(),

                    TextInput::make('nama_yang_mencairkan')
                    ->label(' Nama yang Mencairkan')
                    ->required(),

                    DatePicker::make('tanggal_diterima')
                    ->label('Tanggal di Terima')
                    ->required(),

                    TextInput::make('nama_penerima')
                    ->label('Nama Penerima')
                    ->required(),

                    TextInput::make('bank')
                    ->label('Bank')
                    ->required(),

                    TextArea::make('deskripsi')
                    ->label('Deskripsi Keperluan')
                    ->required(),

                    TextInput::make('jumlah_uang')
                    ->label('Jumlah Uang')
                    ->required(),

                    Select::make('tipe')
                    ->options([
                        'debit' => 'Debit',
                        'kredit' => 'kredit',
                    ]) ->label('Tipe')
                    ->required(),

                    Select::make('status_rekonsil')
                    ->options([
                        'belum' => 'Belum',
                        'sudah' => 'Sudah'
                    ]) ->label('Status Rekonsil')
                    ->required(),

                    TextArea::make('catatan')
                    ->label('Catatan')
                    ->required(),
                ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('no_transaksi')
                ->label('No. Transaksi')
                ->searchable(),

                TextColumn::make('tanggal_transaksi')
                ->searchable()
                ->label('Tanggal Transaksi')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),    

                TextColumn::make('nama_yang_mencairkan')
                ->label('Nama yang Mencairkan')
                ->searchable(),

                TextColumn::make('tanggal_diterima')
                ->searchable()
                ->label('Tanggal Terima')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                
                TextColumn::make('nama_penerima')
                ->label('Nama Penerima')
                ->searchable(),

                TextColumn::make('bank')
                ->label('Bank')
                ->searchable(),

                TextColumn::make('deskripsi')
                ->limit(50) 
                ->label('Deskripsi Keperluan')
                ->searchable()
                ->tooltip(fn ($record) => $record->deskripsi),

                TextColumn::make('jumlah_uang')
                ->label('Jumlah Uang')
                ->searchable(),

                TextColumn::make('tipe')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                       'debit' => 'Debit',
                            'kredit' => 'Kredit',                            
                default => ucfirst($state),
            })
            ->sortable()
            ->searchable()
            ->label('Tipe'),

            TextColumn::make('status_rekonsil')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                       'belum' => 'Belum',
                            'sudah' => 'Sudah',                            
                default => ucfirst($state),
            })
            ->sortable()
            ->searchable()
            ->label('Status Rekonsil'), 
                        
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

                    Filter::make('status_rekonsil')
                    ->form([
                        Select::make('status_rekonsil')
                            ->options([
                                'belum' => 'Belum',
                            'sudah' => 'Sudah',
                            ])
                            ->nullable()
                            ->label('Status Rekonsil')
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_rekonsil']), fn ($q) =>
                            $q->where('status_rekonsil', $data['status_rekonsil'])
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
                                ->title('Data Transaksi Internal Diubah')
                                ->body('Data Transaksi Internal telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->no_transaksi}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->no_transaksi}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->no_transaksi}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Transaksi Internal Dihapus')
                                ->body('Data Transaksi Internal telah berhasil dihapus.')),                         
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
                            ->title('Data Transaksi Internal')
                            ->body('Data Transaksi Internal berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label('Hapus Permanen')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Transaksi Internal')
                            ->body('Data Transaksi Internal berhasil dihapus secara permanen.')
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
                                ->title('Data Transaksi Internal')
                                ->body('Data Transaksi Internal berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Transaksi Internal')
                                ->body('Data Transaksi Internal berhasil dihapus secara permanen.'))
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
                                ->title('Data Transaksi Internal')
                                ->body('Data Transaksi Internal berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, No. Transaksi, Tanggal Transaksi, Nama yang Mencairkan, Nama Penerima, Tanggal di Terima, Bank, Deskripsi Keperluan, Jumlah Uang, Tipe, Status Rekonsil, Catatan\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->no_transaksi}, {$record->nama_yang_mencairkan}, {$record->nama_penerima}, {$record->tanggal_diterima}, {$record->bank}, {$record->deskripsi}, {$record->jumlah_uang}, {$record->tipe}, {$record->status_rekonsil}, {$record->catatan}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'TransaksiInternal.csv');
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
            'index' => Pages\ListRekonsils::route('/'),
            'create' => Pages\CreateRekonsil::route('/create'),
            'edit' => Pages\EditRekonsil::route('/{record}/edit'),
        ];
    }
}

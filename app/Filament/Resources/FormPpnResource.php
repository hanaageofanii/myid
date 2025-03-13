<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormPpnResource\Pages;
use App\Filament\Resources\FormPpnResource\RelationManagers;
use App\Models\form_ppn;
use App\Models\FormPpn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\form_kpr;
use App\Models\form_legal;
use App\Models\form_pajak;
use App\Models\FormPajak;
use App\Models\FormLegal;
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;
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

class FormPpnResource extends Resource  
{
    protected static ?string $model = form_ppn::class;
    protected static ?string $title = "Form Data Faktur PPN";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Data Faktur PPN";
    protected static ?string $navigationLabel = "Faktur PPN";
    protected static ?string $pluralModelLabel = 'Daftar Faktur PPN';
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('siteplan')->nullable()->label('Siteplan'),

                Forms\Components\Select::make('kavling')
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
                ->nullable()
                ->label('Jenis Unit'),
                
                Forms\Components\TextInput::make('nama_konsumen')->nullable()->label('Nama Konsumen'),
                Forms\Components\TextInput::make('nik')->nullable()->label('NIK'),
                Forms\Components\TextInput::make('npwp')->nullable()->label('NPWP'),
                Forms\Components\TextArea::make('alamat')->nullable()->label('Alamat'),
                Forms\Components\TextInput::make('no_seri_faktur')->nullable()->label('No. Seri Faktur'),
                Forms\Components\DatePicker::make('tanggal_faktur')->nullable()->label('Tanggal Faktur'),
                Forms\Components\TextInput::make('harga_jual')->nullable()->label('Harga Jual'),
                Forms\Components\TextInput::make('dpp_ppn')->nullable()->label('DPP PPN'),  

                Forms\Components\Select::make('tarif_ppn')
                    ->options([
                        '11%' => '11 %',
                        '12%' => '12 %',
                    ])
                    ->required()
                    ->reactive()
                    ->nullable()
                    ->label('Tarif PPN'),

                Forms\Components\TextInput::make('jumlah_ppn')->nullable()->label('Jumlah PPN'), 

                Forms\Components\Select::make('status_ppn')
                    ->options([
                        'dtp' => 'DTP',
                        'dtp_sebagian' => 'DTP Sebagian',
                        'dibebaskan' => 'Dibebaskan',
                        'bayar' => 'Bayar',
                    ])
                    ->required()
                    ->label('Status PPN')
                    ->searchable()
                    ->native(false),


                Forms\Components\DatePicker::make('tanggal_bayar_ppn')->nullable()->label('Tanggal Faktur'),
                Forms\Components\TextInput::make('ntpn_ppn')->nullable()->label('BTPN PPN'),

                Forms\Components\Fieldset::make('Dokumen')
                ->schema([
                    Forms\Components\FileUpload::make('up_bukti_setor_ppn')->disk('public')->nullable()->label('Upload Bukti Setor PPN'),
                    Forms\Components\FileUpload::make('up_efaktur')->disk('public')->nullable()->label('Upload E-Faktur'),
                ]),           
            ]);
        }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('siteplan')->sortable()->searchable()->label('Blok'),
                Tables\Columns\TextColumn::make('kavling')->sortable()->searchable()->label('Jenis Unit'),
                Tables\Columns\TextColumn::make('nama_konsumen')->sortable()->searchable()->label('Nama Konsumen'),
                Tables\Columns\TextColumn::make('nik')->sortable()->searchable()->label('NIK'),
                Tables\Columns\TextColumn::make('npwp')->sortable()->searchable()->label('NPWP'),
                Tables\Columns\TextColumn::make('alamat')->sortable()->searchable()->label('Alamat'),
                Tables\Columns\TextColumn::make('no_seri_faktur')->sortable()->searchable()->label('No. Seri Faktur'),
                Tables\Columns\TextColumn::make('tanggal_faktur')->sortable()->searchable()->label('Tanggal Faktur'),
                Tables\Columns\TextColumn::make('harga_jual')->sortable()->searchable()->label('Harga'),
                Tables\Columns\TextColumn::make('dpp_ppn')->sortable()->searchable()->label('DPP PPN'),
                Tables\Columns\TextColumn::make('tarif_ppn')->sortable()->searchable()->label('Tarif PPN'),
                Tables\Columns\TextColumn::make('jumlah_ppn')->sortable()->searchable()->label('Jumlah PPN'),
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
                            Tables\Columns\TextColumn::make('tanggal_bayar_ppn')->sortable()->searchable()->label('Tanggal Bayar PPN'),
                Tables\Columns\TextColumn::make('ntpn_ppn')->sortable()->searchable()->label('NTPN PPN'),

                Tables\Columns\TextColumn::make('up_bukti_setor_ppn')
                ->label('Dokumen Bukti Setor PPN')
                ->url(fn ($record) => $record->up_bukti_setir_ppn ? Storage::url($record->up_bukti_setir_ppn) : '#', true)
                ->sortable()
                ->searchable(),
                Tables\Columns\TextColumn::make('up_efaktur')
                ->label('Dokumen E-Faktur')
                ->url(fn ($record) => $record->up_efaktur ? Storage::url($record->up_efaktur) : '#', true)
                ->sortable()
                ->searchable(),
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
                                ->title('Data KPR Diubah')
                                ->body('Data KPR telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label('Hapus')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data KPR Dihapus')
                                ->body('Data KPR telah berhasil dihapus.')),
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    Tables\Actions\RestoreAction::make()
                    ->color('info')
                    ->label('Kembalikan Data')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data KPR')
                            ->body('Data KPR berhasil dikembalikan.')
                    ),
                    Tables\Actions\ForceDeleteAction::make()
                    ->color('primary')
                    ->label('Hapus Permanen')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data KPR')
                            ->body('Data KPR berhasil dihapus secara permanen.')
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
                                ->title('Data KPR')
                                ->body('Data KPR berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data KPR')
                                ->body('Data KPR berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
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
                                ->title('Data KPR')
                                ->body('Data KPR berhasil dikembalikan.')),
                ]);
                
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID,  Blok, Jenis Unit, Nama Konsumen, NIK, NPWP, Alamat, No. Seri Faktur, Tanggal Faktur, Harga Jual, DPP PPn, Tarif PPN, Jumlah PPN, Status PPN, Tanggal Bayar PPN, NTPN PPN\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->jenis_unit}, {$record->nama_konsumen}, {$record->nik}, {$record->npwp}, {$record->alamat}, {$record->no_seri_faktur}, {$record->tanggal_faktur}, {$record->harga_jual}, {$record->dpp_ppn}, {$record->tarif_ppn}, {$record->jumlah_ppn}, {$record->status_ppn}, {$record->tanggal_bayar_ppn}, {$record->ntpn_ppn}\n";
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
            'index' => Pages\ListFormPpns::route('/'),
            'create' => Pages\CreateFormPpn::route('/create'),
            'edit' => Pages\EditFormPpn::route('/{record}/edit'),
        ];
    }
}

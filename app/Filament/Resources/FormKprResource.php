<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormKprResource\Pages;
use App\Models\form_kpr;
use App\Models\FormKpr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Filament\Tables\Actions\Action;
use App\Filament\Resources\AuditResource\RelationManagers;
use App\Models\Audit;
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
use App\Filament\Resources\GCVResource;
use App\Models\GCV;

class FormKprResource extends Resource
{
    protected static ?string $title = "Form Input Data Penjualan";

    protected static ?string $navigationGroup = "KPR";

    protected static ?string $pluralLabel = "Data Penjualan KPR";

    protected static ?string $navigationLabel = "Form Penjualan";

    protected static ?string $pluralModelLabel = 'Daftar Data Penjualan KPR';



    protected static ?string $model = form_kpr::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('jenis_unit')
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
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $bookedBlok = GCV::where('status', 'booking')
                                ->where('kavling', $state)
                                ->get('siteplan')
                                ->pluck('siteplan')
                                ->toArray();

                            $formattedOptions = array_combine($bookedBlok, $bookedBlok);
                            $set('siteplan', null);
                            $set('available_siteplans', $formattedOptions); 
                        }
                    }),

                    Forms\Components\Select::make('siteplan')
                            ->nullable()
                            ->options(fn ($get, $set, $record) => 
                                collect($get('available_siteplans') ?? [])
                                    ->filter(fn ($label, $key) => !is_null($label)) 
                                    ->toArray() 
                                + ($record?->siteplan ? [$record->siteplan => $record->siteplan] : [])
                            )
                            ->reactive()
                            ->afterStateUpdated(function($state, callable $set){
                                $gcv = GCV::where('siteplan', $state)->first();

                                if ($gcv) {
                                    $set('tanggal_booking', $gcv->tanggal_booking);
                                    $set('nama_konsumen', $gcv->nama_konsumen);
                                    $set('agent', $gcv->agent);
                                    $set('luas', $gcv->luas_tanah);
                                    $set('type', $gcv->type);
                                    
                            }
                        }),
                
                Forms\Components\Select::make('type')
                    ->options([
                        '29/60' => '29/60',
                        '30/60' => '30/60',
                        '45/104' => '45/104',
                        '32/52' => '32/52',
                        '36/60' => '36/60',
                        '36/72' => '36/72',
                    ])->nullable(),
                    
                Forms\Components\TextInput::make('luas')->numeric()->nullable(),
                Forms\Components\TextInput::make('agent')->nullable(),
                Forms\Components\DatePicker::make('tanggal_booking')->nullable(),
                Forms\Components\DatePicker::make('tanggal_akad')->nullable(),
                Forms\Components\TextInput::make('harga')->numeric()->nullable(),
                Forms\Components\TextInput::make('maksimal_kpr')->numeric()->nullable(),
                Forms\Components\TextInput::make('nama_konsumen')->nullable(),
                Forms\Components\TextInput::make('nik')->nullable(),
                Forms\Components\TextInput::make('npwp')->nullable(),
                Forms\Components\Textarea::make('alamat')->nullable(),
                Forms\Components\TextInput::make('no_hp')->nullable(),
                Forms\Components\TextInput::make('no_email')->email()->nullable(),
                Forms\Components\Select::make('pembayaran')
                    ->options([
                        'kpr' => 'KPR',
                        'cash' => 'Cash',
                        'cash_bertahap' => 'Cash Bertahap',
                        'promo' => 'Promo',
                    ])->nullable(),
                Forms\Components\Select::make('bank')
                    ->options([
                        'btn_cikarang' => 'BTN Cikarang',
                        'btn_bekasi' => 'BTN Bekasi',
                        'btn_karawang' => 'BTN Karawang',
                        'bjb_syariah' => 'BJB Syariah',
                        'bjb_jababeka' => 'BJB Jababeka',
                        'btn_syariah' => 'BTN Syariah',
                        'brii_bekasi' => 'BRI Bekasi',
                    ])->nullable(),
                Forms\Components\TextInput::make('no_rekening')->nullable(),
                Forms\Components\Select::make('status_akad')
                    ->options([
                        'akad' => 'Akad',
                        'batal' => 'Batal',
                    ])->nullable(),
                
                Forms\Components\Fieldset::make('Dokumen')
                    ->schema([
                        Forms\Components\FileUpload::make('ktp')->disk('public')->nullable(),
                        Forms\Components\FileUpload::make('kk')->disk('public')->nullable(),
                        Forms\Components\FileUpload::make('npwp_upload')->disk('public')->nullable(),
                        Forms\Components\FileUpload::make('buku_nikah')->disk('public')->nullable(),
                        Forms\Components\FileUpload::make('akte_cerai')->disk('public')->nullable(),
                        Forms\Components\FileUpload::make('akte_kematian')->disk('public')->nullable(),
                        Forms\Components\FileUpload::make('kartu_bpjs')->disk('public')->nullable(),
                        Forms\Components\FileUpload::make('drk')->disk('public')->nullable(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_unit')->sortable(),
                Tables\Columns\TextColumn::make('siteplan')->sortable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('luas')->sortable(),
                Tables\Columns\TextColumn::make('agent')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_booking')->date(), 
                Tables\Columns\TextColumn::make('tanggal_akad')->date(), 
                Tables\Columns\TextColumn::make('harga')->sortable(),
                Tables\Columns\TextColumn::make('maksimal_kpr')->sortable(),
                Tables\Columns\TextColumn::make('nama_konsumen')->sortable(),
                Tables\Columns\TextColumn::make('nik')->sortable(),
                Tables\Columns\TextColumn::make('npwp')->sortable(),
                Tables\Columns\TextColumn::make('alamat')->sortable(),
                Tables\Columns\TextColumn::make('no_hp')->sortable(),
                Tables\Columns\TextColumn::make('no_email')->sortable(),
                Tables\Columns\TextColumn::make('pembayaran')->sortable(),
                Tables\Columns\TextColumn::make('bank')->sortable(),
                Tables\Columns\TextColumn::make('no_rekening')->sortable(),
                Tables\Columns\TextColumn::make('status_akad')->sortable(),
                Tables\Columns\TextColumn::make('ktp')
                ->label('KTP')
                ->url(fn ($record) => $record->ktp ? Storage::url($record->ktp) : '#', true)
                ->sortable(),
                Tables\Columns\TextColumn::make('kk')->label('KK')
                ->url(fn ($record) => $record->ktp ? Storage::url($record->kk) : '#', true)
                ->sortable(),
                Tables\Columns\TextColumn::make('npwp_upload')->label('NPWP')->url(fn ($record) => $record->npwp_upload ? Storage::url($record->ktp) : '#', true)
                ->sortable(),
                Tables\Columns\TextColumn::make('buku_nikah')->label('Buku Nikah')->url(fn ($record) => $record->buku_nikah ? Storage::url($record->ktp) : '#', true)
                ->sortable(),
                Tables\Columns\TextColumn::make('akte_cerai')->label('Akte Cerai')->url(fn ($record) => $record->akte_cerai ? Storage::url($record->ktp) : '#', true)
                ->sortable(),
                Tables\Columns\TextColumn::make('akte_kematian')->label('Akte Kematian')->url(fn ($record) => $record->akte_kematian ? Storage::url($record->ktp) : '#', true)
                ->sortable(),
                Tables\Columns\TextColumn::make('kartu_bpjs')->label('Kartu BPJS')->url(fn ($record) => $record->kartu_bpjs ? Storage::url($record->ktp) : '#', true)
                ->sortable(),
                Tables\Columns\TextColumn::make('drk')->label('DRK')->url(fn ($record) => $record->drk ? Storage::url($record->ktp) : '#', true)
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

                Filter::make('status_akad')
                    ->label('Status Akad')
                    ->form([
                        Select::make('status_akad')
                            ->options([
                                'akad' => 'Akad',
                                'batal' => 'Batal',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_akad']), fn ($q) =>
                            $q->where('status_akad', $data['status_akad'])
                        )
                    ),

                    Filter::make('jenis_unit')
                    ->label('Jenis Unit')
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
        $csvData = "ID, Jenis Unit, Blok, Type, Luas, Agent, Tanggal Booking, Tanggal Akad, Harga, Maksimal KPR, Nama Konsumen, NIK, NPWP, Alamat, NO Handphone, Email, Pembayaran, Bank, No. Rekening, Status Akad\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->jenis_unit}, {$record->siteplan}, {$record->type}, {$record->luas}, {$record->agent}, {$record->tanggal_booking}, {$record->tanggal_akad}, {$record->harga}, {$record->maksimal_kpr}, {$record->nama_konsumen}, {$record->nik}, {$record->npwp}, {$record->alamat}, {$record->no_hp}, {$record->no_email}, {$record->pembayaran}, {$record->bank}, {$record->no_rekening}, {$record->status_akad}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'dataKPR.csv');
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
            ]);
    }

    // public static function getWidgets(): array
    // {
    //     return [
    //         AuditStats::class,
    //     ];
    // }


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormKprs::route('/'),
            'create' => Pages\CreateFormKpr::route('/create'),
            'edit' => Pages\EditFormKpr::route('/{record}/edit'),
        ];
    }
}

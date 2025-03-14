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
use App\Filament\Resources\KPRStats;
use Carbon\Carbon;

class FormKprResource extends Resource
{
    protected static ?string $title = "Form Input Data Penjualan";

    protected static ?string $navigationGroup = "KPR";

    protected static ?string $pluralLabel = "Data Penjualan KPR";

    protected static ?string $navigationLabel = "Form Penjualan";

    protected static ?string $pluralModelLabel = 'Daftar Data Penjualan KPR';



    protected static ?string $model = form_kpr::class;

    protected static ?string $navigationIcon = 'heroicon-o-banknotes';

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
                    ->label('Jenis Unit')
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
                            ->label('Blok')
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
                    ])->nullable()->label('Type'),
                    
                Forms\Components\TextInput::make('luas')->numeric()->nullable()->label('Luas'),
                Forms\Components\TextInput::make('agent')->nullable()->label('Agent'),
                Forms\Components\DatePicker::make('tanggal_booking')->nullable()->label('Tanggal Booking'),
                Forms\Components\DatePicker::make('tanggal_akad')->nullable()->label('Tanggal Akad'),
                Forms\Components\TextInput::make('harga')->numeric()->nullable()->label('Harga')->prefix('Rp'),
                Forms\Components\TextInput::make('maksimal_kpr')->numeric()->nullable()->label('Maksimal KPR'),
                Forms\Components\TextInput::make('nama_konsumen')->nullable()->label('Nama Konsumen'),
                Forms\Components\TextInput::make('nik')->nullable()->label('NIK'),
                Forms\Components\TextInput::make('npwp')->nullable()->label('NPWP'),
                Forms\Components\Textarea::make('alamat')->nullable()->label('Alamat'),
                Forms\Components\TextInput::make('no_hp')->nullable()->label('No. Handphone'),
                Forms\Components\TextInput::make('no_email')->email()->nullable()->label('Email'),
                Forms\Components\Select::make('pembayaran')
                    ->label('Pembayaran')
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
                    ])->nullable()->label('Bank'),
                Forms\Components\TextInput::make('no_rekening')->nullable()->label('No. Rekening'),
                Forms\Components\Select::make('status_akad')
                    ->options([
                        'akad' => 'Akad',
                        'batal' => 'Batal',
                    ])->nullable()->label('Status Akad'),
                
                    Forms\Components\Fieldset::make('Dokumen')
                    ->schema([
                        Forms\Components\FileUpload::make('ktp')
                            ->disk('public')
                            ->nullable()
                            ->label('KTP')
                            ->downloadable()
                            ->previewable(false),
                
                        Forms\Components\FileUpload::make('kk')
                            ->disk('public')
                            ->nullable()
                            ->label('Kartu Keluarga')
                            ->downloadable()
                            ->previewable(false),
                
                        Forms\Components\FileUpload::make('npwp_upload')
                            ->disk('public')
                            ->nullable()
                            ->label('NPWP')
                            ->downloadable()
                            ->previewable(false),
                
                        Forms\Components\FileUpload::make('buku_nikah')
                            ->disk('public')
                            ->nullable()
                            ->label('Buku Nikah')
                            ->downloadable()
                            ->previewable(false),
                
                        Forms\Components\FileUpload::make('akte_cerai')
                            ->disk('public')
                            ->nullable()
                            ->label('Akta Cerai')
                            ->downloadable()
                            ->previewable(false),
                
                        Forms\Components\FileUpload::make('akte_kematian')
                            ->disk('public')
                            ->nullable()
                            ->label('Akte Kematian')
                            ->downloadable()
                            ->previewable(false),
                
                        Forms\Components\FileUpload::make('kartu_bpjs')
                            ->disk('public')
                            ->nullable()
                            ->label('Kartu BPJS')
                            ->downloadable()
                            ->previewable(false),
                
                        Forms\Components\FileUpload::make('drk')
                            ->disk('public')
                            ->nullable()
                            ->label('DRK')
                            ->downloadable()
                            ->previewable(false),
                    ]),
                
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_unit')->label('Jenis Unit')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state, 
                })->searchable(),
                
                Tables\Columns\TextColumn::make('siteplan')->sortable()->searchable()->label('Blok'),
                Tables\Columns\TextColumn::make('type')->sortable()->searchable()->label('Type'),
                Tables\Columns\TextColumn::make('luas')->sortable()->searchable()->label('Luas')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('agent')->sortable()->searchable()->label('Agent'),
                Tables\Columns\TextColumn::make('tanggal_booking')
                ->searchable()
                ->label('Tanggal Booking')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            
            Tables\Columns\TextColumn::make('tanggal_akad')
                ->searchable()
                ->label('Tanggal Akad')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                                
            Tables\Columns\TextColumn::make('harga')
                ->sortable()
                ->searchable()
                ->label('Harga')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('maksimal_kpr')->sortable()->searchable()->label('Maksimal KPR')->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.')),
                Tables\Columns\TextColumn::make('nama_konsumen')->sortable()->searchable()->label('Nama Konsumen'),
                Tables\Columns\TextColumn::make('nik')->sortable()->searchable()->label('NIK'),
                Tables\Columns\TextColumn::make('npwp')->sortable()->searchable()->label('NPWP'),
                Tables\Columns\TextColumn::make('alamat')->sortable()->searchable()->label('Alamat'),
                Tables\Columns\TextColumn::make('no_hp')->sortable()->searchable()->label('No Handphone'),
                Tables\Columns\TextColumn::make('no_email')->sortable()->searchable()->label('Email'),
                Tables\Columns\TextColumn::make('pembayaran')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                        'kpr' => 'KPR',
                        'cash' => 'Cash',
                        'cash_bertahap' => 'Cash Bertahap',
                        'promo' => 'Promo',
                    default => ucfirst($state), 
                })
                ->sortable()
                ->searchable()
                ->label('Pembayaran'),
                Tables\Columns\TextColumn::make('bank')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                    'btn_cikarang' => 'BTN Cikarang',
                    'btn_bekasi' => 'BTN Bekasi',
                    'btn_karawang' => 'BTN Karawang',
                    'bjb_syariah' => 'BJB Syariah',
                    'bjb_jababeka' => 'BJB Jababeka',
                    'btn_syariah' => 'BTN Syariah',
                    'brii_bekasi' => 'BRI Bekasi',
                default => ucfirst($state), 
                })
                ->sortable()
                ->searchable()
                ->label('Bank'),
                Tables\Columns\TextColumn::make('no_rekening')->sortable()->searchable()->label('No. Rekening'),
                Tables\Columns\TextColumn::make('status_akad')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'akad' => 'Akad',
                        'batal' => 'Batal',
                        default => ucfirst($state), 
                    })
                    ->sortable()
                    ->searchable()
                    ->label('Status Akad'),
                
        Tables\Columns\TextColumn::make('ktp')
            ->label('KTP')
            ->formatStateUsing(fn ($record) => $record->ktp 
            ? '<a href="' . Storage::url($record->ktp) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->ktp) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('kk')
            ->label('Kartu Keluarga')
            ->formatStateUsing(fn ($record) => $record->kk 
            ? '<a href="' . Storage::url($record->kk) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->kk) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('npwp_upload')
            ->label('NPWP')
            ->formatStateUsing(fn ($record) => $record->npwp_upload 
            ? '<a href="' . Storage::url($record->npwp_upload) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->npwp_upload) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('buku_nikah')
            ->label('Buku Nikah')
            ->formatStateUsing(fn ($record) => $record->buku_nikah 
            ? '<a href="' . Storage::url($record->buku_nikah) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->buku_nikah) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('akte_cerai')
            ->label('Akte Cerai')
            ->formatStateUsing(fn ($record) => $record->akte_cerai 
            ? '<a href="' . Storage::url($record->akte_cerai) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->akte_cerai) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('akte_kematian')
            ->label('Akte Kematian')
            ->formatStateUsing(fn ($record) => $record->akte_kematian 
            ? '<a href="' . Storage::url($record->akte_kematian) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->akte_kematian) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('kartu_bpjs')
            ->label('Kartu BPJS')
            ->formatStateUsing(fn ($record) => $record->kartu_bpjs 
            ? '<a href="' . Storage::url($record->kartu_bpjs) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->kartu_bpjs) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('drk')
            ->label('DRK')
            ->formatStateUsing(fn ($record) => $record->drk 
            ? '<a href="' . Storage::url($record->up_efaktur) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->up_efaktur) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
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
    //         KPRStats::class,
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

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DajamResource\Pages;
use App\Models\Dajam;
use App\Models\form_kpr;
use App\Models\form_pajak;
use App\Models\PencairanAkad;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use App\Filament\Resources\PencairanAkadResource\RelationManagers;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
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
use App\Models\FormKpr;
use App\Models\Audit;
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use App\Filament\Resources\KPRStats;

class DajamResource extends Resource
{
    protected static ?string $model = Dajam::class;

    protected static ?string $title = "Form Input Data Dajam";
    protected static ?string $navigationGroup = "Keuangan";
    protected static ?string $pluralLabel = "Data Dajam";
    protected static ?string $navigationLabel = "Data Dajam";
    protected static ?string $pluralModelLabel = 'Daftar Data Dajam';
    protected static ?string $navigationIcon = 'heroicon-o-arrow-down-on-square-stack';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
                ->schema([
                    Select::make('siteplan')
                        ->label('Blok')
                        ->options(fn () => form_kpr::pluck('siteplan', 'siteplan'))
                        ->searchable()
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $kprData = form_kpr::where('siteplan', $state)->first();
                            $akadData = PencairanAkad::where('siteplan', $state)->first();
                            $pajakData = form_pajak::where('siteplan', $state)->first();

                            $maxKpr = $kprData->maksimal_kpr ?? 0;
                            $nilaiPencairan = $akadData->nilai_pencairan ?? 0;
                            $dajamPph = $pajakData->jumlah_pph ?? 0;
                            $dajamBphtb = $pajakData->jumlah_bphtb ?? 0;



                            $set('bank', $kprData->bank ?? null);
                            $set('nama_konsumen', $kprData->nama_konsumen ?? null);
                            $set('max_kpr', $maxKpr);
                            $set('nilai_pencairan', $nilaiPencairan);
                            $set('dajam_pph', $dajamPph);
                            $set('dajam_bphtb', $dajamBphtb);
                            $set('total_dajam', max(0, $maxKpr - $nilaiPencairan));
                        }),

                    Select::make('bank')
                        ->options([
                            'btn_cikarang' => 'BTN Cikarang',
                            'btn_bekasi' => 'BTN Bekasi',
                            'btn_karawang' => 'BTN Karawang',
                            'bjb_syariah' => 'BJB Syariah',
                            'bjb_jababeka' => 'BJB Jababeka',
                            'btn_syariah' => 'BTN Syariah',
                            'brii_bekasi' => 'BRI Bekasi',
                        ])
                        ->required()
                        ->label('Bank'),

                    TextInput::make('nama_konsumen')
                        ->label('Nama Konsumen')
                        ->reactive(),
                        
                    TextInput::make('no_debitur')
                        ->label('No. Debitur')
                        ->reactive(),


                    TextInput::make('max_kpr')
                        ->label('Maksimal KPR')
                        ->prefix('Rp')
                        ->reactive(),

                    TextInput::make('nilai_pencairan')
                        ->label('Nilai Pencairan')
                        ->prefix('Rp')
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $get) {
                            $jumlahRealisasi = (int) $get('jumlah_realisasi_dajam');
                            $dajamPph = (int) $get('dajam_pph');
                            $dajamBphtb = (int) $get('dajam_bphtb');

                            $set('pembukuan', max(0, $jumlahRealisasi - ($dajamPph + $dajamBphtb)));
                        })
                        ->afterStateHydrated(function (callable $set, $get) {
                            $jumlahRealisasi = (int) $get('jumlah_realisasi_dajam');
                            $dajamPph = (int) $get('dajam_pph');
                            $dajamBphtb = (int) $get('dajam_bphtb');

                            $set('pembukuan', max(0, $jumlahRealisasi - ($dajamPph + $dajamBphtb)));
                        }),
                    
                        TextInput::make('total_dajam')
                        ->label('Jumlah Dajam')
                        ->prefix('Rp')
                        ->reactive(),
            
                        TextInput::make('dajam_pph')
                        ->label('Dajam PPH')
                        ->prefix('Rp')
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(function (callable $set, $get) {
                            $jumlahRealisasi = (int) $get('jumlah_realisasi_dajam');
                            $dajamPph = (int) $get('dajam_pph');
                            $dajamBphtb = (int) $get('dajam_bphtb');

                            $set('pembukuan', max(0, $jumlahRealisasi - ($dajamPph + $dajamBphtb)));
                        })
                        ->afterStateHydrated(function (callable $set, $get) {
                            $jumlahRealisasi = (int) $get('jumlah_realisasi_dajam');
                            $dajamPph = (int) $get('dajam_pph');
                            $dajamBphtb = (int) $get('dajam_bphtb');

                            $set('pembukuan', max(0, $jumlahRealisasi - ($dajamPph + $dajamBphtb)));
                        }),
                    
                    TextInput::make('dajam_bphtb')
                        ->label('Dajam BPHTB')
                        ->prefix('Rp')
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, $get) => 
                            $set('pembukuan', max(0, (int) $get('jumlah_realisasi_dajam') - 
                                (int) $get('dajam_pph') - (int) $get('dajam_bphtb')
                            ))
                        ),
            
                        TextInput::make('dajam_sertifikat')
                        ->label('Dajam Sertifikat')
                        ->prefix('Rp')
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, $get) => 
                            $set('jumlah_realisasi_dajam', max(0, (int) $get('total_dajam') - (
                                (int) $get('dajam_sertifikat') +
                                (int) $get('dajam_imb') +
                                (int) $get('dajam_listrik') +
                                (int) $get('dajam_jkk') +
                                (int) $get('dajam_bestek')
                            )))
                            ),
                    
            
                    TextInput::make('dajam_imb')
                        ->label('Dajam IMB')
                        ->prefix('Rp')
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, $get) => 
                            $set('jumlah_realisasi_dajam', max(0, (int) $get('total_dajam') - (
                                (int) $get('dajam_sertifikat') +
                                (int) $get('dajam_imb') +
                                (int) $get('dajam_listrik') +
                                (int) $get('dajam_jkk') +
                                (int) $get('dajam_bestek')
                            )))
                            ),
            
                    TextInput::make('dajam_listrik')
                        ->label('Dajam Listrik')
                        ->prefix('Rp')
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, $get) => 
                            $set('jumlah_realisasi_dajam', max(0, (int) $get('total_dajam') - (
                                (int) $get('dajam_sertifikat') +
                                (int) $get('dajam_imb') +
                                (int) $get('dajam_listrik') +
                                (int) $get('dajam_jkk') +
                                (int) $get('dajam_bestek')
                            )))
                            ),
            
                    TextInput::make('dajam_jkk')
                        ->label('Dajam JKK')
                        ->prefix('Rp')
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, $get) => 
                            $set('jumlah_realisasi_dajam', max(0, (int) $get('total_dajam') - (
                                (int) $get('dajam_sertifikat') +
                                (int) $get('dajam_imb') +
                                (int) $get('dajam_listrik') +
                                (int) $get('dajam_jkk') +
                                (int) $get('dajam_bestek')
                            )))
                            ),

                    TextInput::make('dajam_bestek')
                        ->label('Dajam Bestek')
                        ->prefix('Rp')
                        ->live()
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, $get) => 
                            $set('jumlah_realisasi_dajam', max(0, (int) $get('total_dajam') - (
                                (int) $get('dajam_sertifikat') +
                                (int) $get('dajam_imb') +
                                (int) $get('dajam_listrik') +
                                (int) $get('dajam_jkk') +
                                (int) $get('dajam_bestek')
                            )))
                            ),           

                            TextInput::make('jumlah_realisasi_dajam')
                            ->label('Jumlah Realisasi Dajam')
                            ->prefix('Rp')
                            ->reactive()
                            // ->dehydrated()
                            ->live()
                            ->afterStateUpdated(function (callable $set, $get) {
                                $jumlahRealisasi = max(0, (int) $get('total_dajam') - (
                                    (int) $get('dajam_sertifikat') +
                                    (int) $get('dajam_imb') +
                                    (int) $get('dajam_listrik') +
                                    (int) $get('dajam_jkk') +
                                    (int) $get('dajam_bestek')
                                ));
                                $set('jumlah_realisasi_dajam', $jumlahRealisasi);

                                $set('pembukuan', max(0, $jumlahRealisasi - (
                                    (int) $get('dajam_pph') + (int) $get('dajam_bphtb')
                                )));
                            }),

                            TextInput::make('pembukuan')
                            ->label('Pembukuan')
                            ->prefix('Rp')
                            ->reactive()
                            ->live()
                            ->dehydrated()
                            ->afterStateUpdated(fn ($state, callable $set, $get) => 
                                $set('pembukuan', max(0, (int) $get('jumlah_realisasi_dajam') - 
                                    (int) $get('dajam_pph') - (int) $get('dajam_bphtb')
                                ))
                            )
                            ->afterStateHydrated(fn (callable $set, $get) => 
                                $set('pembukuan', max(0, (int) $get('jumlah_realisasi_dajam') - 
                                    (int) $get('dajam_pph') - (int) $get('dajam_bphtb')
                                ))
                            ),

            Fieldset::make('Dokumen')
                ->schema([
                    FileUpload::make('up_spd5')
                        ->disk('public')
                        ->nullable()
                        ->label('Upload SPD 5')
                        ->downloadable()
                        ->previewable(false),

                    FileUpload::make('up_lainnya')
                        ->disk('public')
                        ->nullable()
                        ->label('Upload Lainnya')
                        ->downloadable()
                        ->previewable(false),
                    ]),
                
                    ]),
                ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siteplan')->searchable()->label('Blok'),
                TextColumn::make('bank')
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
            TextColumn::make('nama_konsumen')->searchable()->label('Nama Konsumen'),
            TextColumn::make('no_debitur')->searchable()->label('No. Debitur'),
            TextColumn::make('max_kpr')
            ->searchable()
            ->label('Max KPR')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('nilai_pencairan')
            ->searchable()
            ->label('Nilai Pencairan')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('total_dajam')
            ->searchable()
            ->label('Jumlah Dajam')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dajam_sertifikat')
            ->searchable()
            ->label('Dajam Sertifikat')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dajam_imb')
            ->searchable()
            ->label('Dajam IMB')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dajam_listrik')
            ->searchable()
            ->label('Dajam Listrik')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dajam_jkk')
            ->searchable()
            ->label('Dajam JKK')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dajam_bestek')
            ->searchable()
            ->label('Dajam Bestek')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('jumlah_realisasi_dajam')
            ->searchable()
            ->label('Jumlah Realisasi Dajam')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dajam_pph')
            ->searchable()
            ->label('Dajam PPH')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('dajam_bphtb')
            ->searchable()
            ->label('Dajam BPHTB')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('pembukuan')
            ->searchable()
            ->label('Pembukuan')            
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            Tables\Columns\TextColumn::make('up_spd5')
            ->label('Upload SPD 5')
            ->formatStateUsing(fn ($record) => $record->up_pbb
            ? '<a href="' . Storage::url($record->up_pbb) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->up_pbb) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
            ->html()
            ->sortable()
            ->searchable(),

        Tables\Columns\TextColumn::make('up_lainnya')
            ->label('Upload Dokumen Lainnya')
            ->formatStateUsing(fn ($record) => $record->up_img
            ? '<a href="' . Storage::url($record->up_img) . '" target="_blank">Lihat </a> | 
            <a href="' . Storage::url($record->up_img) . '" download>Download</a>' 
            : 'Tidak Ada Dokumen')
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
                TrashedFilter::make()
                ->label('Data yang dihapus') 
                ->native(false),

                Filter::make('bank')
                    ->label('Bank')
                    ->form([
                        Select::make('bank')
                            ->options([
                                'btn_cikarang' => 'BTN Cikarang',
                                'btn_bekasi' => 'BTN Bekasi',
                                'btn_karawang' => 'BTN Karawang',
                                'bjb_syariah' => 'BJB Syariah',
                                'bjb_jababeka' => 'BJB Jababeka',
                                'btn_syariah' => 'BTN Syariah',
                                'brii_bekasi' => 'BRI Bekasi',
                            ])
                            ->nullable()
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['bank']), fn ($q) =>
                            $q->where('bank', $data['bank'])
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
                                ->title('Data Dajam Diubah')
                                ->body('Data Dajam telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Dajam Dihapus')
                                ->body('Data Dajam telah berhasil dihapus.')),                            
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
                            ->title('Data Dajam')
                            ->body('Data Dajam berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label('Hapus Permanen')
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Dajam')
                            ->body('Data Dajam berhasil dihapus secara permanen.')
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
                                ->title('Data Dajamg')
                                ->body('Data Dajam berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Dajam')
                                ->body('Data Dajam berhasil dihapus secara permanen.'))                        ->requiresConfirmation()
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
                                ->title('Data Dajam')
                                ->body('Data Dajam berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Blok, Bank, Nama Konsumen, Maksimal KPR, Nilai Pencairan, Jumlah Dajam, Dajam Sertifikat, Dajam IMB, Dajam Listrik, Dajam JKK, Dajam Bestek, Jumlah Realisasi Dajam, Dajam PPH, Dajam BPHTB, Pembukuan\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->bank}, {$record->nama_konsumen}, {$record->max_kpr}, {$record->nilai_pencairan}, {$record->jumlah_dajam}, {$record->dajam_sertifikat}, {$record->dajam_imb}, {$record->dajam_listrik}, {$record->dajam_jkk}, {$record->dajam_bestek}, {$record->jumlah_realisasi_dajam}, {$record->dajam_pph}, {$record->dajam_bphtb}, {$record->pembukuan}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'Dajam.csv');
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
            'index'  => Pages\ListDajams::route('/'),
            'create' => Pages\CreateDajam::route('/create'),
            'edit'   => Pages\EditDajam::route('/{record}/edit'),
        ];
    }
}

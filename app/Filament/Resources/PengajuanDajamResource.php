<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PengajuanDajamResource\Pages;
use App\Filament\Resources\PengajuanDajamResource\RelationManagers;
use App\Models\pengajuan_dajam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\verifikasi_dajam;
use App\Models\VerifikasiDajam;
use App\Models\Dajam;
use App\Models\form_kpr;
use App\Models\form_pajak;
use App\Models\PencairanAkad;
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
use App\Models\FormKpr;
use App\Models\Audit;
use App\Filament\Resources\GCVResource;
use App\Models\GCV;
use App\Filament\Resources\KPRStats;
use Illuminate\Support\Facades\Auth;


class PengajuanDajamResource extends Resource
{
    protected static ?string $model = pengajuan_dajam::class;
    protected static ?string $title = "Form Pengajuan Dajam GCV";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Data Pengajuan Dajam GCV";
    protected static ?string $navigationLabel = "Pengajuan Dajam GCV";
    protected static ?string $pluralModelLabel = 'Daftar Pengajuan Dajam GCV';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
                ->schema([
                    Select::make('siteplan')
                        ->label('Blok')
                        ->options(fn () => form_kpr::pluck('siteplan', 'siteplan'))
                        ->searchable()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $kprData = form_kpr::where('siteplan', $state)->first();
                            $akadData = PencairanAkad::where('siteplan', $state)->first();
                            $pajakData = form_pajak::where('siteplan', $state)->first();
                            // $dajamData = dajam::where('siteplan', $state)->first();

                            $maxKpr = $kprData->maksimal_kpr ?? 0;
                            $nilaiPencairan = $akadData->nilai_pencairan ?? 0;
                            $dajamPph = $pajakData->jumlah_pph ?? 0;
                            $dajamBphtb = $pajakData->jumlah_bphtb ?? 0;
                            $dajamTotal = $dajamData->total_dajam ?? 0;

                            $set('bank', $kprData->bank ?? null);
                            $set('nama_konsumen', $kprData->nama_konsumen ?? null);
                            $set('max_kpr', $maxKpr);
                            $set('nilai_pencairan', $nilaiPencairan);
                            $set('dajam_pph', $dajamPph);
                            $set('dajam_bphtb', $dajamBphtb);
                            $set('total_dajam', max(0, $maxKpr - $nilaiPencairan));
                            $set('dajam_sertifikat', $dajamData->dajam_sertifikat ?? null);
                            $set('dajam_imb', $dajamData->dajam_imb ?? null);
                            $set('dajam_listrik', $dajamData->dajam_listrik ?? null);
                            $set('dajam_jkk', $dajamData->dajam_jkk ?? null);
                            $set('dajam_bestek', $dajamData->dajam_bestek ?? null);
                            $set('jumlah_realisasi_dajam', $dajamData->jumlah_realisasi_dajam ?? null);
                            $set('pembukuan', $dajamData->pembukuan ?? null);
                            $set('no_debitur', $dajamData->no_debitur ?? null);
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Bank'),

                    TextInput::make('nama_konsumen')
                        ->label('Nama Konsumen')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->reactive(),
                    
                    TextInput::make('no_debitur')
                        ->label('No. Debitur')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->reactive(),

                    Select::make('nama_dajam')
                        ->options([
                            'sertifikat' => 'Sertifikat',
                            'imb' => 'IMB',
                            'jkk' => 'JKK',
                            'bestek' => 'Bestek',
                            'pph' => 'PPH',
                            'bphtb' => 'BPHTB',
                        ])
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Nama Dajam'),
                    
                    TextInput::make('no_surat')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                        ->label('No. Surat'),
                        
                    
                    DatePicker::make('tanggal_pengajuan')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                        ->label('Tanggal Pengajuan'),

                    TextInput::make('nilai_pencairan')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Nilai Pencairan'),

                    Select::make('status_dajam')
                        ->options([
                            'sudah_diajukan' => 'Sudah Diajukan',
                            'belum_diajukan' => 'Belum Diajukan',
                        ])
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Status Dajam'),
                    
                        Fieldset::make('Dokumen')
                        ->schema([
                            FileUpload::make('up_surat_pengajuan')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal Pajak']);
                            })())
                            ->disk('public')
                            ->nullable()
                            ->label('Upload Surat Pengajuan')
                            ->downloadable()
                            ->multiple()
                            ->previewable(false),

                            FileUpload::make('up_nominatif_pengajuan')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal Pajak']);
                            })())
                            ->disk('public')
                            ->nullable()
                            ->label('Upload Nominatif Pengajuan')
                            ->downloadable()
                            ->multiple()
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
            TextColumn::make('nama_dajam')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                       'sertifikat' => 'Sertifikat',
                            'imb' => 'IMB',
                            'jkk' => 'JKK',
                            'bestek' => 'Bestek',
                            'pph' => 'PPH',
                            'bphtb' => 'BPHTB',
                default => ucfirst($state),
            })
            ->sortable()
            ->searchable()
            ->label('Nama Dajam'), 

            TextColumn::make('no_surat')
            ->searchable()
            ->label('No. Surat'),

            TextColumn::make('tanggal_pengajuan')
            ->searchable()
            ->label('Tanggal Pengajuan')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

            
            TextColumn::make('nilai_pencairan')
            ->searchable()
            ->label('Nilai Pencairan')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),


            TextColumn::make('status_dajam')
                ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sudah_diajukan' => 'Sudah Diajukan',
                        'belum_diajukan' => 'Belum Diajukan',
                default => ucfirst($state), 
            })
            ->sortable()
            ->searchable()
            ->label('Status Dajam'),

            TextColumn::make('up_surat_pengajuan')
            ->label('Surat Pengajuan')
            ->formatStateUsing(function ($record) {
                if (!$record->up_surat_pengajuan) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_surat_pengajuan) ? $record->up_surat_pengajuan : json_decode($record->up_surat_pengajuan, true);

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

        TextColumn::make('up_nominatif_pengajuan')
        ->label('Nominatif Pengajuan')
        ->formatStateUsing(function ($record) {
            if (!$record->up_nominatif_pengajuan) {
                return 'Tidak Ada Dokumen';
            }

            $files = is_array($record->up_nominatif_pengajuan) ? $record->up_nominatif_pengajuan : json_decode($record->up_nominatif_pengajuan, true);

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
            
                Filter::make('status_dajam')
                    ->form([
                        Select::make('status_dajam')
                            ->options([
                                'sudah_diajukan' => 'Sudah Diajukan',
                                'belum_diajukan' => 'Belum Diajukan',
                            ])
                            ->nullable()
                            ->label('Status Dajam')
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_dajam']), fn ($q) =>
                            $q->where('status_dajam', $data['status_dajam'])
                        )
                    ),

                    Filter::make('nama_dajam')
                    ->form([
                        Select::make('nama_dajam')
                            ->options([
                                'sertifikat' => 'Sertifikat',
                            'imb' => 'IMB',
                            'jkk' => 'JKK',
                            'bestek' => 'Bestek',
                            'pph' => 'PPH',
                            'bphtb' => 'BPHTB',
                            ])
                            ->nullable()
                            ->label('Nama Dajam')
                            ->native(false),
                    ])
                    ->query(fn ($query, $data) =>
                        $query->when(isset($data['status_dajam']), fn ($q) =>
                            $q->where('status_dajam', $data['status_dajam'])
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
                                ->title('Verifikasi Dajam Diubah')
                                ->body('Verifikasi Dajam telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Verifikasi Dajam Dihapus')
                                ->body('Verifikasi Dajam telah berhasil dihapus.')),                         
                    // RestoreAction::make()
                    //     ->label('Pulihkan')
                    //     ->successNotificationTitle('Data berhasil dipulihkan')
                    //     ->successRedirectUrl(route('filament.admin.resources.audits.index')),
                    RestoreAction::make()
                    ->color('info')
                    ->label(fn ($record) => "Kembalikan {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Kembalikan Blok{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengembalikan blok {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Verifikasi Dajam')
                            ->body('Verifikasi Dajam berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Verifikasi Dajam')
                            ->body('Verifikasi Dajam berhasil dihapus secara permanen.')
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
                                ->title('Verifikasi Dajamg')
                                ->body('Verifikasi Dajam berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Verifikasi Dajam')
                                ->body('Verifikasi Dajam berhasil dihapus secara permanen.'))
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
                                ->title('Verifikasi Dajam')
                                ->body('Verifikasi Dajam berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Blok, Bank, No. Debitur, Nama Konsumen, Nama Dajam, No. Surat, Tanggal Pengajuan, Nilai Pencairan, Status Dajam\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->bank}, {$record->no_debitur}, {$record->nama_konsumen}, {$record->nama_dajam}, {$record->no_surat}, {$record->tanggal_pengajuan}, {$record->nilai_pencairan}, {$record->status_dajam}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'PengajuanDajam.csv');
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
            'index' => Pages\ListPengajuanDajams::route('/'),
            'create' => Pages\CreatePengajuanDajam::route('/create'),
            'edit' => Pages\EditPengajuanDajam::route('/{record}/edit'),
        ];
    }
}

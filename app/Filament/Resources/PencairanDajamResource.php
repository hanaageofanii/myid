<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PencairanDajamResource\Pages;
use App\Filament\Resources\PencairanDajamResource\RelationManagers;
use App\Models\pencairan_dajam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\pengajuan_dajam;
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


class PencairanDajamResource extends Resource
{
    protected static ?string $model = pencairan_dajam::class;

    protected static ?string $title = "Form Pencairan Dajam";
    protected static ?string $navigationGroup = "Keuangan";
    protected static ?string $pluralLabel = "Data Pencairan Dajam";
    protected static ?string $navigationLabel = "Pencairan Dajam";
    protected static ?string $pluralModelLabel = 'Daftar Pencairan Dajam';
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';
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
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $kprData = form_kpr::where('siteplan', $state)->first();
                            // $akadData = PencairanAkad::where('siteplan', $state)->first();
                            // $pajakData = form_pajak::where('siteplan', $state)->first();
                            // $dajamData = dajam::where('siteplan', $state)->first();
                            $pengDajam = pengajuan_dajam::where('siteplan', $state)->first();

                            $maxKpr = $kprData->maksimal_kpr ?? 0;
                            // $nilaiPencairan = $akadData->nilai_pencairan ?? 0;
                            // $dajamPph = $pajakData->jumlah_pph ?? 0;
                            // $dajamBphtb = $pajakData->jumlah_bphtb ?? 0;
                            // $dajamTotal = $dajamData->total_dajam ?? 0;

                            $set('bank', $kprData->bank ?? null);
                            $set('nama_konsumen', $kprData->nama_konsumen ?? null);
                            $set('max_kpr', $maxKpr);
                            // $set('nilai_pencairan', $nilaiPencairan);
                            $set('pembukuan', $dajamData->pembukuan ?? null);
                            $set('no_debitur', $dajamData->no_debitur ?? null);
                            $set('nama_dajam', $pengDajam->nama_dajam ?? null);
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->required()
                        ->label('Bank'),

                    TextInput::make('nama_konsumen')
                        ->label('Nama Konsumen')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive(),
                    
                    TextInput::make('no_debitur')
                        ->label('No. Debitur')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
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
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->label('Nama Dajam'),
                    
                        TextInput::make('nilai_dajam')
                        ->label('Nilai Dajam')
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive()
                        ->prefix('Rp')
                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                            $set('selisih_dajam', max(0, ($state ?? 0) - ($get('nilai_pencairan') ?? 0)))
                        ),
                        
                    
                    DatePicker::make('tanggal_pencairan')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Kasir 1']);
                    })())
                        ->label('Tanggal Pencairan')
                        ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),


                        TextInput::make('nilai_pencairan')
                        ->label('Nilai Pencairan')
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->reactive()
                        ->prefix('Rp')
                        ->afterStateUpdated(fn ($state, callable $set, callable $get) => 
                            $set('selisih_dajam', max(0, ($get('nilai_dajam') ?? 0) - ($state ?? 0)))
                        ),


                        TextInput::make('selisih_dajam')
                        ->label('Selisih Dajam')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Kasir 1']);
                        })())
                        ->prefix('Rp'),
                    
                        Fieldset::make('Dokumen')
                        ->schema([
                            FileUpload::make('up_rekening_koran')->disk('public')->nullable()->multiple()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })())->label('Upload Rekening Korang')
                                ->downloadable()->previewable(false),

                            FileUpload::make('up_lainnya')->disk('public')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Kasir 1']);
                            })())->nullable()->label('Upload Dokumen Lainnya')->multiple()
                                ->downloadable()->previewable(false),
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

            TextColumn::make('nilai_dajam')
            ->searchable()
            ->label('Nilai Dajam')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),


            TextColumn::make('tanggal_pencairan')
            ->searchable()
            ->label('Tanggal Pencairan')
            ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),

            
            TextColumn::make('nilai_pencairan')
            ->searchable()
            ->label('Nilai Pencairan')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),


            TextColumn::make('selisih_dajam')
            ->searchable()
            ->label('Selisih Dajam')
            ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),


            TextColumn::make('up_rekening_koran')
            ->label('Rekening Koran')
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

            TextColumn::make('up_lainnya')
            ->label('Dokumen Lainnya')
            ->formatStateUsing(function ($record) {
                if (!$record->up_lainnya) {
                    return 'Tidak Ada Dokumen';
                }

                $files = is_array($record->up_lainnya) ? $record->up_lainnya: json_decode($record->up_lainnya, true);

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
                                ->title('Data Pencairan Dajam Diubah')
                                ->body('Data Pencairan Dajam telah berhasil disimpan.')),                    
                        DeleteAction::make()
                        ->color('danger')
                        ->label(fn ($record) => "Hapus Blok {$record->siteplan}")
                        ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok{$record->siteplan}")
                        ->modalDescription(fn ($record) => "Apakah Anda yakin ingin menghapus blok {$record->siteplan}?")
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencairan Dajam Dihapus')
                                ->body('Data Pencairan Dajam telah berhasil dihapus.')),                         
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
                            ->title('Data Pencairan Dajam')
                            ->body('Data Pencairan Dajam berhasil dikembalikan.')
                    ),
                    ForceDeleteAction::make()
                    ->color('primary')
                    ->label(fn ($record) => "Hapus Permanent {$record->siteplan}")
                    ->modalHeading(fn ($record) => "Konfirmasi Hapus Blok Permanent{$record->siteplan}")
                    ->modalDescription(fn ($record) => "Apakah Anda yakin ingin mengahapus blok secara permanent {$record->siteplan}?")
                    ->successNotification(
                        Notification::make()
                            ->success()
                            ->title('Data Pencairan Dajam')
                            ->body('Data Pencairan Dajam berhasil dihapus secara permanen.')
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
                                ->title('Data Pencairan Dajam')
                                ->body('Data Pencairan Dajam berhasil dihapus.'))                        
                                ->requiresConfirmation()
                        ->action(fn (Collection $records) => $records->each->delete()),
                
                    BulkAction::make('forceDelete')
                        ->label('Hapus Permanent')
                        ->icon('heroicon-o-x-circle') 
                        ->color('warning')
                        ->successNotification(
                            Notification::make()
                                ->success()
                                ->title('Data Pencairan Dajam')
                                ->body('Data Pencairan Dajam berhasil dihapus secara permanen.'))
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
                                ->title('Data Pencairan Dajam')
                                ->body('Data Pencairan Dajam berhasil dikembalikan.')),
                ]);
    }

    public static function exportData(Collection $records)
    {
        $csvData = "ID, Blok, Bank, No. Debitur, Nama Konsumen, Nama Dajam, Nilai Dajam, Tanggal Pencairan, Nilai Pencairan, Selisih Dajam\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->bank}, {$record->no_debitur}, {$record->nama_konsumen}, {$record->nama_dajam}, {$record->nilai_dajam}, {$record->tanggal_pencairan}, {$record->nilai_pencairan}, {$record->selisih_dajam}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'PencairanDajam.csv');
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
            'index' => Pages\ListPencairanDajams::route('/'),
            'create' => Pages\CreatePencairanDajam::route('/create'),
            'edit' => Pages\EditPencairanDajam::route('/{record}/edit'),
        ];
    }
}

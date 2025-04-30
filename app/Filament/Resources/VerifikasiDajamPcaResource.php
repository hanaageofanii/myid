<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerifikasiDajamPcaResource\Pages;
use App\Filament\Resources\VerifikasiDajamPcaResource\RelationManagers;
use App\Models\verifikasi_dajam_pca;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use App\Models\verifikasi_dajam;
use App\Models\VerifikasiDajam;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Models\Dajam;
use App\Models\form_pajak_pca;
use App\Models\pencairan_akad_pca;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\form_dp_pca;
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
use App\Models\form_kpr_pca;
use App\Models\Audit;
use App\Filament\Resources\PCAResource;
use App\Models\PCA;
use App\Filament\Resources\KPRStats;
use Illuminate\Support\Facades\Auth;

class VerifikasiDajamPcaResource extends Resource
{
    protected static ?string $model = verifikasi_dajam_pca::class;

    protected static ?string $title = "Form Verifikasi Dajam PCA";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Data Verifikasi Dajam PCA";
    protected static ?string $navigationLabel = "Verifikasi Dajam PCA";
    protected static ?string $pluralModelLabel = 'Daftar Verifikasi Dajam PCA';
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
                ->schema([
                    Select::make('siteplan')
                        ->label('Blok')
                        ->options(fn () => form_kpr_pca::pluck('siteplan', 'siteplan'))
                        ->searchable()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $kprData = form_kpr_pca::where('siteplan', $state)->first();
                            $akadData = pencairan_akad_pca::where('siteplan', $state)->first();
                            $pajakData = form_pajak_pca::where('siteplan', $state)->first();
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->required()
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

                    TextInput::make('max_kpr')
                        ->label('Maksimal KPR')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->prefix('Rp')
                        ->reactive(),

                    TextInput::make('nilai_pencairan')
                        ->label('Nilai Pencairan')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->prefix('Rp')
                        ->reactive(),
            
                        TextInput::make('dajam_pph')
                        ->label('Dajam PPH')
                        ->prefix('Rp')
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal Pajak']);
                            })())
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
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal Pajak']);
                            })())
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

            Fieldset::make('Verifikasi Dajam')
                ->schema([
                    DatePicker::make('tgl_pencairan_dajam_sertifikat')
                    ->label('Tanggal Pencairan Dajam Sertifikat')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })()),

                    DatePicker::make('tgl_pencairan_dajam_imb')
                    ->label('Tanggal Pencairan Dajam IMB')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })()),

                    DatePicker::make('tgl_pencairan_dajam_listrik')
                    ->label('Tanggal Pencairan Dajam Listrik')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })()),

                    DatePicker::make('tgl_pencairan_dajam_jkk')
                    ->label('Tanggal Pencairan Dajam JKK')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })()),

                    DatePicker::make('tgl_pencairan_dajam_pph')
                    ->label('Tanggal Pencairan Dajam PPH')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })()),

                    DatePicker::make('tgl_pencairan_dajam_bphtb')
                    ->label('Tanggal Pencairan Dajam BPHTB')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })()),

                    TextInput::make('total_pencairan_dajam')
                    ->label('Total Pencairan Dajam')
                    ->live()
                    ->reactive()
                    ->prefix('Rp')
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->afterStateUpdated(fn ($state, callable $set, $get) => 
                        $set('sisa_dajam', max(0, (int) $get('total_dajam') - (int) $state))
                    ),

                    TextInput::make('sisa_dajam')
                    ->label('Sisa Dajam')
                    ->live()
                    ->reactive()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->prefix('Rp')
                    ->afterStateUpdated(fn ($state, callable $set, $get) => 
                                $set('sisa_dajam', max(0, (int) $get('total_dajam') - 
                                    (int) $get('total_pencairan_dajam') 
                                ))
                            )
                            ->afterStateHydrated(fn (callable $set, $get) => 
                            $set('sisa_dajam', max(0, (int) $get('total_dajam') - 
                            (int) $get('total_pencairan_dajam') 
                                ))
                            ),

                    Select::make('status_dajam')
                    ->options([
                        'sudah_diajukan' => 'Sudah Diajukan',
                        'belum_diajukan' => 'Belum Diajukan'
                    ])
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal Pajak']);
                    })())
                    ->label('Status Dajam')
                ]),

                Fieldset::make('Dokumen')
                ->schema([
                    FileUpload::make('up_spd5')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->label('Upload SPD 5')
                        ->downloadable()
                        ->previewable(false),

                    FileUpload::make('up_lainnya')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
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
            TextColumn::make('no_surat_pengajuan')
                ->searchable()
                ->label('No. Surat Pengajuan'),
            TextColumn::make('tgl_pencairan_dajam_sertifikat')
                ->searchable()
                ->label('Tanggal Pencairan Dajam Sertifikat')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('tgl_pencairan_dajam_imb')
                ->searchable()
                ->searchable()
                ->label('Tanggal Pencairan Dajam IMB')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('tgl_pencairan_dajam_listrik')
                ->searchable()
                ->label('Tanggal Pencairan Dajam Listrik')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('tgl_pencairan_dajam_jkk')
                ->searchable()
                ->label('Tanggal Pencairan Dajam JKK')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('tgl_pencairan_dajam_bester')
                ->searchable()
                ->label('Tanggal Pencairan Dajam Bester')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('tgl_pencairan_dajam_pph')
                ->searchable()
                ->label('Tanggal Pencairan Dajam PPH')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('tgl_pencairan_dajam_bphtb')
                ->searchable()
                ->label('Tanggal Pencairan Dajam BPHTB')
                ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
            TextColumn::make('total_pencairan_dajam')
                ->searchable()
                ->label('Total Pencairan Dajam')
                ->formatStateUsing(fn ($state) => 'Rp ' . number_format((float) $state, 0, ',', '.')),
            TextColumn::make('sisa_dajam')
                ->searchable()
                ->label('Sisa Dajam')
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

                TextColumn::make('up_spd5')
                ->label('SPD5')
                ->formatStateUsing(function ($record) {
                    if (!$record->up_spd5) {
                        return 'Tidak Ada Dokumen';
                    }
    
                    $files = is_array($record->up_spd5) ? $record->up_spd5 : json_decode($record->up_spd5, true);
    
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
    
                    $files = is_array($record->up_lainnya) ? $record->up_lainnya : json_decode($record->up_lainnya, true);
    
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
        $csvData = "ID, Blok, Bank, Nama Konsumen, Maksimal KPR, Nilai Pencairan, Jumlah Dajam, Dajam Sertifikat, Dajam IMB, Dajam Listrik, Dajam JKK, Dajam Bestek, Jumlah Realisasi Dajam, Dajam PPH, Dajam BPHTB, Pembukuan, No. Surat Pengajuan, Tanggal Pencairan Dajam Sertifikat, Tanggal Pencairan Dajam IMB, Tanggal Pencairan Dajam Listrik, Tanggal Pencairan Dajam JKK, Tanggal Pencairan Dajam Bestek, Tanggal Pencairan Dajam PPH, Tanggal Pencairan Dajam BPHTB, Total Pencairan Dajam, Sisa Dajam, Status Dajam\n";
    
        foreach ($records as $record) {
            $csvData .= "{$record->id}, {$record->siteplan}, {$record->bank}, {$record->nama_konsumen}, {$record->max_kpr}, {$record->nilai_pencairan}, {$record->jumlah_dajam}, {$record->dajam_sertifikat}, {$record->dajam_imb}, {$record->dajam_listrik}, {$record->dajam_jkk}, {$record->dajam_bestek}, {$record->jumlah_realisasi_dajam}, {$record->dajam_pph}, {$record->dajam_bphtb}, {$record->pembukuan}, {$record->no_surat_pengajuan}, {$record->tgl_pencairan_dajam_sertifikat}, {$record->tgl_pencairan_dajam_imb}, {$record->tgl_pencairan_dajam_listrik}, {$record->tgl_pencairan_dajam_jkk}, {$record->tgl_pencairan_dajam_bester}, {$record->tgl_pencairan_dajam_pph}, {$record->tgl_pencairan_dajam_bphtb}, {$record->total_pencairan_dajam}, {$record->sisa_dajam}, {$record->status_dajam}\n";
        }
    
        return response()->streamDownload(fn () => print($csvData), 'VerifikasiDajam.csv');
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
            'index' => Pages\ListVerifikasiDajamPcas::route('/'),
            'create' => Pages\CreateVerifikasiDajamPca::route('/create'),
            'edit' => Pages\EditVerifikasiDajamPca::route('/{record}/edit'),
        ];
    }
}

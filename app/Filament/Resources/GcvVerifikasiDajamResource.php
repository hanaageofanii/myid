<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvVerifikasiDajamResource\Pages;
use App\Filament\Resources\GcvVerifikasiDajamResource\RelationManagers;
use App\Models\gcv_verifikasi_dajam;
use App\Models\GcvVerifikasiDajam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Facades\Filament;
use App\Models\gcv_uang_muka;
use App\Models\GcvUangMuka;
use App\Models\gcv_stok;
use App\Models\gcvDataSiteplan;
use App\Models\gcv_legalitas;
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
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\DatePicker;
use Filament\Support\Enums\MaxWidth;
use Filament\Tables\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Wizard;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Actions\ForceDeleteAction;
use App\Models\form_kpr;
use Filament\Forms\Components\Wizard\Step;
use Illuminate\Validation\Rule;
use App\Models\gcv_datatandaterima;
use Carbon\Carbon;
use App\Models\gcv_kpr;
use App\Models\gcv_pencairan_akad;
use Filament\Forms\Components\Repeater;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Actions\RestoreBulkAction;
use App\Filament\Resources\GcvUangMukaResource\Widgets\gcv_uang_MukaStats;


class GcvVerifikasiDajamResource extends Resource
{
    protected static ?string $model = gcv_verifikasi_dajam::class;

    protected static ?string $title = "Form Verifikasi Dajam";
    protected static ?string $pluralLabel = "Data Verifikasi Dajam";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $navigationLabel = "Verifikasi Dajam";
    protected static ?string $pluralModelLabel = 'Daftar Verifikasi Dajam';
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';
    protected static bool $isScopedToTenant = false;
      protected static ?string $tenantOwnershipRelationshipName = 'team';

    protected static ?string $tenantRelationshipName = 'team';

    protected static ?int $navigationSort = 7;
    public static function form(Form $form): Form
    {
            return $form->schema([
                Wizard::make([
                    Step::make('Data Konsumen')
                        ->description('Informasi Data Konsumen')
                        ->columns(3)
                        ->schema([
                    Select::make('kavling')
                        ->label('Kavling')
                        ->options(options: [
                            'standar' => 'Standar',
                            'khusus' => 'Khusus',
                            'hook' => 'Hook',
                            'komersil' => 'Komersil',
                            'tanah_lebih' => 'Tanah Lebih',
                            'kios' => 'Kios',
                        ])
                        ->required()
                        ->reactive()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),

Select::make('siteplan')
    ->label('Blok')
    ->searchable()
    ->reactive()
    ->options(function (callable $get) {
        $selectedKavling = $get('kavling');
        $tenant = Filament::getTenant(); // tenant aktif

        if (! $selectedKavling || ! $tenant) {
            return [];
        }

        return gcv_kpr::where('jenis_unit', $selectedKavling)
            ->where('status_akad', 'akad')
            ->where('team_id', $tenant->id) // filter tenant
            ->pluck('siteplan', 'siteplan')
            ->toArray();
    })
    ->disabled(fn () => ! (function () {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        return $user && $user->hasRole(['admin','Legal officer']);
    })())
    ->afterStateUpdated(function ($state, callable $set) {
        $tenant = Filament::getTenant();
        if (! $state || ! $tenant) return;

        $kprData = gcv_kpr::where('siteplan', $state)
            ->where('team_id', $tenant->id)
            ->first();
        $pencairanAkad = gcv_pencairan_akad::where('siteplan', $state)
            ->where('team_id', $tenant->id)
            ->first();

        $set('bank', $kprData?->bank);
        $set('nama_konsumen', $kprData?->nama_konsumen);
        $set('max_kpr', $kprData?->maksimal_kpr);
        $set('no_debitur', $pencairanAkad?->no_debitur);
    }),

                            TextInput::make('nama_konsumen')
                            ->label('Nama Konsumen')
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })())
                        ->reactive(),

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
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })())
                            ->required()
                            ->label('Bank'),

                        TextInput::make('no_debitur')
                            ->label('No. Debitur')
                            ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })())
                        ->reactive(),

                        Forms\Components\TextInput::make('max_kpr')
                            ->label('Maksimal KPR')
                            ->prefix('Rp')
                            ->nullable()
                            ->required()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),
                        ]),

Step::make('Nilai Dajam')
            ->description('Informasi Nilai Dajam')
            ->columns(2)
            ->schema([
                TextInput::make('nilai_pencairan')
                    ->label('Nilai Pencairan')
                    ->prefix('Rp')
                    ->reactive()
                    ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })())
                        ->afterStateUpdated(function (callable $set, $get) {
                        $jumlahRealisasi = (int) $get('jumlah_realisasi_dajam');
                        $pph = (int) $get('dajam_pph');
                        $bphtb = (int) $get('dajam_bphtb');
                        $set('pembukuan', max(0, $jumlahRealisasi - ($pph + $bphtb)));
                    })
                    ->afterStateHydrated(function (callable $set, $get) {
                        $jumlahRealisasi = (int) $get('jumlah_realisasi_dajam');
                        $pph = (int) $get('dajam_pph');
                        $bphtb = (int) $get('dajam_bphtb');
                        $set('pembukuan', max(0, $jumlahRealisasi - ($pph + $bphtb)));
                    }),

                TextInput::make('total_dajam')
                    ->label('Jumlah Dajam')
                    ->prefix('Rp')
                    ->reactive()
                    ->live()
                    ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                    })()),

                    TextInput::make('dajam_sertifikat')
                        ->label('Dajam Sertifikat')
                        ->prefix('Rp')
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
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

                            DatePicker::make('tgl_pencairan_dajam_sertifikat')
                                ->label('Tanggal Pencairan Dajam Sertifikat')
                                ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),

                        TextInput::make('dajam_imb')
                            ->label('Dajam IMB')
                            ->prefix('Rp')
                            ->live()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
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

                        DatePicker::make('tgl_pencairan_dajam_imb')
                            ->label('Tanggal Pencairan Dajam IMB')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('dajam_listrik')
                            ->label('Dajam Listrik')
                            ->prefix('Rp')
                            ->live()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
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

                            DatePicker::make('tgl_pencairan_dajam_listrik')
                                ->label('Tanggal Pencairan Dajam Listrik')
                                ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })()),

                            TextInput::make('dajam_jkk')
                        ->label('Dajam JKK')
                        ->prefix('Rp')
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
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

                        DatePicker::make('tgl_pencairan_dajam_jkk')
                            ->label('Tanggal Pencairan Dajam JKK')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                    TextInput::make('dajam_bestek')
                        ->label('Dajam Bestek')
                        ->prefix('Rp')
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
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

                        DatePicker::make('tgl_pencairan_dajam_bester')
                            ->label('Tanggal Pencairan Dajam Bester')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),

                        TextInput::make('dajam_pph')
                        ->label('Dajam PPH')
                        ->prefix('Rp')
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
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

                    DatePicker::make('tgl_pencairan_dajam_pph')
                        ->label('Tanggal Pencairan Dajam PPH')
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })()),

                     TextInput::make('dajam_bphtb')
                        ->label('Dajam BPHTB')
                        ->prefix('Rp')
                        ->live()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })())
                        ->reactive()
                        ->afterStateUpdated(fn ($state, callable $set, $get) =>
                            $set('pembukuan', max(0, (int) $get('jumlah_realisasi_dajam') -
                                (int) $get('dajam_pph') - (int) $get('dajam_bphtb')
                            ))
                        ),

                        DatePicker::make('tgl_pencairan_dajam_bphtb')
                            ->label('Tanggal Pencairan Dajam BPHTB')
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })()),
                        ]),
                        Step::make('Informasi Data Dajam')
                            ->description('Informasi Nilai Dajam')
                            ->columns(2)
                            ->schema([

                            TextInput::make('jumlah_realisasi_dajam')
                            ->label('Jumlah Realisasi Dajam')
                            ->prefix('Rp')
                            ->reactive()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
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

                            TextInput::make('sisa_dajam')
                    ->label('Sisa Dajam')
                    ->live()
                    ->reactive()
                    ->disabled(fn () => ! (function () {
                        /** @var \App\Models\User|null $user */
                        $user = Auth::user();
                        return $user && $user->hasRole(['admin','Legal officer']);
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

                            TextInput::make('total_pencairan_dajam')
                                ->label('Total Pencairan Dajam')
                                ->live()
                                ->reactive()
                                ->prefix('Rp')
                                ->disabled(fn () => ! (function () {
                                    /** @var \App\Models\User|null $user */
                                    $user = Auth::user();
                                    return $user && $user->hasRole(['admin','Legal officer']);
                                })())
                                ->afterStateUpdated(fn ($state, callable $set, $get) =>
                                    $set('sisa_dajam', max(0, (int) $get('total_dajam') - (int) $state))
                                ),

                        TextInput::make('pembukuan')
                            ->label('Pembukuan')
                            ->prefix('Rp')
                            ->reactive()
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
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

                            TextInput::make('no_surat_pengajuan')
                                ->label('No. Surat Pengajuan')
                                ->disabled(fn () => ! (function () {
                                            /** @var \App\Models\User|null $user */
                                            $user = Auth::user();
                                            return $user && $user->hasRole(['admin','Legal officer']);
                                        })()),

                        Select::make('status_dajam')
                            ->options([
                                'sudah_diajukan' => 'Sudah Diajukan',
                                'belum_diajukan' => 'Belum Diajukan'
                            ])
                            ->disabled(fn () => ! (function () {
                                /** @var \App\Models\User|null $user */
                                $user = Auth::user();
                                return $user && $user->hasRole(['admin','Legal officer']);
                            })())
                            ->label('Status Dajam'),

                            TextArea::make('catatan')
                                ->label('Catatan')
                                ->columnSpanFull()
                                ->disabled(fn () => ! (function () {
                                            /** @var \App\Models\User|null $user */
                                            $user = Auth::user();
                                            return $user && $user->hasRole(['admin','Legal officer']);
                                        })()),
                                    ]),

                             Step::make('Upload Dokumen')
                            ->description('Informasi Dokumen')
                            ->columns(2)
                            ->schema([
                                Fieldset::make('Dokumen')
                ->schema([
                     FileUpload::make('up_spd5')
            ->disk('public')
            ->nullable()
            ->multiple()
            ->disabled(fn () => ! (function () {
                /** @var \App\Models\User|null $user */
                $user = Auth::user();
                return $user && $user->hasRole(['admin','Legal officer']);
            })())
            ->label('Upload SPD 5')
            ->downloadable()
            ->previewable(false)
            ->afterStateHydrated(function ($component, $state) {
                $day = now()->day;

                if ($day === 4 && blank($state)) {
                    Notification::make()
                        ->title('⚠️ Wajib Upload SPD 5')
                        ->body('Hari ini tanggal 4. Harap upload SPD 5.')
                        ->warning()
                        ->persistent()
                        ->send();
                }

                if ($day > 4 && blank($state)) {
                    Notification::make()
                        ->title('❗ SPD 5 Belum Di-upload')
                        ->body('Tanggal upload SPD 5 sudah lewat. Harap segera lengkapi dokumen.')
                        ->danger()
                        ->persistent()
                        ->send();
                }
            }),

                    FileUpload::make('up_lainnya')
                        ->disk('public')
                        ->nullable()
                        ->multiple()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal officer']);
                        })())
                        ->label('Upload Lainnya')
                        ->downloadable()
                        ->previewable(false),
                    ]),
                ])
        ])->columnSpanFull(),
    ]);
}

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kavling')
                ->label('Kavling')
                ->formatStateUsing(fn(string $state): string => match ($state){
                    'standar' => 'Standar',
                    'khusus' => 'Khusus',
                    'hook' => 'Hook',
                    'komersil' => 'Komersil',
                    'tanah_lebih' => 'Tanah Lebih',
                    'kios' => 'Kios',
                    default => $state,
                })->searchable(),
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

                    BulkAction::make('print')
                    ->label('Print Data')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->action(function (Collection $records) {
                        session(['print_records' => $records->pluck('id')->toArray()]);

                        return redirect()->route('verifikasidajam.print');
                    }),



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
        ])
        ->where('team_id', filament()->getTenant()->id); // filter data sesuai tenant
}

    protected static function mutateFormDataBeforeCreate(array $data): array
{
    $user = filament()->auth()->user();

    if (! $user) {
        throw new \Exception('User harus login untuk membuat data ini.');
    }

    $data['user_id'] = $user->id;
    $data['team_id'] = $user->current_team_id ?? filament()->getTenant()?->id;

    return $data;
}


protected static function mutateFormDataBeforeSave(array $data): array
{
    if (! isset($data['user_id'])) {
        $data['user_id'] = filament()->auth()->id();
    }

    return $data;
}

public static function canViewAny(): bool
{
    $user = auth()->user();
        /** @var \App\Models\User|null $user */

    return $user->hasRole(['admin','Direksi','Legal officer','Super Admin', 'Legal Pajak']);
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
            'index' => Pages\ListGcvVerifikasiDajams::route('/'),
            'create' => Pages\CreateGcvVerifikasiDajam::route('/create'),
            'edit' => Pages\EditGcvVerifikasiDajam::route('/{record}/edit'),
        ];
    }
}

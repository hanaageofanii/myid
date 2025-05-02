<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VerifikasiDajamTkrResource\Pages;
use App\Filament\Resources\VerifikasiDajamTkrResource\RelationManagers;
use App\Models\VerifikasiDajamTkr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

use App\Models\PajakTkr;
use App\Models\PencairanAkadTkr;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use App\Models\FormDpTkr;
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
use App\Models\FormKprTkr;
use App\Models\audit_tkr;
use App\Filament\Resources\TKRResource;
use App\Models\StokTkr;
use App\Filament\Resources\KPRStats;
use Illuminate\Support\Facades\Auth;

class VerifikasiDajamTkrResource extends Resource
{
    protected static ?string $model = VerifikasiDajamTkr::class;

    protected static ?string $title = "Form Verifikasi Dajam TKR";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Data Verifikasi Dajam TKR";
    protected static ?string $navigationLabel = "Verifikasi Dajam TKR";
    protected static ?string $pluralModelLabel = 'Daftar Verifikasi Dajam TKR';
    protected static ?string $navigationIcon = 'heroicon-o-check-badge';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
                ->schema([
                    Select::make('siteplan')
                        ->label('Blok')
                        ->options(fn () => FormKprTkr::pluck('siteplan', 'siteplan'))
                        ->searchable()
                        ->disabled(fn () => ! (function () {
                            /** @var \App\Models\User|null $user */
                            $user = Auth::user();
                            return $user && $user->hasRole(['admin','Legal Pajak']);
                        })())
                        ->reactive()
                        ->afterStateUpdated(function ($state, callable $set) {
                            $kprData = FormKprTkr::where('siteplan', $state)->first();
                            $akadData = PencairanAkadTkr::where('siteplan', $state)->first();
                            $pajakData = PajakTkr::where('siteplan', $state)->first();
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
                //
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListVerifikasiDajamTkrs::route('/'),
            'create' => Pages\CreateVerifikasiDajamTkr::route('/create'),
            'edit' => Pages\EditVerifikasiDajamTkr::route('/{record}/edit'),
        ];
    }
}

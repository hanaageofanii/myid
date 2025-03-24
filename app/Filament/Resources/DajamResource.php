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

class DajamResource extends Resource
{
    protected static ?string $model = Dajam::class;

    protected static ?string $title = "Form Input Dajam";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Data Dajam";
    protected static ?string $navigationLabel = "Dajam";
    protected static ?string $pluralModelLabel = 'Daftar Dajam';
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

    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListDajams::route('/'),
            'create' => Pages\CreateDajam::route('/create'),
            'edit'   => Pages\EditDajam::route('/{record}/edit'),
        ];
    }
}

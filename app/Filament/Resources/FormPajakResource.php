<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormPajakResource\Pages;
use App\Filament\Resources\FormPajakResource\RelationManagers;
use App\Models\form_kpr;
use App\Models\form_legal;
use App\Models\form_pajak;
use App\Models\FormPajak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormPajakResource extends Resource
{
    protected static ?string $model = form_pajak::class;

    protected static ?string $title = "Form Validasi PPH";

    protected static ?string $navigationGroup = "Legal";

    protected static ?string $pluralLabel = "Data Validasi PPH";

    protected static ?string $navigationLabel = "Validasi PPH";

    protected static ?string $pluralModelLabel = 'Daftar Validasi PPH';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('siteplan')
                ->label('Blok')
                ->nullable()
                ->options(fn() => form_kpr::pluck('siteplan', 'siteplan')->toArray())
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $kprData = form_kpr::where('siteplan', $state)->first();
                    if ($kprData) {
                        $set('kavling', $kprData->jenis_unit);
                        $set('nama_konsumen', $kprData->nama_konsumen);
                        $set('nik', $kprData->nik);
                        $set('npwp', $kprData->npwp);
                        $set('alamat', $kprData->alamat);
                        $set('harga', $kprData->harga);
                        $set('pembayaran', $kprData->pembayaran);
            
                        // Hitung NPOPTKP
                        $npoptkp = (int) $kprData->harga >= 80000000 ? 80000000 : 0;
                        $set('npoptkp', $npoptkp);
            
                        // Hitung BPHTB (5% dari harga - NPOPTKP)
                        $set('jumlah_bphtb', max(0.05 * ($kprData->harga - $npoptkp), 0));
            
                        // Tentukan Tarif PPH
                        $tarif_pph = ($kprData->jenis_unit === 'standar' && $kprData->pembayaran === 'kpr') ? 0.01 : 0.025;
                        $set('tarif_pph', ($tarif_pph * 100) . '%'); 
            
                        // Hitung Jumlah PPH
                        $jumlah_pph = max(($kprData->harga * $tarif_pph), 0);
                        $set('jumlah_pph', $jumlah_pph);
                    }
            

                        $legalData = form_legal::where('siteplan', $state)->first();
                        if ($legalData) {
                            $set('no_sertifikat', $legalData->no_sertifikat);
                            $set('nop', $legalData->nop);
                            $set('luas_tanah', $legalData->luas_sertifikat);
                        }
                    }),

            Forms\Components\TextInput::make('no_sertifikat')->nullable()->label('No. Sertifikat'),

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
                    ->label('Jenis Unit'),
                    
                Forms\Components\TextInput::make('nama_konsumen')->nullable()->label('Nama Konsumen'),

                Forms\Components\TextInput::make('nik')->nullable()->label('NIK'),
                Forms\Components\TextInput::make('npwp')->nullable()->label('NPWP'),
                Forms\Components\Textarea::make('alamat')->nullable()->label('Alamat'),
                Forms\Components\TextInput::make('nop')->nullable()->label('NOP'),
                Forms\Components\TextInput::make('luas_tanah')->nullable()->label('Luas Sertifikat'),
                Forms\Components\TextInput::make('harga')
                ->numeric()
                ->nullable()
                ->label('Harga')
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set, callable $get) {
                    $npoptkp = $state >= 80000000 ? 80000000 : 0;
                    $set('npoptkp', $npoptkp);
                    $set('jumlah_bphtb', max(0.05 * ($state - $npoptkp), 0));
                }),

                Forms\Components\TextInput::make('npoptkp')
                    ->numeric()
                    ->nullable()
                    ->label('NPOPTKP')
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $harga = $get('harga');
                        $set('jumlah_bphtb', max(0.05 * ($harga - $state), 0));
                    }),

                Forms\Components\TextInput::make('jumlah_bphtb')->numeric()->nullable()->label('Jumlah BPHTB'),

                Forms\Components\Select::make('tarif_pph')
                ->label('Tarif PPH')
                ->options(['1%' => '1 %', '2.5%' => '2.5 %'])
                ->disabled(),

                Forms\Components\TextInput::make('jumlah_pph')->numeric()->nullable()->label('Jumlah PPH'),
                Forms\Components\TextInput::make('kode_billiing_pph')->numeric()->nullable()->label('Kode Billing PPH'),
                Forms\Components\DatePicker::make('tanggal_bayar_pph')->nullable()->label('Tanggal Pembayaran PPH'),
                Forms\Components\TextInput::make('ntpnpph')->numeric()->nullable()->label('NTPN PPH'),
                Forms\Components\TextInput::make('validasi_pph')->numeric()->nullable()->label('Validasi PPH'),
                Forms\Components\DatePicker::make('tanggal_validasi')->nullable()->label('Tanggal Validasi'),

                Forms\Components\Fieldset::make('Dokumen')
                ->schema([
                    Forms\Components\FileUpload::make('up_kode_billing')->disk('public')->nullable()->label('Upload Kode Billing'),
                    Forms\Components\FileUpload::make('up_bukti_setor_pajak')->disk('public')->nullable()->label('Upload Bukti Setor Pajak'),
                    Forms\Components\FileUpload::make('up_suket_validasi')->disk('public')->nullable()->label('Upload Suket Validasi'),
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
            'index' => Pages\ListFormPajaks::route('/'),
            'create' => Pages\CreateFormPajak::route('/create'),
            'edit' => Pages\EditFormPajak::route('/{record}/edit'),
        ];
    }
}

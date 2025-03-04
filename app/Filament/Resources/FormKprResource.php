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

class FormKprResource extends Resource
{
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
                    ])->required(),
                Forms\Components\TextInput::make('blok')->nullable(),
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
                
                Forms\Components\FileUpload::make('ktp')->nullable(),
                Forms\Components\FileUpload::make('kk')->nullable(),
                Forms\Components\FileUpload::make('npwp_upload')->nullable(),
                Forms\Components\FileUpload::make('buku_nikah')->nullable(),
                Forms\Components\FileUpload::make('akte_cerai')->nullable(),
                Forms\Components\FileUpload::make('akte_kematian')->nullable(),
                Forms\Components\FileUpload::make('kartu_bpjs')->nullable(),
                Forms\Components\FileUpload::make('drk')->nullable(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('jenis_unit')->sortable(),
                Tables\Columns\TextColumn::make('blok')->sortable(),
                Tables\Columns\TextColumn::make('type')->sortable(),
                Tables\Columns\TextColumn::make('luas')->sortable(),
                Tables\Columns\TextColumn::make('harga')->sortable(),
                Tables\Columns\TextColumn::make('nama_konsumen')->sortable(),
                Tables\Columns\TextColumn::make('tanggal_booking')->date(),
                Tables\Columns\TextColumn::make('status_akad')->sortable(),
                Tables\Columns\TextColumn::make('ktp')->sortable(),
                Tables\Columns\TextColumn::make('kk')->sortable(),
                Tables\Columns\TextColumn::make('npwp_upload')->sortable(),
                Tables\Columns\TextColumn::make('buku_nikah')->sortable(),
                Tables\Columns\TextColumn::make('akte_cerai')->sortable(),
                Tables\Columns\TextColumn::make('akte_kematian')->sortable(),
                Tables\Columns\TextColumn::make('kartu_bpjs')->sortable(),
                Tables\Columns\TextColumn::make('drk')->sortable(),
            ])
            ->filters([
                // Tambahkan filter jika diperlukan
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormKprs::route('/'),
            'create' => Pages\CreateFormKpr::route('/create'),
            'edit' => Pages\EditFormKpr::route('/{record}/edit'),
        ];
    }
}
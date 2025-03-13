<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormPpnResource\Pages;
use App\Filament\Resources\FormPpnResource\RelationManagers;
use App\Models\form_ppn;
use App\Models\FormPpn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormPpnResource extends Resource  
{
    protected static ?string $model = form_ppn::class;

    protected static ?string $title = "Form Data Faktur PPN";

    protected static ?string $navigationGroup = "Legal";

    protected static ?string $pluralLabel = "Data Faktur PPN";

    protected static ?string $navigationLabel = "Faktur PPN";

    protected static ?string $pluralModelLabel = 'Daftar Faktur PPN';

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('siteplan')->numeric()->nullable()->label('Siteplan'),
                Forms\Components\TextInput::make('kavling')->numeric()->nullable()->label('Jenis Unit'),
                Forms\Components\TextInput::make('nama_konsumen')->numeric()->nullable()->label('Nama Konsumen'),
                Forms\Components\TextInput::make('nik')->numeric()->nullable()->label('NIK'),
                Forms\Components\TextInput::make('npwp')->numeric()->nullable()->label('NPWP'),
                Forms\Components\TextInput::make('alamat')->numeric()->nullable()->label('Alamat'),
                Forms\Components\TextInput::make('no_seri_faktur')->numeric()->nullable()->label('No. Seri Faktur'),
                Forms\Components\DatePicker::make('tanggal_faktur')->nullable()->label('Tanggal Faktur'),
                Forms\Components\TextInput::make('harga_jual')->numeric()->nullable()->label('Harga Jual'),
                Forms\Components\TextInput::make('dpp_ppn')->numeric()->nullable()->label('DPP PPN'),  
                Forms\Components\TextInput::make('tarif_ppn')->numeric()->nullable()->label('Tarif PPN'),  
                Forms\Components\TextInput::make('jumlah_ppn')->numeric()->nullable()->label('Jumlah PPN'), 
                Forms\Components\TextInput::make('status_ppn')->numeric()->nullable()->label('Status PPN'),     
                Forms\Components\DatePicker::make('tanggal_bayar_ppn')->nullable()->label('Tanggal Faktur'),
                Forms\Components\TextInput::make('ntpn_ppn')->numeric()->nullable()->label('BTPN PPN'),

                Forms\Components\Fieldset::make('Dokumen')
                ->schema([
                    Forms\Components\FileUpload::make('up_bukti_setor_ppn')->disk('public')->nullable()->label('Upload Bukti Setor PPN'),
                    Forms\Components\FileUpload::make('up_efaktur')->disk('public')->nullable()->label('Upload E-Faktur'),
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
            'index' => Pages\ListFormPpns::route('/'),
            'create' => Pages\CreateFormPpn::route('/create'),
            'edit' => Pages\EditFormPpn::route('/{record}/edit'),
        ];
    }
}

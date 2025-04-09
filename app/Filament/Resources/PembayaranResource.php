<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PembayaranResource\Pages;
use App\Filament\Resources\PembayaranResource\RelationManagers;
use App\Models\Pembayaran;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PembayaranResource extends Resource
{
    protected static ?string $model = Pembayaran::class;

    protected static ?string $title = "Pembayaran";
    protected static ?string $navigationGroup = "Kas & Bank";
    protected static ?string $pluralLabel = "Data Pembayaran";
    protected static ?string $navigationLabel = "Pembayaran";
    protected static ?string $pluralModelLabel = 'Daftar Pembayaran';
    protected static ?string $navigationIcon = 'heroicon-o-credit-card';
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Fieldset::make('Data')
                ->schema([
                    TextInput::make('kasbank')
                        ->label('Kas / Bank')
                        ->required(),
                    DatePicker::make('tanggal')
                    ->label('Tanggal')
                    ->required(),
                    Textinput::make('no_bukti')
                    ->label('No. Bukti')
                    ->required(),

                    Fieldset::make('Info Lainnya')
                    ->schema([
                    
                        Textinput::make('no_cek')
                        ->label('No. Cek')
                        ->required(),

                        Textinput::make('pemberi')
                        ->label('Pemberi')
                        ->required(),

                        Textarea::make('catatan')
                        ->label('Catatan')
                        ->required(),
                    
                        Fieldset::make('Dokumen atau Bukti')
                        ->schema([
                            FileUpload::make('bukti_bukti')->disk('public')->nullable()->label('Upload Bukti Lainnya')
                            ->downloadable()->previewable(false),
                        ])
                    ])
                ])            
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
            'index' => Pages\ListPembayarans::route('/'),
            'create' => Pages\CreatePembayaran::route('/create'),
            'edit' => Pages\EditPembayaran::route('/{record}/edit'),
        ];
    }
}

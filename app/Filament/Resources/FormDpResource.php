<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormDpResource\Pages;
use App\Models\form_dp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class FormDpResource extends Resource
{
    protected static ?string $model = form_dp::class;

    protected static ?string $title = "Form Input Data Uang Muka";

    protected static ?string $navigationGroup = "Legal";

    protected static ?string $pluralLabel = "Data Uang Muka";

    protected static ?string $navigationLabel = "Uang Muka";

    protected static ?string $pluralModelLabel = 'Daftar Uang Muka';

    protected static ?string $navigationIcon = 'heroicon-o-arrow-uturn-down';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Fieldset::make('Data Konsumen')
                ->schema([
                    TextInput::make('siteplan')->required()->label('Site Plan'),
                    TextInput::make('nama_konsumen')->required()->label('Nama Konsumen'),
                    TextInput::make('harga')->required()->numeric()->label('Harga'),
                    TextInput::make('max_kpr')->required()->numeric()->label('Maksimal KPR'),
                ]),

            Fieldset::make('Pembayaran')
                ->schema([
                    TextInput::make('sbum')->required()->label('SBUM'),
                    TextInput::make('sisa_pembayaran')->required()->numeric()->label('Sisa Pembayaran'),
                    TextInput::make('dp')->required()->numeric()->label('Uang Muka (DP)'),
                    TextInput::make('laba_rugi')->required()->numeric()->label('Laba Rugi'),
                    DatePicker::make('tanggal_terima_dp')->required()->label('Tanggal Terima Uang Muka'),
                    Select::make('pembayaran')
                        ->options([
                            'cash' => 'Cash',
                            'potong_komisi' => 'Potong Komisi',
                            'promo' => 'Promo',
                        ])
                        ->required()
                        ->label('Pembayaran'),
                ]),

            Fieldset::make('Dokumen')
                ->schema([
                    FileUpload::make('up_kwitansi')->disk('public')->nullable()->label('Kwitansi')
                        ->downloadable()->previewable(false),
                    FileUpload::make('up_pricelist')->disk('public')->nullable()->label('Price List')
                        ->downloadable()->previewable(false),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siteplan')->label('Blok'),
                TextColumn::make('nama_konsumen')->label('Nama Konsumen'),
                TextColumn::make('harga')->label('Harga'),
                TextColumn::make('max_kpr')->label('Max KPR'),
                TextColumn::make('sbum')->label('SBUM'),
                TextColumn::make('sisa_pembayaran')->label('Sisa Pembayaran'),
                TextColumn::make('dp')->label('Uang Muka'),
                TextColumn::make('laba_rugi')->label('Laba Rugi Uang Muka'),
                TextColumn::make('tanggal_terima_dp')
                    ->label('Tanggal Terima DP')
                    ->formatStateUsing(fn ($state) => Carbon::parse($state)->translatedFormat('d F Y')),
                TextColumn::make('pembayaran')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                            'cash' => 'Cash',
                            'potong_komisi' => 'Potong Komisi',
                            'promo' => 'Promo',
                        default => ucfirst($state), 
                    })
                    ->sortable()
                    ->searchable()
                    ->label('Pembayaran'),
                TextColumn::make('up_kwitansi')
                    ->label('Kwitansi')
                    ->formatStateUsing(fn ($record) => $record->up_kwitansi 
                        ? '<a href="' . Storage::url($record->up_kwitansi) . '" target="_blank">Lihat</a> | 
                           <a href="' . Storage::url($record->up_kwitansi) . '" download>Download</a>' 
                        : 'Tidak Ada Dokumen')
                    ->html(),
                TextColumn::make('up_pricelist')
                    ->label('Price List')
                    ->formatStateUsing(fn ($record) => $record->up_pricelist 
                        ? '<a href="' . Storage::url($record->up_pricelist) . '" target="_blank">Lihat</a> | 
                           <a href="' . Storage::url($record->up_pricelist) . '" download>Download</a>' 
                        : 'Tidak Ada Dokumen')
                    ->html(),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListFormDps::route('/'),
            'create' => Pages\CreateFormDp::route('/create'),
            'edit' => Pages\EditFormDp::route('/{record}/edit'),
        ];
    }
}

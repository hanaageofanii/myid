<?php

namespace App\Filament\Resources;

use App\Filament\Resources\KartuKontrolGCVResource\Pages;
use App\Filament\Resources\KartuKontrolGCVResource\RelationManagers;
use App\Models\Kartu_kontrolGCV;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class KartuKontrolGCVResource extends Resource
{
    protected static ?string $model = Kartu_kontrolGCV::class;

    protected static ?string $title = "Form Kartu Kontrol";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Kartu Kontrol";
    protected static ?string $navigationLabel = "Keuangan > Kartu Kontrol";
    protected static ?string $pluralModelLabel = 'Kartu Kontrol';
    protected static ?string $navigationIcon = 'heroicon-o-cursor-arrow-ripple                                                                                                                                                                                                                                           ';
    protected static ?int $navigationSort = 18;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
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
            'index' => Pages\ListKartuKontrolGCVS::route('/'),
            'create' => Pages\CreateKartuKontrolGCV::route('/create'),
            'edit' => Pages\EditKartuKontrolGCV::route('/{record}/edit'),
        ];
    }
}
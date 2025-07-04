<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvDatatanahResource\Pages;
use App\Filament\Resources\GcvDatatanahResource\RelationManagers;
use App\Models\gcv_datatanah;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GcvDatatanahResource extends Resource
{
    protected static ?string $model = gcv_datatanah::class;

    protected static ?string $title = "Data Tanah";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Tanah";
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationLabel = 'Legal > Data Tanah';
    protected static ?string $pluralModelLabel = 'Data Tanah';
    protected static ?int $navigationSort = 13;

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
            'index' => Pages\ListGcvDatatanahs::route('/'),
            'create' => Pages\CreateGcvDatatanah::route('/create'),
            'edit' => Pages\EditGcvDatatanah::route('/{record}/edit'),
        ];
    }
}
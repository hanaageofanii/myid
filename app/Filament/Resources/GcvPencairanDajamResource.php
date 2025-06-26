<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvPencairanDajamResource\Pages;
use App\Filament\Resources\GcvPencairanDajamResource\RelationManagers;
use App\Models\gcv_pencairan_dajam;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GcvPencairanDajamResource extends Resource
{
    protected static ?string $model = gcv_pencairan_dajam::class;

    protected static ?string $title = "Data Pencairan Dajam";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Pencairan Dajam";
    protected static ?string $navigationLabel = 'Keuangan > Pencairan Dajam';
    protected static ?string $pluralModelLabel = 'Data Pencairan Dajam';
    protected static ?int $navigationSort = 7;
    protected static ?string $navigationIcon = 'heroicon-o-queue-list';

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
            'index' => Pages\ListGcvPencairanDajams::route('/'),
            'create' => Pages\CreateGcvPencairanDajam::route('/create'),
            'edit' => Pages\EditGcvPencairanDajam::route('/{record}/edit'),
        ];
    }
}

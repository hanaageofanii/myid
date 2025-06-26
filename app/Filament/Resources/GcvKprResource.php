<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvKprResource\Pages;
use App\Filament\Resources\GcvKprResource\RelationManagers;
use App\Models\gcv_kpr;
use App\Models\GcvKpr;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GcvKprResource extends Resource
{
    protected static ?string $model = gcv_kpr::class;

    protected static ?string $title = "Data Akad";
    protected static ?string $navigationGroup = "GCV";
    protected static ?string $pluralLabel = "Data Akad";
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationLabel = 'KPR > Data Akad';
    protected static ?string $pluralModelLabel = 'Data Akad';
    protected static ?int $navigationSort = 5;

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
            'index' => Pages\ListGcvKprs::route('/'),
            'create' => Pages\CreateGcvKpr::route('/create'),
            'edit' => Pages\EditGcvKpr::route('/{record}/edit'),
        ];
    }
}

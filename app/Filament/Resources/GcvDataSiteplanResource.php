<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GcvDataSiteplanResource\Pages;
use App\Filament\Resources\GcvDataSiteplanResource\RelationManagers;
use App\Models\GcvDataSiteplan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GcvDataSiteplanResource extends Resource
{
    protected static ?string $model = GcvDataSiteplan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

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
            'index' => Pages\ListGcvDataSiteplans::route('/'),
            'create' => Pages\CreateGcvDataSiteplan::route('/create'),
            'edit' => Pages\EditGcvDataSiteplan::route('/{record}/edit'),
        ];
    }
}

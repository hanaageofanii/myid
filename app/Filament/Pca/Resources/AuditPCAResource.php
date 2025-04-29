<?php

namespace App\Filament\Pca\Resources;

use App\Filament\Pca\Resources\AuditPCAResource\Pages;
use App\Filament\Pca\Resources\AuditPCAResource\RelationManagers;
use App\Models\AuditPCA;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AuditPCAResource extends Resource
{
    protected static ?string $model = AuditPCA::class;

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
            'index' => Pages\ListAuditPCAS::route('/'),
            'create' => Pages\CreateAuditPCA::route('/create'),
            'edit' => Pages\EditAuditPCA::route('/{record}/edit'),
        ];
    }
}

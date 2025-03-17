<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormDpResource\Pages;
use App\Filament\Resources\FormDpResource\RelationManagers;
use App\Models\form_dp;
use App\Models\FormDp;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormDpResource extends Resource
{
    protected static ?string $model = form_dp::class;

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
            'index' => Pages\ListFormDps::route('/'),
            'create' => Pages\CreateFormDp::route('/create'),
            'edit' => Pages\EditFormDp::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormPajakPcaResource\Pages;
use App\Filament\Resources\FormPajakPcaResource\RelationManagers;
use App\Models\FormPajakPca;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormPajakPcaResource extends Resource
{
    protected static ?string $model = FormPajakPca::class;

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
            'index' => Pages\ListFormPajakPcas::route('/'),
            'create' => Pages\CreateFormPajakPca::route('/create'),
            'edit' => Pages\EditFormPajakPca::route('/{record}/edit'),
        ];
    }
}

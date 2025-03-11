<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormPajakResource\Pages;
use App\Filament\Resources\FormPajakResource\RelationManagers;
use App\Models\form_pajak;
use App\Models\FormPajak;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormPajakResource extends Resource
{
    protected static ?string $model = form_pajak::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                
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
            'index' => Pages\ListFormPajaks::route('/'),
            'create' => Pages\CreateFormPajak::route('/create'),
            'edit' => Pages\EditFormPajak::route('/{record}/edit'),
        ];
    }
}

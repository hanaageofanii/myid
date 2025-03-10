<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FormLegalResource\Pages;
use App\Filament\Resources\FormLegalResource\RelationManagers;
use App\Models\form_legal;
use App\Models\FormLegal;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FormLegalResource extends Resource
{
    protected static ?string $model = form_legal::class;

    protected static ?string $title = "Input Sertifikat";
    protected static ?string $navigationGroup = "Legal";
    protected static ?string $pluralLabel = "Form Input Sertifikat";
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationLabel = 'Input Sertifikat';
    protected static ?string $pluralModelLabel = 'Daftar Input Sertifikat';


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
            'index' => Pages\ListFormLegals::route('/'),
            'create' => Pages\CreateFormLegal::route('/create'),
            'edit' => Pages\EditFormLegal::route('/{record}/edit'),
        ];
    }
}
